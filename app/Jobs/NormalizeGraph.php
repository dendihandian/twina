<?php

namespace App\Jobs;

use App\Repositories\TopicRepository;
use App\Repositories\TopicGraphRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NormalizeGraph implements ShouldQueue
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
     *P
     * @return void
     */
    public function handle(TopicRepository $topicRepository, TopicGraphRepository $topicGraphRepository)
    {
        Log::info('NormalizeGraph@handle start');

        Log::debug([
            'topicId' => $this->topicId,
            'userId' => $this->userId,
        ]);

        try {
            $graph = $topicGraphRepository->getTopicGraph($this->topicId, $this->userId);

            if ($graph['edges']) {
                // Filtering edges that contains non-exist node (people) in their source or target.
                // NOTE: non-exist source or target node will cause the d3.js graph to be error...
                $nodesKeys = array_keys($graph['nodes']);
                $graph['edges'] = collect($graph['edges'])->filter(function ($edge) use ($nodesKeys) {
                    return in_array($edge['source'], $nodesKeys) && in_array($edge['target'], $nodesKeys);
                })->toArray();
            }

            $topicGraphRepository->updateTopicGraph($this->topicId, $graph, $this->userId);
        } catch (\Throwable $th) {
            Log::error($th);
        }

        $topicRepository->updateTopic($this->topicId, [
            'on_normalize' => false,
        ], $this->userId);

        Log::info('NormalizeGraph@handle end');
    }
}
