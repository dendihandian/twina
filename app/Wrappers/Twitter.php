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
}
