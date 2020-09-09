<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface TopicGraphRepository.
 *
 * @package namespace App\Repositories;
 */
interface TopicGraphRepository extends RepositoryInterface
{
    public function updateTopicGraph($topicId, $param, $userId = null);
}
