<?php

namespace App\Http\Controllers;

use App\Repositories\PreferenceRepository;
use App\Repositories\TopicRepository;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $topicRepository;
    protected $preferenceRepository;

    public function __construct(TopicRepository $topicRepository, PreferenceRepository $preferenceRepository)
    {
        $this->topicRepository = $topicRepository;
        $this->preferenceRepository = $preferenceRepository;
    }

    public function landingPage()
    {
        return view('pages.landing');
    }

    public function highlightedTopic()
    {
        $topicId = $this->preferenceRepository->getSelectedTopic();
        $topic = $topicId ? $this->topicRepository->getTopic($topicId) : null;
        $tweetsAnalysis = $topic['tweets_analysis'] ?? [];
        $graph = $topic['graph'] ?? null;

        // dd($topic);

        return view('pages.highlighted', compact('topic', 'graph', 'tweetsAnalysis'));
    }
}
