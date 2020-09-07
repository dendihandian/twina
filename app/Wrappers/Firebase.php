<?php

namespace App\Wrappers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class Firebase
{
    protected $config;
    protected $http;

    public function __construct()
    {
        $this->config = config('firebase');
    }

    protected function urlBuilder($path = '', $token = null)
    {
        $url = $this->config['database'] . $path . '.json?key=' . $this->config['api_key'];

        if ($token) {
            $url += '&auth=' . $token;
        }

        return $url;
    }

    public function getPublicTopics()
    {
        $response = Http::get($this->urlBuilder($this->config['paths']['public_topics']));
        return $response->json() ?? [];
    }

    public function getTopics($userId)
    {
        $response = Http::get($this->urlBuilder('/topics/users/' . $userId));
        return $response->json() ?? [];
    }

    public function getTopic($userId, $topicId)
    {
        $response = Http::get($this->urlBuilder("/topics/users/{$userId}/{$topicId}"));
        return $response->json();
    }

    public function addPublicTopic($param = [])
    {
        $defaultParam = [
            'text' => '',
            'last_fetch_tweet' => '',
            'last_fetch_count' => 0,
            'last_fetch_date' => 0,
            'tweets' => null,
            'tweets_count' => 0,
            'on_queue' => false,
            'created_at' => Carbon::now()->toDateTimeString(),
        ];

        $param = array_merge($defaultParam, $param);

        $response = Http::post($this->urlBuilder($this->config['paths']['public_topics']), $param);
        return $response->json();
    }

    public function addTopic($userId, $param = [])
    {
        $defaultParam = [
            'text' => '',
            'last_fetch_tweet' => '',
            'last_fetch_count' => 0,
            'last_fetch_date' => 0,
            'tweets' => null,
            'tweets_count' => 0,
            'on_queue' => false,
            'created_at' => Carbon::now()->toDateTimeString(),
        ];

        $param = array_merge($defaultParam, $param);

        $response = Http::post($this->urlBuilder('/topics/users/' . $userId), $param);
        return $response->json();
    }

    public function updateTopic($userId, $topicId, $param)
    {
        // ref: https://firebase.google.com/docs/reference/rest/database#section-patch
        $response = Http::patch($this->urlBuilder("/topics/users/{$userId}/{$topicId}"), $param);
        return $response->json();
    }

    public function getTopicTweets($userId, $topicId)
    {
        // NOTE: accessing '/topics/users/{$userId}/{$topicId}/tweets' directly will cause the keys changed into index numbers.
        // So we get the topic object first instead and take the tweets.
        $topic = $this->getTopic($userId, $topicId);
        return $topic['tweets'] ?? [];
    }

    public function putTopicTweets($userId, $topicId, $tweets)
    {
        // NOTE: putting associative array into '/topics/users/{$userId}/{$topicId}/tweets' directly will cause the id keys changed into index numbers.
        // So we patch the tweets instead into the topic.
        $response = Http::patch($this->urlBuilder("/topics/users/{$userId}/{$topicId}"), [
            'tweets' => $tweets,
        ]);
        return $response->json();
    }
}
