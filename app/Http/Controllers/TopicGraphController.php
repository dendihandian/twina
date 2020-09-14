<?php

namespace App\Http\Controllers;

use App\Repositories\TopicGraphRepository;
use App\Repositories\TopicRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TopicGraphController extends Controller
{
    protected $topicRepository;
    protected $topicGraphRepository;

    public function __construct(
        TopicRepository $topicRepository,
        TopicGraphRepository $topicGraphRepository
    ) {
        $this->topicRepository = $topicRepository;
        $this->topicGraphRepository = $topicGraphRepository;
    }

    public function index(Request $request, $topicId)
    {
        $isPub = $request->has('isPub');
        $topic = $this->topicRepository->getTopic(
            $topicId,
            $isPub ? null : Auth::user()->id
        );

        if (!($graph = $topic['graph'] ?? null)) {
            Log::info("Topic {$topicId} graph was null/empty");
            $graph = $this->topicGraphRepository->getTopicGraph(
                $topicId,
                $isPub ? null : Auth::user()->id
            );
        }

        $tweetsAnalysis = $topic['tweets_analysis'] ?? false;
        $graphAnalysis = $topic['graph']['analysis'] ?? false;

        return view('topics.graph.index', compact('isPub', 'topicId', 'topic', 'graph', 'tweetsAnalysis', 'graphAnalysis'));
    }

    public function generate(Request $request, $topicId)
    {
        $isPub = $request->has('isPub');
        $this->topicGraphRepository->generateGraph(
            $topicId,
            $isPub ? null : Auth::user()->id
        );

        $request->session()->flash('success', 'Graph on generating...');
        return redirect()->back();
    }

    public function normalize(Request $request, $topicId)
    {
        $isPub = $request->has('isPub');
        $this->topicGraphRepository->normalizeGraph(
            $topicId,
            $isPub ? null : Auth::user()->id
        );

        $request->session()->flash('success', 'Graph on normalizing...');
        return redirect()->back();
    }

    public function analyze(Request $request, $topicId)
    {
        $isPub = $request->has('isPub');
        $this->topicGraphRepository->analyzeGraph(
            $topicId,
            $isPub ? null : Auth::user()->id
        );

        $request->session()->flash('success', 'Graph on analyzing...');
        return redirect()->back();
    }
}
