<?php

namespace App\Entities\Traits;

use App\Wrappers\Firebase\FirebaseREST;

/**
 * 
 */
trait RESTClientTrait
{
    public static function getRESTClientInstance()
    {
        return new FirebaseREST;
    }
}
