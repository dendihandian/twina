<?php

return [
    'database' => env('FIREBASE_DATABASE'),
    'api_key' => env('FIREBASE_API_KEY'),

    'paths' => [
        'public_topics' => '/topics/public',
        'user_topics' => '/topics/users/:user',
        'user_topic' => '/topics/users/:user/:topic',
    ],
];
