<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TopicTweetRepository;
use App\Entities\TopicTweet;
use App\Validators\TopicTweetValidator;
use App\Wrappers\Firebase\Firebase;

/**
 * Class TopicTweetRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TopicTweetRepositoryEloquent extends BaseRepository implements TopicTweetRepository
{
    protected $firebase;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TopicTweet::class;
    }

    public function __construct()
    {
        $this->firebase = new Firebase;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getTopicTweets($topicId, $userId = null)
    {
        if ($userId) {
            $tweets = $this->firebase->getTopicTweets($userId, $topicId);
        } else {
            $tweets = $this->firebase->getPublicTopicTweets($topicId);
        }

        return $tweets;
    }

    public function putTopicTweets($topicId, $tweets, $userId = null)
    {
        if ($userId) {
            $tweets = $this->firebase->putTopicTweets($userId, $topicId, $tweets);
        } else {
            $tweets = $this->firebase->putPublicTopicTweets($topicId, $tweets);
        }

        return $tweets;
    }
}
