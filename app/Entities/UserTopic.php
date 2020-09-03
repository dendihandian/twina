<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class UserTopic.
 *
 * @package namespace App\Entities;
 */
class UserTopic extends Pivot
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
