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
    protected $topicEntity;

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
        $this->topicEntity = new Topic;
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
        $topics = $this->topicEntity->getTopics($userId) ?? [];
        return $topics;
    }

    public function getTopic($topicId, $userId = null)
    {
        $topic = $this->topicEntity->getTopic($topicId, $userId) ?? null;
        return $topic;
    }

    public function createTopic($param, $userId = null)
    {
        $param = [
            'text' => $param['name'],
            'result_type' => $param['result_type'],
        ];

        return $this->topicEntity->addTopic($param, $userId);
    }

    public function updateTopic($topicId, $param, $userId = null)
    {
        return $this->topicEntity->updateTopic($topicId, $param, $userId);
    }

    public function deleteTopic($topicId, $userId = null)
    {
        return $this->topicEntity->deleteTopic($topicId, $userId);
    }

    public function startMining($topicId, $userId = null)
    {
        $param = ['on_mining' => true];
        $this->updateTopic($topicId, $param, $userId);
        MiningTweets::dispatch($topicId, $userId);
    }

    // TODO: move to graph repo
    public function startAnalyzing($topicId, $userId = null)
    {
        $param = ['on_analyze' => true];
        $this->updateTopic($topicId, $param, $userId);
        GenerateGraph::dispatch($topicId, $userId);
    }

    // TODO: move to graph repo or remove
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
