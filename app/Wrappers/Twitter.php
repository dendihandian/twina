<?php

namespace App\Wrappers;

use Abraham\TwitterOAuth\TwitterOAuth;

class Twitter
{
    protected $config;
    protected $connection;

    public function __construct()
    {
        $this->config = config('twitter');
        $this->connection = new TwitterOAuth(
            $this->config['consumer_key'],
            $this->config['consumer_secret'],
            $this->config['access_token'],
            $this->config['token_secret'],
        );
    }

    public function getAccount()
    {
        return $this->connection->get("account/verify_credentials");
    }

    public function searchTweets($params)
    {
        // docs: https://developer.twitter.com/en/docs/twitter-api/v1/tweets/search/api-reference/get-search-tweets

        $params = [
            'q' => $params['q'],
            'result_type' => $params['result_type'] ?? $this->config['search']['default']['result_type'], // mixed, recent, popular
            'count' => $params['count'] ?? $this->config['search']['default']['count'],
            'since_id' => $params['since_id'] ?? null,
        ];

        return $this->connection->get("search/tweets", $params);
    }
}
