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

            /**
             * By right, the email only should be sent in production environment
             * As for local and staging, you can just log the error
             */
            if (app()->environment('local')) {
                return;
            }

            /**
             * To prevent spamming the email, we will cache the error message for 5 minutes
             * If the same error message is thrown within 5 minutes, we will not send the email
             */
            $cacheKey = 'easy-detect:error:' . md5($error->getMessage() . $error->getFile() . $error->getLine());

            if (Cache::has($cacheKey)) {
                return;
            }

            Cache::put($cacheKey, true, now()->addMinutes(5));

            $traces = $error->getTrace();

            $appTrace = collect($traces)->first(function ($trace) {
                return isset($trace['file']) && (
                    str_starts_with($trace['file'], base_path('app')) ||
                    str_starts_with($trace['file'], base_path('resources'))
                );
            });

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
}
