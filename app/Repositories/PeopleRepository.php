<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface PeopleRepository.
 *
 * @package namespace App\Repositories;
 */
interface PeopleRepository extends RepositoryInterface
{
    public function getPeopleByUserId($userId);
    public function getPeopleByScreenName($screenName);
    public function getPeoplesByUserIds(array $userIds);
    public function getPeoplesByScreenNames(array $screenNames);
}
