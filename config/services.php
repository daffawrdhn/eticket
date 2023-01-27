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
        'domain' => env('sandbox783c20caab3d476bb94d54ccd9e3fe5f.mailgun.org'),
        'secret' => env('94aa491c3d43e5197e0c48e3e9d55b32-c9746cf8-e29a74ae'),
        'endpoint' => env('94aa491c3d43e5197e0c48e3e9d55b32-c9746cf8-e29a74ae', 'https://api.mailgun.net/v3/sandbox783c20caab3d476bb94d54ccd9e3fe5f.mailgun.org'),
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

];
