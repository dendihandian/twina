<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wrappers\Twitter;

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
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $account = $this->twitter->getAccount();
        return view('home', ['account' => $account]);
    }
}
