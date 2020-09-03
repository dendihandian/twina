<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

/**
 * Class Topic.
 *
 * @package namespace App\Entities;
 */
class Topic extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'last_mining' => 'datetime:Y-m-d h:i:s'
    ];

    public static function boot()
    {
        parent::boot();

        // ref: https://stackoverflow.com/questions/38685019/laravel-how-to-create-a-function-after-or-before-saveupdate

        self::creating(function ($topic) {
            $topic->name = Str::lower($topic->name);
            $topic->slug = Str::slug($topic->name);
        });

        self::created(function ($topic) {
            UserTopic::firstOrCreate([
                'user_id' => Auth::id(),
                'topic_id' => $topic->id,
            ]);
        });
    }
}
