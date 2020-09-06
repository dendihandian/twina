<?php

return [
    'database' => env('FIREBASE_DATABASE'),
    'api_key' => env('FIREBASE_API_KEY'),

    'paths' => [
        'public_topics' => '/topics/public',
        'public_topic' => '/topics/public/:topic',
        'public_topic_tweets' => '/topics/public/:topic/tweets',
        'public_topic_tweet' => '/topics/public/:topic/tweets/:tweet',

        'user_topics' => '/topics/users/:user',
        'user_topic' => '/topics/users/:user/:topic',
        'user_topic_tweets' => '/topics/users/:user/:topic/tweets',
        'user_topic_tweet' => '/topics/users/:user/:topic/tweets/:tweet',
    ],
];
