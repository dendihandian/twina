<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Wrappers\Twitter\Twitter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Repositories\TopicRepository;
use App\Repositories\TopicTweetRepository;
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
        $this->twitter = new Twitter; // TODO: don't use wrapper directly, use repository.
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
        Log::info('MiningTweets@handle start');
        Log::debug(['topicId' => $this->topicId, 'userId' => $this->userId]);

        // NOTE: for the best debugging experience, try to use QUEUE_CONNECTION=sync in the .env instead of 'database' or any queue driver.

        try {
            if ($this->userId) {
                $topic = $topicRepository->getTopic($this->topicId, $this->userId);
            } else {
                $topic = $topicRepository->getTopic($this->topicId);
            }

            Log::debug([
                'topic' => $topic
            ]);

            $param = [
                'q' => $topic['text'],
                'result_type' => $topic['result_type'] ?? 'recent',
            ];

            $lastTweet = (isset($this->topic['last_fetch_tweet']) && !empty($this->topic['last_fetch_tweet'])) ? $this->topic['last_fetch_tweet'] : null;

            if ($lastTweet) {
                $param['since_id'] = $lastTweet['id'];
            }

            $statuses = $this->twitter->searchTweets($param);

            $tweetCount = 0;
            $lastFetchCount = $topic['last_fetch_count'] ?? 0;

            if ($searchCount = count($statuses->statuses)) {

                Log::debug('search tweets count: ' . $searchCount);

                // TODO: find a way to append tweets if possible (without merging with existing)
                if ($this->userId) {
                    $existingStatuses = $topicTweetRepository->getTopicTweets($this->topicId, $this->userId);
                } else {
                    $existingStatuses = $topicTweetRepository->getTopicTweets($this->topicId);
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
            }

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
                $topicTweetRepository->putTopicTweets($this->topicId, $mergedStatuses);
                $topicRepository->updateTopic($this->topicId, $updateParams);
            }
        } catch (\Throwable $th) {
            Log::error($th);

            $updateParams = ['on_queue' => false];

            if ($this->userId) {
                $topicRepository->updateTopic($this->topicId, $updateParams, $this->userId);
            } else {
                $topicRepository->updateTopic($this->topicId, $updateParams);
            }
        }

        Log::info('MiningTweets@handle end');
    }
}
