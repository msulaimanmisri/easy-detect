<?php

declare(strict_types=1);

namespace SulaimanMisri\EasyDetect;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;
use SulaimanMisri\EasyDetect\Mail\SendErrorMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;


class EasyDetectServiceProvider extends ServiceProvider
{
    /**
     * Booting the package
     */
    public function boot(): void
    {
        $this->handleLoadView();
        $this->handlePublishing();
        $this->handleException();
    }

    /**
     * Registering the package
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/easy-detect.php',
            'easy-detect'
        );
    }

    /**
     * Handle the loading of views
     */
    public function handleLoadView(): void
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'easy-detect');
    }

    /**
     * Handle the publishing of config and views
     */
    public function handlePublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/easy-detect.php' => config_path('easy-detect.php'),
            ], 'easy-detect-config');

            $this->publishes([
                __DIR__ . '/resources/views' => resource_path('views/vendor/easy-detect'),
            ], 'easy-detect-views');
        }
    }

    /**
     * Handle the setup of exception handling
     */
    public function handleException(): void
    {
        $this->app->make(ExceptionHandler::class)->reportable(function (\Throwable $error) {
            if ($this->enableCacheChecking($error) === false) {
                return;
            }

            $traces = $error->getTrace();
            $appTrace = $this->getTheIssue($traces);
            $errorFile = $appTrace['file'] ?? $error->getFile();
            $errorLine = $appTrace['line'] ?? $error->getLine();

            /**
             * We using foreach instead of sending all recipients at once is to avoid any 
             * error that might happen during sending the email and to consider the
             * Mailing spam policy.
             */
            foreach (config('easy-detect.recipients') as $recipient) {
                Mail::to($recipient)
                    ->send(new SendErrorMail(
                        errorMessage: $error->getMessage(),
                        errorFile: $errorFile,
                        errorLine: $errorLine,
                        errorTrace: $error->getTraceAsString()
                    ));
            }
        });
    }

    /**
     * Enable cache checking for errors
     * 
     * @param \Throwable $error
     * @return bool Returns false if cache exists, true otherwise
     */
    public function enableCacheChecking($error)
    {
        $cacheKey = 'easy-detect:error:' . md5(
            $error->getMessage() .
                $error->getFile() .
                $error->getLine() .
                $error->getTraceAsString()
        );

        if (Cache::has($cacheKey)) {
            return false;
        }

        Cache::put($cacheKey, true, now()->addMinutes(
            config('easy-detect.cache_duration')
        ));

        return true;
    }

    /**
     * Get the issue from the traces
     * 
     * @param array $traces
     * @return array|null
     */
    public function getTheIssue($traces): ?array
    {
        return collect($traces)->first(function ($trace) {
            return isset($trace['file']) && (
                str_starts_with($trace['file'], base_path('app')) ||
                str_starts_with($trace['file'], base_path('resources'))
            );
        });
    }
}
