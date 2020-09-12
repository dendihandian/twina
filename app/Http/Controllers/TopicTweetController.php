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

    public function index(Request $request, $topicId)
    {
        $isPub = $request->has('isPub');

        $topic = $this->topicRepository->getTopic(
            $topicId,
            $isPub ? null : Auth::user()->id
        );

        $tweets = $topic['tweets'] ?? [];

        if (!$tweets) {
            $tweets = $this->topicTweetRepository->getTopicTweets(
                $topicId,
                $isPub ? null : Auth::user()->id
            );
        }

        return view('topics.tweets.index', compact('topic', 'tweets'));
    }
}
