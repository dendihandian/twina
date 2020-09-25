<?php

namespace App\Repositories\Traits;

/**
 * Trait for Cache Methods in RepositoryEloquent
 */
trait RepositoryCacheTrait
{
    protected function buildCachePath($path, $identifiers = [], $userId = null)
    {
        $pathString = config('twina.cache_paths')[$path];

        foreach ($identifiers as $key => $value) {
            if (!empty($value)) {
                $pathString = str_replace('[' . $key . ']', $value, $pathString);
            }
        }

        $ownerPrefix = ($userId) ? "users.{$userId}." : "public.";

        return $ownerPrefix . $pathString;
    }
}
