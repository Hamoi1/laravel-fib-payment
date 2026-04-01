<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Integration Environment
    |--------------------------------------------------------------------------
    |
    | This value is the environment that you want to choose for FIB integration.
    | Supported values: 'prod', 'stage'
    |
    */
    'environment' => env('FIB_ENVIRONMENT', 'stage'),

    /*
    |--------------------------------------------------------------------------
    | Callback URL
    |--------------------------------------------------------------------------
    |
    | The callback URL that FIB will POST to when payment status changes.
    | Your route should accept POST requests with 'id' and 'status' fields.
    |
    */
    'callback_url' => env('FIB_CALLBACK_URL', 'http://127.0.0.1:8000/fib/callback'),

    /*
    |--------------------------------------------------------------------------
    | OAuth2 Credentials
    |--------------------------------------------------------------------------
    |
    | Your FIB client credentials. These determine live vs test mode.
    |
    */
    'client_id' => env('FIB_CLIENT_ID'),
    'client_secret' => env('FIB_CLIENT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | Default currency for payments. Supported: IQD, USD
    |
    */
    'currency' => env('FIB_CURRENCY', 'IQD'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Timeouts
    |--------------------------------------------------------------------------
    |
    | Connection and request timeouts in seconds.
    |
    */
    'timeout' => env('FIB_TIMEOUT', 15),
    'connect_timeout' => env('FIB_CONNECT_TIMEOUT', 5),

    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    |
    | Number of retries and delay between retries in milliseconds.
    |
    */
    'retry_times' => env('FIB_RETRY_TIMES', 3),
    'retry_delay' => env('FIB_RETRY_DELAY', 200),

    /*
    |--------------------------------------------------------------------------
    | Cache Driver
    |--------------------------------------------------------------------------
    |
    | Cache driver for OAuth token storage. Null uses the app default.
    |
    */
    'cache_driver' => env('FIB_CACHE_DRIVER', 'fib_oauth_access_token'),

    /*
    |--------------------------------------------------------------------------
    | Refund Window
    |--------------------------------------------------------------------------
    |
    | ISO 8601 duration for how long payments remain refundable.
    | Default: P7D (7 days)
    |
    */
    'refundable_for' => env('FIB_REFUNDABLE_FOR', 'P7D'),

    /*
    |--------------------------------------------------------------------------
    | Allowed Webhook IPs
    |--------------------------------------------------------------------------
    |
    | IP addresses allowed to send webhook requests. Empty array skips check.
    |
    */
    'allowed_ips' => explode(',', env('FIB_ALLOWED_CALLBACK_IPS', '')),
];
