<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Repositories\TopicRepository;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $topicRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TopicRepository $topicRepository)
    {
        $this->topicRepository = $topicRepository;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $topic = $this->topicRepository->getSelectedTopic(Auth::user()->id);
        $graph = $topic['graph'] ?? [];
        if ($graph) {
            $graph = [
                'nodes' => array_values($graph['nodes']),
                'links' => array_values($graph['edges']),
            ];
        }
        return view('home', compact('topic', 'graph'));
    }
}
