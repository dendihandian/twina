<?php

namespace App\Wrappers\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Wrappers\Twitter\Traits\Trends;
use App\Wrappers\Twitter\Traits\Users;
use App\Wrappers\Twitter\Traits\Search;
use App\Wrappers\Twitter\Traits\Account;

class Twitter
{
    use Trends, Users, Search, Account;

    protected $config;
    protected $client;

    public function __construct()
    {
        $this->config = config('twitter');
        $this->client = new TwitterOAuth(
            $this->config['consumer_key'],
            $this->config['consumer_secret'],
            $this->config['access_token'],
            $this->config['token_secret'],
        );
    }
}
