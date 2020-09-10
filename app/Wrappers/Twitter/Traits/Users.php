<?php

namespace App\Wrappers\Twitter\Traits;

/**
 * Users
 */
trait Users
{
    /**
     * getUserByScreenName
     *
     * @param  string $screenName
     * @return object
     * 
     * ref: https://developer.twitter.com/en/docs/twitter-api/v1/accounts-and-users/follow-search-get-users/api-reference/get-users-show
     */
    public function getUserByScreenName(string $screenName)
    {
        $param = ['screen_name' => $screenName];
        return $this->client->get("users/show", $param);
    }

    /**
     * getUserByUserId
     *
     * @param  int $userId
     * @return object
     * 
     * ref: https://developer.twitter.com/en/docs/twitter-api/v1/accounts-and-users/follow-search-get-users/api-reference/get-users-show
     */
    public function getUserByUserId(int $userId)
    {
        $param = ['user_id' => $userId];
        return $this->client->get("users/show", $param);
    }


    /**
     * getUsersByScreenNames
     *
     * @param  array $screenNames
     * @return array
     * 
     * ref: https://developer.twitter.com/en/docs/twitter-api/v1/accounts-and-users/follow-search-get-users/api-reference/get-users-lookup
     */
    public function getUsersByScreenNames(array $screenNames)
    {
        $param = ['screen_name' => $screenNames];
        return $this->client->get("users/lookup", $param);
    }

    /**
     * getUsersByUserIds
     *
     * @param  array $userIds
     * @return array
     * 
     * ref: https://developer.twitter.com/en/docs/twitter-api/v1/accounts-and-users/follow-search-get-users/api-reference/get-users-lookup
     */
    public function getUsersByUserIds(array $userIds)
    {
        $param = ['user_id' => $userIds];
        return $this->client->get("users/lookup", $param);
    }
}
