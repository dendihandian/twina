<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TweetRepository;
use App\Entities\Tweet;
use App\Validators\TweetValidator;

/**
 * Class TweetRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TweetRepositoryEloquent extends BaseRepository implements TweetRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Tweet::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
