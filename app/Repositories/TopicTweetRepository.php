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
    public function getTopicTweets($user, $topicId);
    public function putTopicTweets($user, $topicId, $tweets);
}
