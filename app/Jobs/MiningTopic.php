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

class MiningTopic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $topic;
    protected $twitter;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
        $this->twitter = new Twitter;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug('MiningTopic@handle start');

        try {
            DB::beginTransaction();

            $lastTweet = $this->topic->last_tweet;

            $statuses = $this->twitter->searchTweets([
                'q' => 'laravel',
                'since_id' => $lastTweet,
            ]);

            $bulk = [];

            foreach (array_reverse($statuses->statuses) as $status) {
                $bulk[] =
                    [
                        'tweet_id' => $status->id ?? null,
                        'people_id' => $status->user->id ?? null,
                        'text' => $status->text ?? null,
                        'created_date' => $status->created_at ?? null,
                        'in_reply_to_people_id' => $status->in_reply_to_user_id ?? null,
                        'in_reply_to_status_id' => $status->in_reply_to_status_id ?? null,
                        'lang' => $status->lang ?? null,
                    ];

                $lastTweet = $status->id ?? $lastTweet;
            }

            Tweet::insert($bulk);

            $this->topic->update([
                'on_queue' => false,
                'last_tweet' => $lastTweet,
                'total_tweets' => $this->topic->total_tweets + count($bulk),
                'last_fetched_tweets' => count($bulk),
                'last_mining' => Carbon::now(),
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            $this->topic->update([
                'on_queue' => false,
            ]);
        }

        Log::debug('MiningTopic@handle end');
    }
}
