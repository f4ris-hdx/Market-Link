<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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

    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
        'base_url' => env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com'),
        'version' => env('GEMINI_API_VERSION', 'v1beta'),
        'model' => env('GEMINI_MODEL', 'gemini-3.8-flash'),
        'timeout' => env('GEMINI_TIMEOUT', 12),
        'verify' => filter_var(env('GEMINI_SSL_VERIFY', true), FILTER_VALIDATE_BOOLEAN),
    ],

    'maps' => [
        'tile_url' => env('MAP_TILE_URL', 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'),
        'attribution' => env('MAP_ATTRIBUTION', '&copy; OpenStreetMap contributors'),
        'geocoder_url' => env('MAP_GEOCODER_URL', 'https://nominatim.openstreetmap.org/search'),
        'reverse_geocoder_url' => env('MAP_REVERSE_GEOCODER_URL', 'https://nominatim.openstreetmap.org/reverse'),
    ],

];
