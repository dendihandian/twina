<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopicRequest;
use App\Repositories\PreferenceRepository;
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
    protected $preferenceRepository;

    public function __construct(TopicRepository $topicRepository, PreferenceRepository $preferenceRepository)
    {
        $this->topicRepository = $topicRepository;
        $this->preferenceRepository = $preferenceRepository;
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
            $this->topicRepository->startMining(
                $topicId,
                $isPub ? null : Auth::user()->id
            );
            $request->session()->flash('success', 'Mining tweets successfuly');
            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function setSelected(Request $request, $topicId)
    {
        $isPub = $request->has('isPub');
        try {
            $this->preferenceRepository->setSelectedTopic(
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
