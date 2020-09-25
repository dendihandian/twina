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
use App\Repositories\Traits\RepositoryCacheTrait;
use App\Wrappers\Firebase\Firebase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * Class TopicRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TopicRepositoryEloquent extends BaseRepository implements TopicRepository
{
    use RepositoryCacheTrait;

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

    public function getTopics($userId = null)
    {
        $topics = $this->topicEntity->getTopics($userId) ?? [];
        return $topics;
    }

    public function getTopic($topicId, $userId = null)
    {
        $cachePath = $this->buildCachePath('topic', compact('topicId'), $userId);

        if (Cache::has($cachePath)) {
            $topic = Cache::get($cachePath);
        } else {
            $topic = $this->topicEntity->getTopic($topicId, $userId) ?? null;
            Cache::add($cachePath, $topic);
        }
        return $topic;
    }

    public function createTopic($param, $userId = null)
    {
        $param = [
            'text' => $param['name'],
            'result_type' => $param['result_type'],
            'created_at' => Carbon::now()->toDateTimeString(),
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
}
