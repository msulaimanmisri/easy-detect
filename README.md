![EasyDetect Image](public/easy-detect.png)

# Easy Detect - Laravel Error Notification Package
Easy Detect is a Laravel package designed to simplify error monitoring in your application. It automatically captures and sends email notifications whenever an exception occurs, providing detailed error reports to help developers quickly identify and resolve issues.

> [!TIP]
> Found this package useful? Don't forget to ⭐ star the repository and share your feedback!

## Features
---
* Automatic error email notifications for unhandled exceptions.

* Customizable email recipients.

* Built-in anti-spam mechanism to prevent excessive notifications.

* Configurable email template for better UI/UX.

* Seamless integration with Laravel's logging system.


## About the Author
Hi, I'm Sulaiman Misri, a Senior Executive based in Kuala Lumpur, Malaysia. If you find this package useful, feel free to explore my [portfolio](https://sulaimanmisri.com) for more information about my freelance services and open-source contributions.

## Requirements
* Laravel 10.x or higher

* PHP 8.1 or higher

* Mail configuration set up in .env

## Installation
Follow these steps to install Easy Detect in your Laravel application:

### 1️⃣ Install via Composer
```
composer require sulaimanmisri/easy-detect
```

### 2️⃣ Publish Configuration File
```
php artisan vendor:publish --tag=easy-detect-config
```

### 3️⃣ Publish Email View Template (optional)
```
php artisan vendor:publish --tag=easy-detect-views
```

### 4️⃣ Configure Email Recipients
Update your .env file with the email addresses where error notifications should be sent. You can specify multiple recipients separated by commas:
```
EASY_DETECT_RECIPIENTS="userone@email.com, usertwo@email.com"
```

### 5️⃣ Set Up Queue Configuration (Recommended)
To ensure email notifications are processed in the background without affecting application performance, set your queue connection to database:
```
QUEUE_CONNECTION=database
```
> [!TIP]
> Important: Background processing ensures better reliability and allows for failed job handling.

## Usage
### Enabling/Disabling Notifications
You can toggle email reporting by modifying the turn_on configuration in `config/easy-detect.php` :
```
'turn_on' => false, // Set to true to enable error notifications
```
> [!IMPORTANT]
> Important: It is recommended to disable error notifications in local or staging environments to avoid unnecessary emails. Enable it in production for real-time error tracking.

### Automatic Error Reporting
Once installed, `Easy Detect` will automatically capture and send email notifications for unhandled exceptions. No additional code is required!

## Configuration
Easy Detect comes with a configuration file (config/easy-detect.php) that allows you to customize its behavior:

### Anti-Spam Mechanism
To prevent excessive error emails, you can cache exceptions. This ensures that the same error is only reported once within a defined period:
```
'cache_duration' => 60, // Cache duration in minutes
```

### Customizing the Email Template
If you want to modify the email template:

1. Publish the views (already covered in step 3 of installation).

2. Edit the published template file located in resources/views/vendor/easy-detect.

> [!NOTE]
> Note: The default email template contains essential error details. It is recommended to only adjust the UI/UX, not the actual error data structure, to maintain the accuracy of reports.

