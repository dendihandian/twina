<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Entities\Topic;
use App\Entities\Tweet;
use App\Wrappers\Twitter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
        $userId,
        $topicId
    ) {
        $this->userId = $userId;
        $this->topicId = $topicId;
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
        Log::debug(Collection::make([['id' => 'qweqeq'], ['id' => 'dpqwie']])->keyBy('id')->toArray());

        // NOTE: for the best debugging experience, try to use QUEUE_CONNECTION=sync in the .env instead of 'database' or any queue driver.

        try {
            $topic = $topicRepository->getTopic($this->userId, $this->topicId);

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

            if (count($statuses->statuses)) {
                // TODO: find a way to append tweets (without merging with existing)
                $existingStatuses = $topicTweetRepository->getTopicTweets($this->userId, $this->topicId);
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

                $topicTweetRepository->putTopicTweets($this->userId, $this->topicId, $mergedStatuses);

                $topicRepository->updateTopic($this->userId, $this->topicId, [
                    'last_fetch_tweet' => $lastTweet,
                    'last_fetch_count' => $lastFetchCount,
                    'last_fetch_date' => Carbon::now()->toDateTimeString(),
                    'tweets_count' => $tweetCount,
                    'on_queue' => false,
                ]);
            }

            // DB::commit();
        } catch (\Throwable $th) {
            // DB::rollBack();
            Log::error($th);
            $topicRepository->updateTopic($this->userId, $this->topicId, [
                'on_queue' => false,
            ]);
        }

        Log::debug('MiningTopic@handle end');
    }
}
