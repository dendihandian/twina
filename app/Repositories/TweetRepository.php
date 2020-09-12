<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface TweetRepository.
 *
 * @package namespace App\Repositories;
 */
interface TweetRepository extends RepositoryInterface
{
    public function searchTweets($param);
}
