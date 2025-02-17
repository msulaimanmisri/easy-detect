<?php

declare(strict_types=1);

return [
    /**
     * The email addresses to send the error report to
     */
    'recipients' => array_map('trim', explode(',', env('EASY_DETECT_RECIPIENTS', 'default@example.com'))),

    /**
     * The subject of the error report email
     */
    'subject' => env('EASY_DETECT_MAIL_SUBJECT', 'Error Log Report'),
];
