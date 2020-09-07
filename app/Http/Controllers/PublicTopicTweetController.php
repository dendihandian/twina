<?php

namespace App\Http\Controllers;

use App\Repositories\TopicRepository;
use App\Repositories\TopicTweetRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicTopicTweetController extends Controller
{
    protected $topicRepository;

    public function __construct(TopicRepository $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    public function index($topicId)
    {
        $topic = $this->topicRepository->getTopic($topicId);
        $tweets = $topic['tweets'] ?? [];
        return view('topics.tweets.index', compact('topic', 'tweets'));
    }
}
