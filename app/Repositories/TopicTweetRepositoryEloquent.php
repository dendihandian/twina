<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TopicTweetRepository;
use App\Entities\TopicTweet;
use App\Validators\TopicTweetValidator;

/**
 * Class TopicTweetRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TopicTweetRepositoryEloquent extends BaseRepository implements TopicTweetRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TopicTweet::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
