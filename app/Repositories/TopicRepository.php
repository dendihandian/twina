<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface TopicRepository.
 *
 * @package namespace App\Repositories;
 */
interface TopicRepository extends RepositoryInterface
{
    public function getTopics($user);
    public function getTopic($user, $topicId);
    public function createTopic($user, $param);
    public function startMining($user, $topicId);
}
