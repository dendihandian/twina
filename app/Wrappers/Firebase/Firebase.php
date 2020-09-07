<?php

namespace App\Wrappers\Firebase;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Wrappers\Firebase\Traits\PublicTopic;
use App\Wrappers\Firebase\Traits\UserTopic;

class Firebase
{
    use PublicTopic, UserTopic;

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
