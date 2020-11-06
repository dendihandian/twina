<?php

namespace App\Jobs;

use App\Repositories\TopicGraphRepository;
use App\Repositories\TopicRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AnalyzeGraph implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $topicId;
    protected $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($topicId, $userId)
    {
        $this->topicId = $topicId;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TopicRepository $topicRepository, TopicGraphRepository $topicGraphRepository)
    {
        Log::info('AnalyzeGraph@handle start');

        Log::debug([
            'topicId' => $this->topicId,
            'userId' => $this->userId,
        ]);

        try {
        } catch (\Throwable $th) {
            Log::error($th);
        }

        $topicRepository->updateTopic($this->topicId, [
            'on_analyze' => false
        ], $this->userId);

        Log::info('AnalyzeGraph@handle end');
    }
}
