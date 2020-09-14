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

        $tweetsAnalysis = $topic['tweets_analysis'] ?? [];

        return view('topics.tweets.index', compact('isPub', 'topicId', 'topic', 'tweets', 'tweetsAnalysis'));
    }

    public function mine(Request $request, $topicId)
    {
        $isPub = $request->has('isPub');
        $this->topicTweetRepository->mineTweets($topicId, $isPub ? null : Auth::user()->id);
        $request->session()->flash('info', __('The tweets are being mined. Please reload the page after few seconds.'));
        // return redirect()->intended(route(($isPub ? 'public.' : '') . 'topics.tweets.index', ['topic' => $topicId]));
        return redirect()->back();
    }

    public function analyze(Request $request, $topicId)
    {
        $isPub = $request->has('isPub');
        $this->topicTweetRepository->analyzeTweets($topicId, $isPub ? null : Auth::user()->id);
        $request->session()->flash('info', __('The tweets are being analyzed. Please reload the page after few seconds.'));
        // return redirect()->intended(route(($isPub ? 'public.' : '') . 'topics.tweets.index', ['topic' => $topicId]));
        return redirect()->back();
    }
}
