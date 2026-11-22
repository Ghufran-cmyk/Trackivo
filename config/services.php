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

    'weather' => [
        'url' => env('WEATHER_API_URL', 'https://api.open-meteo.com/v1/forecast'),
        'key' => env('WEATHER_API_KEY'),
        'timeout' => (int) env('WEATHER_API_TIMEOUT', 10),
    ],

    'geocoding' => [
        'url' => env('GEOCODING_API_URL', 'https://nominatim.openstreetmap.org/search'),
        'user_agent' => env('GEOCODING_USER_AGENT', 'TrackivoGlobalLogistics/1.0 (contact@trackivo.com)'),
        'timeout' => (int) env('GEOCODING_API_TIMEOUT', 10),
    ],

    'http_client' => [
        'ca_bundle' => env('HTTP_CLIENT_CA_BUNDLE'),
        'verify_ssl' => env('HTTP_CLIENT_VERIFY_SSL', true),
    ],

];
