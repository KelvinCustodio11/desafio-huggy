<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'huggy' => [
        'client_id'     => env('HUGGY_CLIENT_ID'),
        'client_secret' => env('HUGGY_CLIENT_SECRET'),
        'redirect'      => env('HUGGY_REDIRECT_URI'),
        'auth_base'     => env('HUGGY_AUTH_BASE', 'https://auth.huggy.app'),
        'api_base'      => env('HUGGY_API_BASE',  'https://api.huggy.app/v3'),
    ],

    'client' => [
        'callback_redirect'  => env('CLIENT_CALLBACK_REDIRECT', 'http://localhost:5173/callback'),
    ],

    'webhook' => [
        'url' => env('WEBHOOK_URL_CONTACTS'),
    ],

    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from' => env('TWILIO_FROM'),
    ],
];
