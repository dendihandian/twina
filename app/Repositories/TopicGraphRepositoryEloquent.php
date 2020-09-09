<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TopicGraphRepository;
use App\Entities\TopicGraph;
use App\Validators\TopicGraphValidator;
use App\Wrappers\Firebase\Firebase;

/**
 * Class TopicGraphRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TopicGraphRepositoryEloquent extends BaseRepository implements TopicGraphRepository
{
    protected $firebase;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TopicGraph::class;
    }

    public function __construct(Firebase $firebase)
    {
        $this->firebase = $firebase;
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
        if ($userId) {
            $this->firebase->updateUserTopicGraph($userId, $topicId, $param);
        } else {
            $this->firebase->updatePublicTopicGraph($topicId, $param);
        }
    }
}
