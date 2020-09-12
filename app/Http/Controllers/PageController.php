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
        $topicId = $this->preferenceRepository->getSelectedTopic();
        $topic = $topicId ? $this->topicRepository->getTopic($topicId) : null;
        $graph = $topic['graph'] ?? null;

        return view('pages.landing', compact('topic', 'graph'));
    }
}
