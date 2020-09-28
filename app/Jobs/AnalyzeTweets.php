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
        $mostMentions = [];
        $mostHashtags = [];
        $mostReplies = [];

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

                    // most mentioned analysis
                    if ($tweet['entities']['user_mentions'] ?? false) {
                        foreach ($tweet['entities']['user_mentions'] as $user) {
                            if (isset($mostMentions[$user['screen_name']])) {
                                $mostMentions[$user['screen_name']] += 1;
                            } else {
                                $mostMentions[$user['screen_name']] = 1;
                            }
                        }
                    }

                    // most hashtags analysis
                    if ($tweet['entities']['hashtags'] ?? false) {
                        foreach ($tweet['entities']['hashtags'] as $hashtag) {
                            if (isset($mostHashtags[$hashtag['text']])) {
                                $mostHashtags[$hashtag['text']] += 1;
                            } else {
                                $mostHashtags[$hashtag['text']] = 1;
                            }
                        }
                    }

                    // most replies analysis
                    if ($tweet['in_reply_to_screen_name'] ?? false) {
                        if (isset($mostReplies[$tweet['in_reply_to_screen_name']])) {
                            $mostReplies[$tweet['in_reply_to_screen_name']] += 1;
                        } else {
                            $mostReplies[$tweet['in_reply_to_screen_name']] = 1;
                        }
                    }

                    if ($tweet['location'] ?? false) {
                        Log::debug('location: ');
                        Log::debug($tweet['location']);
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

            if (count($mostMentions)) {
                arsort($mostMentions);
                $mostMentions = array_slice($mostMentions, 0, 10);
                $mostMentions = Collection::make($mostMentions)->map(function ($count, $screenName) {
                    return [
                        'text' => $screenName,
                        'count' => $count
                    ];
                })->values()->toArray();
            }

            if (count($mostHashtags)) {
                arsort($mostHashtags);
                $mostHashtags = array_slice($mostHashtags, 0, 10);
                $mostHashtags = Collection::make($mostHashtags)->map(function ($count, $hashtag) {
                    return [
                        'text' => $hashtag,
                        'count' => $count
                    ];
                })->values()->toArray();
            }

            if (count($mostReplies)) {
                arsort($mostReplies);
                $mostReplies = array_slice($mostReplies, 0, 10);
                $mostReplies = Collection::make($mostReplies)->map(function ($count, $hashtag) {
                    return [
                        'text' => $hashtag,
                        'count' => $count
                    ];
                })->values()->toArray();
            }

            $tweetsAnalysis = [
                'tweets_count' => count($tweets),
                'tweets_date_range' => $tweetsDateRange,
                'langs_count' => $langsCount, // TOBEREMOVED
                'most_langs' => $langsCount,
                'most_words' => $mostWords,
                'most_mentions' => $mostMentions,
                'most_hashtags' => $mostHashtags,
                'most_replies' => $mostReplies,
            ];

            Log::debug($tweetsAnalysis);

            $topicRepository->updateTopic($this->topicId, [
                'on_analyze_tweets' => false,
                'tweets_analysis' => $tweetsAnalysis,
            ], $this->userId);

            $topicRepository->clearCaches($this->topicId, $this->userId);
        } catch (\Throwable $th) {
            // dd($th);
            Log::error($th);
            $topicRepository->updateTopic($this->topicId, [
                'on_analyze_tweets' => false
            ], $this->userId);
        }

        // $topicRepository->updateTopic($this->topicId, [
        //     'on_analyze_tweets' => false
        // ], $this->userId);

        Log::info('AnalyzeTweets@handle end');
    }
}
