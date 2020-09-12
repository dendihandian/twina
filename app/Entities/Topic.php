<?php

namespace App\Entities;

use App\Entities\Traits\RESTClientTrait;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Topic.
 *
 * @package namespace App\Entities;
 */
class Topic extends BaseEntity implements Transformable
{
    use TransformableTrait, RESTClientTrait;

    protected function pathBuilder($topicId, $userId = null)
    {
        $path = $userId ? "/users/{$userId}/topics" : "/public/topics";
        $path = $topicId ? $path . "/{$topicId}" : $path;

        return $path;
    }

    # /topics

    public function getTopics($userId = null)
    {
        $restClient = self::getRESTClientInstance();
        $path = $this->pathBuilder(null, $userId);
        $response = $restClient->get($path);
        return $response;
    }

    public function addTopic($param, $userId = null)
    {
        $restClient = self::getRESTClientInstance();
        $path = $this->pathBuilder(null, $userId);
        $response = $restClient->post($path, $param);
        return $response;
    }

    # /topics/{topic}

    public function getTopic($topicId, $userId = null)
    {
        $restClient = self::getRESTClientInstance();
        $path = $this->pathBuilder($topicId, $userId);
        $response = $restClient->get($path);
        return $response;
    }

    public function updateTopic($topicId, $param, $userId = null)
    {
        $restClient = self::getRESTClientInstance();
        $path = $this->pathBuilder($topicId, $userId);
        $response = $restClient->patch($path, $param);
        return $response;
    }

    public function deleteTopic($topicId, $userId = null)
    {
        $restClient = self::getRESTClientInstance();
        $path = $this->pathBuilder($topicId, $userId);
        $response = $restClient->delete($path);
        return $response;
    }
}
