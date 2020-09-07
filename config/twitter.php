<?php

return [
    'consumer_key' => env('TWITTER_CONSUMER_KEY', null),
    'consumer_secret' => env('TWITTER_CONSUMER_SECRET', null),
    'access_token' => env('TWITTER_ACCESS_TOKEN', null),
    'token_secret' => env('TWITTER_TOKEN_SECRET', null),

    'search' => [
        'default' => [
            'result_type' => env('TWITTER_SEARCH_DEFAULT_RESULT_TYPE', 'recent'),
            'count' => env('TWITTER_SEARCH_DEFAULT_COUNT', 50),
        ],
    ]
];
