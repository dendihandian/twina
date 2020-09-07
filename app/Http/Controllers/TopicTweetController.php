<?php

namespace App\Http\Controllers;

use App\Repositories\TopicRepository;
use App\Repositories\TopicTweetRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicTweetController extends Controller
{
    protected $topicRepository;
    protected $topicTweetRepository;

    public function __construct(TopicRepository $topicRepository, TopicTweetRepository $topicTweetRepository)
    {
        $this->topicRepository = $topicRepository;
        $this->topicTweetRepository = $topicTweetRepository;
    }

    public function index($topicId)
    {
        $topic = $this->topicRepository->getTopic(Auth::user()->id, $topicId);
        $tweets = $topic['tweets'] ?? [];
        return view('topics.tweets.index', compact('topic', 'tweets'));
    }
}
