<?php

namespace App\Wrappers\Firebase\Traits;

use Illuminate\Support\Facades\Http;

/**
 * 
 */
trait Topic
{
    public function deleteTopic($topicId, $userId = null)
    {
        if ($userId) {
            $response = Http::delete($this->urlBuilder("/topics/users/{$userId}/{$topicId}"));
        } else {
            $response = Http::delete($this->urlBuilder("/topics/public/{$topicId}"));
        }

        return $response->json();
    }
}
