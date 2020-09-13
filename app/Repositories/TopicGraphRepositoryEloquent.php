<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TopicGraphRepository;
use App\Entities\TopicGraph;
use App\Validators\TopicGraphValidator;
use App\Wrappers\Firebase\Firebase;
use App\Jobs\NormalizeGraph;
use App\Jobs\AnalyzeGraph;
use App\Jobs\GenerateGraph;

/**
 * Class TopicGraphRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TopicGraphRepositoryEloquent extends BaseRepository implements TopicGraphRepository
{
    protected $firebase;
    protected $topicGraphEntity;
    protected $topicRepository;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TopicGraph::class;
    }

    public function __construct(Firebase $firebase, TopicRepository $topicRepository)
    {
        $this->firebase = $firebase;
        $this->topicRepository = $topicRepository;
        $this->topicGraphEntity = new TopicGraph;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function updateTopicGraph($topicId, $param, $userId = null)
    {
        return $this->topicGraphEntity->updateTopicGraph($topicId, $param, $userId);
    }

    public function getTopicGraph($topicId, $userId = null)
    {
        return $this->topicGraphEntity->getTopicGraph($topicId, $userId);
    }

    public function generateGraph($topicId, $userId = null)
    {
        $this->topicRepository->updateTopic($topicId, [
            'on_generate' => true
        ], $userId);

        GenerateGraph::dispatch($topicId, $userId);
    }

    public function normalizeGraph($topicId, $userId = null)
    {
        $this->topicRepository->updateTopic($topicId, [
            'on_normalize' => true
        ], $userId);

        NormalizeGraph::dispatch($topicId, $userId);
    }

    public function analyzeGraph($topicId, $userId = null)
    {
        $this->topicRepository->updateTopic($topicId, [
            'on_analyze' => true
        ], $userId);

        AnalyzeGraph::dispatch($topicId, $userId);
    }
}
