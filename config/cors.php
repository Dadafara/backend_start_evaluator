<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    // 'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'registre',
        'logout',
<<<<<<< HEAD
        'auth/google',
        'auth/google/callback'
    ],

    'allowed_methods' => ['GET', 'POST'],
=======
    ],

    'allowed_methods' => ['*'],

>>>>>>> eedfdfde7fa28d3eb9bf9db69a174f4bde8f6272
    'allowed_origins' => ['http://localhost:3000'],
    'allowed_origins_patterns' => [],
<<<<<<< HEAD
    'allowed_headers' => ['Content-Type', 'Authorization', 'x-xsrf-token'],
    'exposed_headers' => ['Access-Control-Allow-Origin', 'Access-Control-Allow-Credentials'],
    'max_age' => 60 * 60,
=======

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

>>>>>>> eedfdfde7fa28d3eb9bf9db69a174f4bde8f6272
    'supports_credentials' => true,

    // 'allowed_methods' => ['GET', 'POST'],
    // 'allowed_origins' => ['http://localhost:3000'],
    // 'allowed_origins_patterns' => [],
    // 'allowed_headers' => ['Content-Type', 'Authorization', 'x-xsrf-token'],
    // 'exposed_headers' => [],
    // 'max_age' => 0,
    // 'supports_credentials' => true,

];

