<?php

namespace App\Http\Controllers;

use App\Repositories\TopicRepository;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $topicRepository;

    public function __construct(TopicRepository $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    public function landingPage()
    {
        $topic = $this->topicRepository->getSelectedTopic();
        $graph = $topic['graph'] ?? null;

        return view('welcome', compact('topic', 'graph'));
    }
}
