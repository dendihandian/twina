<?php

namespace App\Wrappers\Firebase\Traits;

use Illuminate\Support\Facades\Http;

/**
 * 
 */
trait TopicGraph
{
    public function updateTopicGraph($topicId, $param, $userId = null)
    {
        if ($userId) {
            $response = Http::patch($this->urlBuilder("/topics/users/{$userId}/{$topicId}/graph"), $param);
        } else {
            $response = Http::patch($this->urlBuilder("/topics/public/{$topicId}/graph"), $param);
        }

        return $response->json();
    }
}
