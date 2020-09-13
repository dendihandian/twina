<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TopicTweetRepository;
use App\Entities\TopicTweet;
use App\Jobs\AnalyzeTweets;

/**
 * Class TopicTweetRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TopicTweetRepositoryEloquent extends BaseRepository implements TopicTweetRepository
{
    protected $topicTweetEntity;
    protected $topicRepository;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TopicTweet::class;
    }

    public function __construct(TopicRepository $topicRepository)
    {
        $this->topicRepository = $topicRepository;
        $this->topicTweetEntity = new TopicTweet;
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
        return $this->topicTweetEntity->getTopicTweets($topicId, $userId);
    }

    public function setTopicTweets($topicId, $tweets, $userId = null)
    {
        return $this->topicTweetEntity->setTopicTweets($topicId, $tweets, $userId);
    }

    public function analyzeTweets($topicId, $userId = null)
    {
        $this->topicRepository->updateTopic($topicId, [
            'on_analyze_tweets' => true
        ], $userId);

        AnalyzeTweets::dispatch($topicId, $userId);
    }
}
