<?php

namespace App\Jobs;

use App\Repositories\TopicRepository;
use App\Repositories\TopicTweetRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class AnalyzeTweets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $topicId;
    protected $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($topicId, $userId = null)
    {
        $this->topicId = $topicId;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TopicRepository $topicRepository, TopicTweetRepository $topicTweetRepository)
    {
        Log::info('AnalyzeTweets@handle start');

        Log::debug([
            'topicId' => $this->topicId,
            'userId' => $this->userId,
        ]);

        // default values
        $tweetsDateRange = ['min' => null, 'max' => null];
        $langsCount = [];

        try {

            $tweets = $topicTweetRepository->getTopicTweets($this->topicId, $this->userId);

            if (is_array($tweets) && count($tweets)) {
                foreach ($tweets as $tweet) {
                    // date range analysis
                    if (isset($tweet['created_at']) && !empty($tweet['created_at'])) {
                        $tweetCreatedAt = Carbon::parse($tweet['created_at'])->toDateTimeString();
                        if (!($tweetsDateRange['min']) || $tweetsDateRange['min'] >= $tweetCreatedAt) {
                            $tweetsDateRange['min'] = $tweetCreatedAt;
                        }

                        if (!($tweetsDateRange['max']) || $tweetsDateRange['max'] <= $tweetCreatedAt) {
                            $tweetsDateRange['max'] = $tweetCreatedAt;
                        }
                    }

                    // langs count analysis
                    if (isset($tweet['lang']) && !empty($tweet['lang'])) {
                        if (isset($langsCount[$tweet['lang']])) {
                            $langsCount[$tweet['lang']] += 1;
                        } else {
                            $langsCount[$tweet['lang']] = 1;
                        }
                    }
                }
            }

            $tweetsAnalysis = [
                'tweets_count' => count($tweets),
                'tweets_date_range' => $tweetsDateRange,
                'langs_count' => $langsCount,
            ];

            Log::debug($tweetsAnalysis);

            $topicRepository->updateTopic($this->topicId, [
                'on_analyze_tweets' => false,
                'tweets_analysis' => $tweetsAnalysis,
            ], $this->userId);
        } catch (\Throwable $th) {
            // dd($th);
            Log::error($th);
            $topicRepository->updateTopic($this->topicId, [
                'on_analyze_tweets' => false
            ], $this->userId);
        }

        $topicRepository->updateTopic($this->topicId, [
            'on_analyze_tweets' => false
        ], $this->userId);

        Log::info('AnalyzeTweets@handle end');
    }
}
