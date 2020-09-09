<?php

namespace App\Wrappers\Firebase\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

/**
 * 
 */
trait UserTopicGraph
{
    public function updateUserTopicGraph($userId, $topicId, $param)
    {
        $response = Http::patch($this->urlBuilder("/topics/users/{$userId}/{$topicId}/graph"), $param);
        return $response->json();
    }
}
