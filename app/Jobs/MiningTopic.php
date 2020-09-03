<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Entities\Topic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MiningTopic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $topic;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
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
            Log::debug($this->topic->name);
        } catch (\Throwable $th) {
            Log::error($th);
        }

        $this->topic->update([
            'on_queue' => false,
            'last_mining' => Carbon::now(),
        ]);

        Log::debug('MiningTopic@handle end');
    }
}
