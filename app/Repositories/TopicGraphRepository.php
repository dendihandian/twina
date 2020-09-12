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
    public function getTopicGraph($topicId, $userId = null);
    public function updateTopicGraph($topicId, $param, $userId = null);
    public function generateGraph($topicId, $userId = null);
    public function normalizeGraph($topicId, $userId = null);
    public function analyzeGraph($topicId, $userId = null);
}
