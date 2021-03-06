<?php

namespace App\Entities;

use App\Entities\Traits\RESTClientTrait;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class TopicTweet.
 *
 * @package namespace App\Entities;
 */
class TopicTweet extends BaseEntity implements Transformable
{
    use TransformableTrait, RESTClientTrait;

    protected function pathBuilder($topicId, $userId = null)
    {
        if ($userId) {
            $path = "/users/{$userId}/topics/{$topicId}/tweets";
        } else {
            $path = "/public/topics/{$topicId}/tweets";
        }

        return $path;
    }

    public function getTopicTweets($topicId, $userId = null)
    {
        $restClient = self::getRESTClientInstance();
        $path = $this->pathBuilder($topicId, $userId);
        $response = $restClient->get($path);
        return $response;
    }
}
