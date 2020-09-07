<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TopicRepository;
use App\Entities\Topic;
use App\Validators\TopicValidator;
use App\Jobs\MiningTopic;
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

    public function startMining($topicId, $userId = null)
    {
        $param = ['on_queue' => true];

        if ($userId) {
            $this->firebase->updateTopic($userId, $topicId, $param);
            MiningTopic::dispatch($userId, $topicId);
        } else {
            $this->firebase->updatePublicTopic($topicId, $param);
            MiningTopic::dispatch($topicId);
        }
    }
}
