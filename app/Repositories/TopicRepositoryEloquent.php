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
use App\Wrappers\CacheExtended as Cache;

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
        $cachePath = $this->buildCachePath('topics', [], $userId);

        if (Cache::has($cachePath)) {
            $topics = Cache::get($cachePath);
        } else {
            $topics = $this->topicEntity->getTopics($userId) ?? [];
            Cache::put($cachePath, $topics);
        }

        return $topics;
    }

    public function getTopic($topicId, $userId = null)
    {
        $cachePath = $this->buildCachePath('topic', compact('topicId'), $userId);

        if (Cache::has($cachePath)) {
            $topic = Cache::get($cachePath);
        } else {
            $topic = $this->topicEntity->getTopic($topicId, $userId) ?? null;
            Cache::put($cachePath, $topic);
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

        $result = $this->topicEntity->addTopic($param, $userId);

        $this->clearCaches(null, $userId);

        return $result;
    }

    public function updateTopic($topicId, $param, $userId = null)
    {
        $result = $this->topicEntity->updateTopic($topicId, $param, $userId);

        $this->clearCaches($topicId, $userId);

        return $result;
    }

    public function deleteTopic($topicId, $userId = null)
    {
        $result = $this->topicEntity->deleteTopic($topicId, $userId);

        $this->clearCaches($topicId, $userId);

        return $result;
    }

    public function startMining($topicId, $userId = null)
    {
        $param = ['on_mining' => true];
        $this->updateTopic($topicId, $param, $userId);
        MiningTweets::dispatch($topicId, $userId);
    }

    public function clearCaches($topicId = null, $userId = null)
    {
        if (Cache::has($cachePath = $this->buildCachePath('topics', [], $userId))) Cache::forget($cachePath);
        if ($topicId && Cache::has($cachePath = $this->buildCachePath('topic', compact('topicId'), $userId))) Cache::forget($cachePath);
    }
}
