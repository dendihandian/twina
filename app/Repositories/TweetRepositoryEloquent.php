<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TweetRepository;
use App\Entities\Tweet;
use App\Wrappers\Twitter\Twitter;

/**
 * Class TweetRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TweetRepositoryEloquent extends BaseRepository implements TweetRepository
{
    protected $twitter;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Tweet::class;
    }

    public function __construct(Twitter $twitter)
    {
        $this->twitter = $twitter;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function searchTweets($param)
    {
        return $this->twitter->searchTweets($param);
    }
}
