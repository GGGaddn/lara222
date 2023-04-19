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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'wildberries' => [
        'suppliers' => [
            'url' => env('WILDBERRIES_SUPPLIERS_URL'),
            'key' => env('WILDBERRIES_SUPPLIERS_KEY'),
        ],

        'statistics' => [
            'url' => env('WILDBERRIES_STATISTICS_URL'),
            'key' => env('WILDBERRIES_STATISTICS_KEY'),
        ],
    ],

    'ozon' => [
        'api_url' => env('OZON_API_URL'),
        'client_id' => env('OZON_CLIENT_ID'),
        'api_key' => env('OZON_API_KEY'),
    ],
];
