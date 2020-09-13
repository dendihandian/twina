<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface TopicTweetRepository.
 *
 * @package namespace App\Repositories;
 */
interface TopicTweetRepository extends RepositoryInterface
{
    public function getTopicTweets($topicId, $userId = null);
    public function setTopicTweets($topicId, $tweets, $userId = null);
    public function analyzeTweets($topicId, $userId = null);
}
