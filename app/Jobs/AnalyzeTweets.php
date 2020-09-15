<?php

namespace App\Jobs;

use App\Entities\Tweet;
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
        $mostWords = [];

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

                    // most words analysis
                    $words = explode(' ', filter_var($tweet['text'], FILTER_SANITIZE_STRING));
                    foreach ($words as $word) {
                        $word = strtolower($word);
                        if (!empty($word) && (strlen($word) > 1) && !in_array($word, Tweet::EXCEPTIONAL_WORDS)) {
                            if (isset($mostWords[$word])) {
                                $mostWords[$word] += 1;
                            } else {
                                $mostWords[$word] = 1;
                            }
                        }
                    }
                }
            }

            if (count($mostWords)) {
                arsort($mostWords);
                $mostWords = array_slice($mostWords, 0, 10);

                // NOTE: an '#' prefix cannot be added to firebase, so wrap it on quote...
                // NOTE: it's complicated with firebase, so the text should be a value not a key...
                $mostWords = Collection::make($mostWords)->map(function ($count, $word) {
                    return [
                        'text' => $word,
                        'count' => $count
                    ];
                })->values()->toArray();
            }

            $tweetsAnalysis = [
                'tweets_count' => count($tweets),
                'tweets_date_range' => $tweetsDateRange,
                'langs_count' => $langsCount,
                'most_words' => $mostWords,
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
