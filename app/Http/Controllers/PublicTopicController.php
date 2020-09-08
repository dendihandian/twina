<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopicRequest;
use Illuminate\Http\Request;
use App\Repositories\TopicRepository;

class PublicTopicController extends Controller
{
    /**
     * @var TopicRepository
     */
    protected $topicRepository;

    public function __construct(TopicRepository $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    public function index()
    {
        $topics = $this->topicRepository->getTopics();
        return view('public.topics.index', compact('topics'));
    }

    public function create()
    {
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
        return view('public.topics.create', compact('resultTypes'));
    }

    public function store(TopicRequest $request)
    {
        try {
            $this->topicRepository->createTopic($request->only(['name', 'result_type']));
            return redirect()->route('public.topics.index');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function mining(Request $request, $topicId)
    {
        try {
            $topic = $this->topicRepository->getTopic($topicId);
            if ($topic && !$topic['on_queue']) {
                $this->topicRepository->startMining($topicId);
            }
            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function getAnalysis($topicId)
    {
        $topic = $this->topicRepository->getTopic($topicId);
        $graph = $topic['graph'] ?? null;

        if ($graph) {
            $graph = [
                'nodes' => array_values($graph['nodes']),
                'links' => array_values($graph['edges']),
            ];
        }


        return view('public.topics.analysis', compact('topic', 'topicId', 'graph'));
    }

    public function postAnalysis($topicId)
    {
        $this->topicRepository->startAnalyzing($topicId);
        return redirect()->route('public.topics.analysis.index', ['topic' => $topicId]);
    }
}
