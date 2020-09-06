<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wrappers\Twitter;
use App\Wrappers\Firebase;

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
        // $array = json_decode(json_encode($statuses->statuses), true);
        dd(json_decode(json_encode($statuses->statuses), true));
        $account = $this->twitter->getAccount();
        return view('home', ['account' => $account]);
    }
}
