<?php

namespace App\Wrappers\Firebase;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Wrappers\Firebase\Traits\Topic;
use App\Wrappers\Firebase\Traits\PublicTopic;
use App\Wrappers\Firebase\Traits\PublicTopicGraph;
use App\Wrappers\Firebase\Traits\UserTopic;
use App\Wrappers\Firebase\Traits\UserTopicGraph;

class Firebase
{
    use Topic, PublicTopic, UserTopic, PublicTopicGraph, UserTopicGraph;

    protected $config;
    protected $http;

    public function __construct()
    {
        $this->config = config('firebase');
    }

    protected function urlBuilder($path = '', $token = null)
    {
        $url = $this->config['database'] . $path . '.json?key=' . $this->config['api_key'];

        if ($token) {
            $url += '&auth=' . $token;
        }

        return $url;
    }
}
