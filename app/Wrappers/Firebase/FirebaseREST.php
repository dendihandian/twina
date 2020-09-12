<?php

namespace App\Wrappers\Firebase;

use Illuminate\Support\Facades\Http;

class FirebaseREST
{
    protected $config;

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

    public function get($path)
    {
        $response = Http::get($this->urlBuilder($path));
        return $response->json();
    }

    public function post($path, $param = [])
    {
        $response = Http::post($this->urlBuilder($path), $param);
        return $response->json();
    }

    public function patch($path, $param = [])
    {
        $response = Http::patch($this->urlBuilder($path), $param);
        return $response->json();
    }

    public function put($path, $param = [])
    {
        $response = Http::put($this->urlBuilder($path), $param);
        return $response->json();
    }

    public function delete($path)
    {
        $response = Http::delete($this->urlBuilder($path));
        return $response->json();
    }
}
