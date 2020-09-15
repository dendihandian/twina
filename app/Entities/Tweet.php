<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Tweet.
 *
 * @package namespace App\Entities;
 */
class Tweet extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    const EXCEPTIONAL_WORDS = [
        'rt',
    ];

    const EXPLICIT_WORDS = [];
}
