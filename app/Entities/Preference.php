<?php

namespace App\Entities;

use App\Entities\Traits\RESTClientTrait;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Preference.
 *
 * @package namespace App\Entities;
 */
class Preference extends BaseEntity implements Transformable
{
    use TransformableTrait, RESTClientTrait;

    protected function pathBuilder($userId = null)
    {
        if ($userId) {
            $path = "/users/{$userId}/preference";
        } else {
            $path = "/public/preference";
        }

        return $path;
    }

    public function getPreference($userId = null)
    {
        $restClient = self::getRESTClientInstance();
        $path = $this->pathBuilder($userId);
        $response = $restClient->get($path);
        return $response;
    }

    public function updatePreference($param, $userId = null)
    {
        $restClient = self::getRESTClientInstance();
        $path = $this->pathBuilder($userId);
        $response = $restClient->patch($path, $param);
        return $response;
    }
}
