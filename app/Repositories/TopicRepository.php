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
    public function getTopics($userId = null);
    public function getTopic($topicId, $userId = null);
    public function createTopic($param, $userId = null);
    public function updateTopic($topicId, $param, $userId = null);
    public function deleteTopic($topicId, $userId = null);
    public function startMining($topicId, $userId = null); // TODO: probably move to TopicTweet repo
}
