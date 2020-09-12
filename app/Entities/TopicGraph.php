<?php

namespace App\Entities;

use App\Entities\Traits\RESTClientTrait;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class TopicGraph.
 *
 * @package namespace App\Entities;
 */
class TopicGraph extends BaseEntity implements Transformable
{
    use TransformableTrait, RESTClientTrait;

    protected function pathBuilder($topicId, $userId = null)
    {
        if ($userId) {
            $path = "/topics/users/{$userId}/{$topicId}/graph";
        } else {
            $path = "/topics/public/{$topicId}/graph";
        }

        return $path;
    }

    public function getTopicGraph($topicId, $userId = null)
    {
        $restClient = self::getRESTClientInstance();
        $path = $this->pathBuilder($topicId, $userId);
        $response = $restClient->get($path);
        return $response;
    }

    public function updateTopicGraph($topicId, $param, $userId = null)
    {
        $restClient = self::getRESTClientInstance();
        $path = $this->pathBuilder($topicId, $userId);
        $response = $restClient->patch($path, $param);
        return $response;
    }
}
