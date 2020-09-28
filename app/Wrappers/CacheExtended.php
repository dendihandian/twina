<?php

namespace App\Wrappers;

use Illuminate\Support\Facades\Cache;

class CacheExtended
{
    public static function has($key)
    {
        if (env('CACHE_ENABLED', false)) {
            return Cache::has($key);
        } else {
            return null;
        }
    }

    public static function get($key)
    {
        if (env('CACHE_ENABLED', false)) {
            return Cache::get($key);
        } else {
            return null;
        }
    }

    public static function put($key, $value)
    {
        if (env('CACHE_ENABLED', false)) {
            Cache::put($key, $value);
        }
    }
}
