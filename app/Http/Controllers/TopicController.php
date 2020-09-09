<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TopicRequest;
use App\Repositories\TopicRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// use Illuminate\Database\Eloquent\Collection;

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

    public function index()
    {
        $topics = $this->topicRepository->getTopics(Auth::user()->id);
        return view('topics.index', compact('topics'));
    }

    public function create()
    {
        return view('topics.create');
    }

    public function store(TopicRequest $request)
    {
        try {
            $this->topicRepository->createTopic($request->only('name'), Auth::user()->id);
            return redirect()->route('topics.index');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function mining(Request $request, $topicId)
    {
        try {
            // DB::beginTransaction();
            $topic = $this->topicRepository->getTopic($topicId, Auth::user()->id);
            if ($topic && !$topic['on_queue']) {
                $this->topicRepository->startMining($topicId, Auth::user()->id);
            }

            // DB::commit();

            return redirect()->back();
        } catch (\Throwable $th) {
            // DB::rollBack();
            dd($th);
        }
    }
}
