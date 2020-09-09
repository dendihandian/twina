<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TopicRepository;
use App\Entities\Topic;
use App\Validators\TopicValidator;
use App\Jobs\MiningTweets;
use App\Jobs\GenerateGraph;
use App\Jobs\ComplementGraph;
use App\Wrappers\Firebase\Firebase;

/**
 * Class TopicRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TopicRepositoryEloquent extends BaseRepository implements TopicRepository
{
    protected $firebase;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Topic::class;
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

    public function getSelectedTopic($userId = null)
    {
        if ($userId) {
            $topicId = $this->firebase->getSelectedUserTopic($userId);
        } else {
            $topicId = $this->firebase->getSelectedPublicTopic();
        }

        $topic = $this->getTopic($topicId, $userId);

        return $topic;
    }

    public function setSelectedTopic($topicId, $userId = null)
    {
        if ($userId) {
            $this->firebase->setSelectedUserTopic($userId, $topicId);
        } else {
            $this->firebase->setSelectedPublicTopic($topicId);
        }
    }

    public function getTopics($userId = null)
    {
        if ($userId) {
            return $this->firebase->getTopics($userId);
        } else {
            return $this->firebase->getPublicTopics();
        }
    }

    public function getTopic($topicId, $userId = null)
    {
        if ($userId) {
            return $this->firebase->getTopic($userId, $topicId);
        } else {
            return $this->firebase->getPublicTopic($topicId);
        }
    }

    public function createTopic($param, $userId = null)
    {
        $param = [
            'text' => $param['name'],
            'result_type' => $param['result_type'],
        ];

        if ($userId) {
            $this->firebase->addTopic($userId, $param);
        } else {
            $this->firebase->addPublicTopic($param);
        }
    }

    public function updateTopic($topicId, $param, $userId = null)
    {
        if ($userId) {
            $this->firebase->updateTopic($userId, $topicId, $param);
        } else {
            $this->firebase->updatePublicTopic($topicId, $param);
        }
    }

    public function deleteTopic($topicId, $userId = null)
    {
        $this->firebase->deleteTopic($topicId, $userId);
    }

    public function startMining($topicId, $userId = null)
    {
        $param = ['on_queue' => true];

        if ($userId) {
            $this->firebase->updateTopic($userId, $topicId, $param);
        } else {
            $this->firebase->updatePublicTopic($topicId, $param);
        }

        MiningTweets::dispatch($topicId, $userId);
    }

    public function startAnalyzing($topicId, $userId = null)
    {
        $param = ['on_analyze' => true];

        if ($userId) {
            $this->firebase->updateTopic($userId, $topicId, $param);
        } else {
            $this->firebase->updatePublicTopic($topicId, $param);
        }

        GenerateGraph::dispatch($topicId, $userId);
    }

    public function startComplementingGraph($topicId, $userId = null)
    {
        $param = ['on_complement_graph' => true];

        if ($userId) {
            $this->firebase->updateTopic($userId, $topicId, $param);
        } else {
            $this->firebase->updatePublicTopic($topicId, $param);
        }

        ComplementGraph::dispatch($topicId, $userId);
    }
}
