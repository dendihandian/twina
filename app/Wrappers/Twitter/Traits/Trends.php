<?php

namespace App\Wrappers\Twitter\Traits;

/**
 * Trends
 */
trait Trends
{
    /**
     * getTrendsByPlaceId
     *
     * @param  int $placeId
     * @return array
     * 
     * ref: https://developer.twitter.com/en/docs/twitter-api/v1/trends/trends-for-location/api-reference/get-trends-place
     */
    public function getTrendsByPlaceId(int $placeId = 1)
    {
        $param = [
            'id' => $placeId,
        ];

        return $this->client->get("trends/place", $param);
    }
}
