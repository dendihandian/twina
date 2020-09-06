<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TopicRepository;
use App\Entities\Topic;
use App\Validators\TopicValidator;
use App\Jobs\MiningTopic;
use App\Wrappers\Firebase;

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

    public function getTopics($userId)
    {
        return $this->firebase->getTopics($userId);
    }

    public function getTopic($userId, $topicId)
    {
        return $this->firebase->getTopic($userId, $topicId);
    }

    public function createTopic($userId, $param)
    {
        $this->firebase->addTopic($userId, [
            'text' => $param['name'],
        ]);
    }

    public function updateTopic($userId, $topicId, $param)
    {
        $this->firebase->updateTopic($userId, $topicId, $param);
    }

    public function startMining($userId, $topicId)
    {
        $this->firebase->updateTopic($userId, $topicId, [
            'on_queue' => true
        ]);

        MiningTopic::dispatch($userId, $topicId);
    }
}
