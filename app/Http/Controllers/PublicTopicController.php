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
        return view('public.topics.create');
    }

    public function store(TopicRequest $request)
    {
        try {
            $this->topicRepository->createTopic($request->only('name'));
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
}
