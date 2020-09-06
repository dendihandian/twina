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

    public function getTopics($user)
    {
        return $this->firebase->getTopics($user->id);
    }

    public function getTopic($user, $topicId)
    {
        return $this->firebase->getTopic($user->id, $topicId);
    }

    public function createTopic($user, $param)
    {
        $this->firebase->addTopic($user->id, [
            'text' => $param['name'],
        ]);
    }

    public function startMining($user, $topicId)
    {
        $this->firebase->updateTopic($user->id, $topicId, [
            'on_queue' => true
        ]);

        $topic = $this->getTopic($user, $topicId);
        MiningTopic::dispatch($userId, $topic);
    }
}
