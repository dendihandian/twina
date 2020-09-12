<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopicRequest;
use Illuminate\Http\Request;
use App\Repositories\TopicRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TopicController extends Controller
{
    /**
     * @var TopicRepository
     */
    protected $topicRepository;

    public function __construct(TopicRepository $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    public function index(Request $request)
    {
        $isPub = $request->has('isPub');
        $topics = $this->topicRepository->getTopics($isPub ? null : Auth::user()->id);
        return view('topics.index', compact('isPub', 'topics'));
    }

    public function create(Request $request)
    {
        $isPub = $request->has('isPub');
        $resultTypes = [
            [
                'label' => 'Recent',
                'value' => 'recent',
            ],
            [
                'label' => 'Popular',
                'value' => 'popular',
            ],
            [
                'label' => 'Mixed',
                'value' => 'mixed',
            ],
        ];
        return view('topics.create', compact('isPub', 'resultTypes'));
    }

    public function store(TopicRequest $request)
    {
        $isPub = $request->has('isPub');
        try {
            $this->topicRepository->createTopic(
                $request->only(['name', 'result_type']),
                $isPub ? null : Auth::user()->id
            );
            $request->session()->flash('success', 'Topic created');
            return redirect()->route(($isPub ? 'public.' : '') . 'topics.index');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function delete(Request $request, $topicId)
    {
        $isPub = $request->has('isPub');
        try {
            $this->topicRepository->deleteTopic(
                $topicId,
                $isPub ? null : Auth::user()->id
            );
            $request->session()->flash('success', 'Topic was deleted');
            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function mining(Request $request, $topicId)
    {
        $isPub = $request->has('isPub');
        try {
            $topic = $this->topicRepository->getTopic(
                $topicId,
                $isPub ? null : Auth::user()->id
            );
            if ($topic && !$topic['on_queue']) {
                $this->topicRepository->startMining(
                    $topicId,
                    $isPub ? null : Auth::user()->id
                );
            }
            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function getAnalysis(Request $request, $topicId)
    {
        $isPub = $request->has('isPub');
        $topic = $this->topicRepository->getTopic(
            $topicId,
            $isPub ? null : Auth::user()->id
        );
        $graph = $topic['graph'] ?? null;

        if ($graph) {
            $graph = [
                'nodes' => array_values($graph['nodes']),
                'links' => array_values($graph['edges']),
            ];
        }

        return view('topics.graph.index', compact('isPub', 'topic', 'topicId', 'graph'));
    }

    public function postAnalysis(Request $request, $topicId)
    {
        $isPub = $request->has('isPub');
        $this->topicRepository->startAnalyzing(
            $topicId,
            $isPub ? null : Auth::user()->id
        );
        return redirect()->route(($isPub ? 'public.' : '') . 'topics.analysis.index', ['topic' => $topicId]);
    }

    public function postComplementGraph(Request $request, $topicId)
    {
        $isPub = $request->has('isPub');
        $this->topicRepository->startComplementingGraph(
            $topicId,
            $isPub ? null : Auth::user()->id
        );
        return redirect()->route(($isPub ? 'public.' : '') . 'topics.analysis.index', ['topic' => $topicId]);
    }

    public function setSelectedTopic(Request $request, $topicId)
    {
        $isPub = $request->has('isPub');
        try {
            $this->topicRepository->setSelectedTopic(
                $topicId,
                $isPub ? null : Auth::user()->id
            );
            $request->session()->flash('success', 'The topic was selected successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            Log::error($th);
            $request->session()->flash('error', $this->errorMessage);
            return redirect()->back();
        }
    }
}
