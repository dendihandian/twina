<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TopicRequest;
use App\Repositories\TopicRepository;
use Illuminate\Support\Facades\Auth;

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
        $topics = Auth::user()->topics;
        return view('topics.index', compact('topics'));
    }

    public function create()
    {
        return view('topics.create');
    }

    public function store(TopicRequest $request)
    {
        $this->repository->create($request->only('name'));
        return redirect()->route('topics.index');
    }
}
