<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wrappers\Twitter;
use App\Wrappers\Firebase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->twitter = new Twitter;
        $this->firebase = new Firebase;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // dd($this->firebase->getPublicTopics());
        $statuses = $this->twitter->searchTweets(['q' => 'laravel']);
        $statuses = json_decode(json_encode(array_reverse($statuses->statuses)), true);
        $statuses = Collection::make($statuses)->keyBy('id')->toArray();
        Log::debug($statuses);
        dd($statuses);
        // $array = json_decode(json_encode($statuses->statuses), true);
        dd(json_decode(json_encode($statuses->statuses), true));
        $account = $this->twitter->getAccount();
        return view('home', ['account' => $account]);
    }
}
