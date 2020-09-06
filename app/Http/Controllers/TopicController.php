<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TopicRequest;
use App\Repositories\TopicRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Eloquent\Collection;

class TopicController extends Controller
{
    /**
     * @var TopicRepository
     */
    protected $repository;

    public function __construct(TopicRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $topics = $this->repository->getTopics(Auth::user()->id);
        return view('topics.index', compact('topics'));
    }

    public function create()
    {
        return view('topics.create');
    }

    public function store(TopicRequest $request)
    {
        try {
            $this->repository->createTopic(Auth::user()->id, $request->only('name'));
            return redirect()->route('topics.index');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function mining(Request $request, $topicId)
    {
        try {
            // DB::beginTransaction();
            $topic = $this->repository->getTopic(Auth::user()->id, $topicId);
            if ($topic && !$topic['on_queue']) {
                $this->repository->startMining(Auth::user()->id, $topicId);
            }

            // DB::commit();

            return redirect()->back();
        } catch (\Throwable $th) {
            // DB::rollBack();
            dd($th);
        }
    }
}
