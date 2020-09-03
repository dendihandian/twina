<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class TopicTweet.
 *
 * @package namespace App\Entities;
 */
class TopicTweet extends Pivot
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $table = 'topic_tweets';
}
