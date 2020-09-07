<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Wrappers\Twitter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Repositories\TopicRepository;
use App\Repositories\TopicTweetRepository;
use Illuminate\Support\Collection;

class MiningTopic implements ShouldQueue
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
        $this->twitter = new Twitter;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        TopicRepository $topicRepository,
        TopicTweetRepository $topicTweetRepository
    ) {
        Log::debug('MiningTopic@handle start');
        Log::debug('User ID: ' . $this->userId);

        // NOTE: for the best debugging experience, try to use QUEUE_CONNECTION=sync in the .env instead of 'database' or any queue driver.

        try {
            if ($this->userId) {
                $topic = $topicRepository->getTopic($this->topicId, $this->userId);
            } else {
                $topic = $topicRepository->getPublicTopic($this->topicId);
            }

            Log::debug('topic text: ' . $topic['text']);

            $param = [
                'q' => $topic['text'],
            ];

            $lastTweet = (isset($this->topic['last_fetch_tweet']) && !empty($this->topic['last_fetch_tweet'])) ? $this->topic['last_fetch_tweet'] : null;

            if ($lastTweet) {
                $param['since_id'] = $lastTweet;
            }

            $statuses = $this->twitter->searchTweets($param);

            $tweetCount = 0;
            $lastFetchCount = 0;

            if ($searchCount = count($statuses->statuses)) {

                Log::debug('search tweets count: ' . $searchCount);

                // TODO: find a way to append tweets if possible (without merging with existing)
                if ($this->userId) {
                    $existingStatuses = $topicTweetRepository->getTopicTweets($this->topicId, $this->userId);
                } else {
                    $existingStatuses = $topicTweetRepository->getPublicTopicTweets($this->topicId);
                }

                $existingStatuses = (is_array($existingStatuses) && count($existingStatuses)) ? Collection::make($existingStatuses)->keyBy('id')->toArray() : [];

                $statuses = json_decode(json_encode(array_reverse($statuses->statuses)), true);
                $statuses = Collection::make($statuses)->keyBy('id')->toArray();

                if ($statuses) {
                    $lastTweet = end($statuses);
                }

                $lastFetchCount = count($statuses);

                // NOTE: merging two associative arrays using array_merge() will make the keys gone. So we use '+' operator instead. 
                $mergedStatuses = $existingStatuses ? ($existingStatuses + $statuses) : $statuses;

                $tweetCount = count($mergedStatuses);

                $updateParams = [
                    'last_fetch_tweet' => $lastTweet,
                    'last_fetch_count' => $lastFetchCount,
                    'last_fetch_date' => Carbon::now()->toDateTimeString(),
                    'tweets_count' => $tweetCount,
                    'on_queue' => false,
                ];

                if ($this->userId) {
                    $topicTweetRepository->putTopicTweets($this->topicId, $mergedStatuses, $this->userId);
                    $topicRepository->updateTopic($this->topicId, $updateParams, $this->userId);
                } else {
                    $topicTweetRepository->putPublicTopicTweets($this->topicId, $mergedStatuses);
                    $topicRepository->updatePublicTopic($this->topicId, $updateParams);
                }
            }
        } catch (\Throwable $th) {
            Log::error($th);

            $updateParams = ['on_queue' => false];

            if ($this->userId) {
                $topicRepository->updateTopic($this->topicId, $updateParams, $this->userId);
            } else {
                $topicRepository->updatePublicTopic($this->topicId, $updateParams);
            }
        }

        Log::debug('MiningTopic@handle end');
    }
}
