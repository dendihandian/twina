<?php

namespace App\Wrappers\Firebase\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

/**
 * 
 */
trait PublicTopicGraph
{
    public function updatePublicTopicGraph($topicId, $param)
    {
        $response = Http::patch($this->urlBuilder("/topics/public/{$topicId}/graph"), $param);
        return $response->json();
    }
}
