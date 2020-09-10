<?php

namespace App\Wrappers\Twitter\Traits;

/**
 * Trends
 */
trait Search
{
    /**
     * searchTweets
     *
     * @param  array $params
     * @return array
     * 
     * docs: https://developer.twitter.com/en/docs/twitter-api/v1/tweets/search/api-reference/get-search-tweets
     */
    public function searchTweets($params)
    {
        $params = [
            'q' => $params['q'],
            'result_type' => $params['result_type'] ?? $this->config['search']['default']['result_type'], // mixed, recent, popular
            'count' => $params['count'] ?? $this->config['search']['default']['count'],
            'since_id' => $params['since_id'] ?? null,
        ];

        return $this->client->get("search/tweets", $params);
    }
}
