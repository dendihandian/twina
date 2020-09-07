<?php

namespace App\Wrappers\Firebase\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

/**
 * 
 */
trait PublicTopic
{
    public function getPublicTopics()
    {
        $response = Http::get($this->urlBuilder('/public/topics'));
        return $response->json() ?? [];
    }

    public function getPublicTopic($topicId)
    {
        $response = Http::get($this->urlBuilder("/public/topics/{$topicId}"));
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

        $response = Http::post($this->urlBuilder('/public/topics'), $param);
        return $response->json();
    }

    public function updatePublicTopic($topicId, $param)
    {
        // ref: https://firebase.google.com/docs/reference/rest/database#section-patch
        $response = Http::patch($this->urlBuilder("/public/topics/{$topicId}"), $param);
        return $response->json();
    }

    public function getPublicTopicTweets($topicId)
    {
        // NOTE: accessing '/topics/users/{$userId}/{$topicId}/tweets' directly will cause the keys changed into index numbers.
        // So we get the topic object first instead and take the tweets.
        $topic = $this->getPublicTopic($topicId);
        return $topic['tweets'] ?? [];
    }

    public function putPublicTopicTweets($topicId, $tweets)
    {
        // NOTE: putting associative array into '/topics/users/{$userId}/{$topicId}/tweets' directly will cause the id keys changed into index numbers.
        // So we patch the tweets instead into the topic.
        $response = Http::patch($this->urlBuilder("/public/topics/{$topicId}"), [
            'tweets' => $tweets,
        ]);
        return $response->json();
    }
}
