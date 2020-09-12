<?php

namespace App\Http\Controllers;

use App\Repositories\PreferenceRepository;
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
    protected $preferenceRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        TopicRepository $topicRepository,
        TopicGraphRepository $topicGraphRepository,
        PreferenceRepository $preferenceRepository
    ) {
        $this->topicRepository = $topicRepository;
        $this->topicGraphRepository = $topicGraphRepository;
        $this->preferenceRepository = $preferenceRepository;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $topicId = $this->preferenceRepository->getSelectedTopic(Auth::user()->id);
        $topic = $this->topicRepository->getTopic($topicId, Auth::user()->id);
        $graph = $topic['graph'] ?? [];

        if (!$graph) {
            $graph = $this->topicGraphRepository->getTopicGraph($topicId, Auth::user()->id);
        }

        return view('home', compact('topic', 'graph'));
    }
}
