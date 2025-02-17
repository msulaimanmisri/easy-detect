![EasyDetect Image](public/easy-detect.png)

## Easy Detect - Laravel Error Notification Package
Easy Detect is a Laravel package designed to simplify error monitoring in your application. It automatically sends email notifications whenever an exception occurs, providing detailed error reports to help you quickly identify and resolve issues.

## About the Author
Hi, I'm Sulaiman Misri. Currently I'm working as a Senior Executive in Kuala Lumpur Malaysia. If you find this package useful, feel free to check out my [portfolio](https://sulaimanmisri.com) for more information about my freelance services.

## Prerequisites
* Laravel 10.x or higher
* PHP 8.1 or higher

## Installation
1. Install the package via Composer
```bash
composer require sulaimanmisri/easy-detect
```

2. Publish the configuration file:
```bash
php artisan vendor:publish --tag=easy-detect-config
```

3. Publish the view file:
```bash
php artisan vendor:publish --tag=easy-detect-views
```

4. Update your .env file with the recipients email. You can set more than one recipients.
```bash
EASY_DETECT_RECIPIENTS="userone@email.com, usertwo@email.com"
```

5. Configure your mail settings in .env (e.g., SMTP credentials)

> [!IMPORTANT]
> You must set your `QUEUE_CONNECTION` to `database` in order to send the email in background process. This ensures your application remains responsive while error notifications are being processed. Using database queue also provides better reliability and allows for failed job handling.

## Usage
Once installed, Easy Detect will automatically send email notifications for unhandled exceptions. No additional code is required! 

> [!IMPORTANT]
> This package will run in production only. You should debug via your own method in Local and Staging. Such check the log.

## Configuration
The package comes with a configuration file (`config/easy-Detect.php`) where you can customize its behavior.

## Customizing the Email Template
To customize the email template, publish the views. But of course, there's some default info stated in there. If you change the contect, you might not see the original Error content. I suggest that you only change the UI/UX and not the default data.


