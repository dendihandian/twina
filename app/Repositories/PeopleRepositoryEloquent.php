<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PeopleRepository;
use App\Entities\People;
use App\Validators\PeopleValidator;
use App\Wrappers\Twitter;

/**
 * Class PeopleRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PeopleRepositoryEloquent extends BaseRepository implements PeopleRepository
{
    protected $twitter;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return People::class;
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

    public function getPeopleByUserId($userId)
    {
        return $this->twitter->getUserByUserId($userId);
    }

    public function getPeopleByScreenName($screenName)
    {
        return $this->twitter->getUserByScreenName($screenName);
    }

    public function getPeoplesByUserIds(array $userIds)
    {
        return $this->twitter->getUsersByUserIds($userIds);
    }

    public function getPeoplesByScreenNames(array $screenNames)
    {
        return $this->twitter->getUsersByScreenNames($screenNames);
    }
}
