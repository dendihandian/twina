<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Repositories\TopicRepository;
use App\Repositories\TopicTweetRepository;
use App\Repositories\TweetRepository;
use Illuminate\Support\Collection;

class MiningTweets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $topicId;
    protected $twitter;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        $topicId,
        $userId = null
    ) {
        $this->topicId = $topicId;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        TopicRepository $topicRepository,
        TopicTweetRepository $topicTweetRepository,
        TweetRepository $tweetRepository
    ) {
        Log::info('MiningTweets@handle start');
        Log::debug(['topicId' => $this->topicId, 'userId' => $this->userId]);

        // NOTE: for the best debugging experience, try to use QUEUE_CONNECTION=sync in the .env instead of 'database' or any queue driver.

        try {
            // default / previous values
            $topic = $topicRepository->getTopic($this->topicId, $this->userId);
            $lastTweet = $topic['last_fetch_tweet'] ?? null;
            $lastFetchCount = $topic['last_fetch_count'] ?? 0;
            $tweetCount = $topic['tweets_count'] ?? 0;

            $searchParam = [
                'q' => $topic['text'],
                'result_type' => $topic['result_type'] ?? 'recent',
                'since_id' => $lastTweet['id'] ?? null,
            ];

            Log::debug('search param', $searchParam);

            // search tweets
            $statuses = $tweetRepository->searchTweets($searchParam);

            if ($searchCount = count($statuses->statuses)) {

                Log::debug('search tweets count: ' . $searchCount);

                // TODO: find a way to append tweets if possible (without merging with existing)

                // NOTE: performance exception
                // $existingStatuses = $topicTweetRepository->getTopicTweets($this->topicId, $this->userId);
                $existingStatuses = $topic['tweets'] ?? [];

                $existingStatuses = (is_array($existingStatuses) && count($existingStatuses)) ? Collection::make($existingStatuses)->keyBy('id')->toArray() : [];

                $statuses = json_decode(json_encode(array_reverse($statuses->statuses)), true);
                $statuses = Collection::make($statuses)->keyBy('id')->toArray();

                if ($statuses) {
                    $lastTweet = end($statuses);
                }

                $lastFetchCount = $searchCount;

                // NOTE: merging two associative arrays using array_merge() will make the keys gone. So we use '+' operator instead. 
                $mergedStatuses = $existingStatuses ? ($existingStatuses + $statuses) : $statuses;

                $tweetCount = count($mergedStatuses);
            }

            // NOTE: performance exception
            // $topicTweetRepository->setTopicTweets($this->topicId, $mergedStatuses, $this->userId);

            $topicRepository->updateTopic($this->topicId, [
                'last_fetch_tweet' => $lastTweet,
                'last_fetch_count' => $lastFetchCount,
                'last_fetch_date' => Carbon::now()->toDateTimeString(),
                'tweets_count' => $tweetCount,
                'on_mining' => false,
                'tweets' => $mergedStatuses, // performance exception
            ], $this->userId);
        } catch (\Throwable $th) {
            Log::error($th);
            $topicRepository->updateTopic($this->topicId, [
                'on_mining' => false
            ], $this->userId);
        }

        Log::info('MiningTweets@handle end');
    }
}
