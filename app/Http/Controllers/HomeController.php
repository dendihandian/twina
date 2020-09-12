<?php

namespace App\Http\Controllers;

use App\Repositories\TopicGraphRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Repositories\TopicRepository;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $topicRepository;
    protected $topicGraphRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        TopicRepository $topicRepository,
        TopicGraphRepository $topicGraphRepository
    ) {
        $this->topicRepository = $topicRepository;
        $this->topicGraphRepository = $topicGraphRepository;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $topic = $this->topicRepository->getSelectedTopic(Auth::user()->id);
        // dd($topic);
        $graph = $topic['graph'] ?? [];

        // if (!empty($topic) && !$graph) {
        //     $graph = $this->topicGraphRepository->getTopicGraph(
        //         $topic['id'],
        //         Auth::user()->id
        //     );
        // }

        return view('home', compact('topic', 'graph'));
    }
}
