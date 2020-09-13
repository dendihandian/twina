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

            // default values
            $accountsDeleted = [];
            $tweetsDateRange = ['min' => null, 'max' => null];

            $graph = $topicGraphRepository->getTopicGraph($this->topicId, $this->userId);

            // gain information by looping the nodes
            if (isset($graph['nodes']) && !empty($graph['nodes']) && is_array($graph['nodes'])) {
                foreach ($graph['nodes'] as $screenName => $node) {

                    // account deleted analysis
                    if (isset($node['deleted']) && !empty($node['deleted'])) {
                        $accountsDeleted[] = $screenName;
                    }
                }
            }

            // gain information by looping the edges
            if (isset($graph['edges']) && !empty($graph['edges']) && is_array($graph['edges'])) {
                foreach ($graph['edges'] as $key => $edge) {

                    // date range analysis
                    if (isset($edge['date']) && !empty($edge['date'])) {
                        if (!($tweetsDateRange['min']) || $tweetsDateRange['min'] >= $edge['date']) {
                            $tweetsDateRange['min'] = $edge['date'];
                        }

                        if (!($tweetsDateRange['max']) || $tweetsDateRange['max'] <= $edge['date']) {
                            $tweetsDateRange['max'] = $edge['date'];
                        }
                    }
                }
            }

            $analysis = [
                'deleted_accounts' => $accountsDeleted,
                'tweets_date_range' => $tweetsDateRange,
            ];

            Log::debug([
                'analysis' => $analysis
            ]);

            $topicGraphRepository->updateTopicGraph($this->topicId, [
                'analysis' => $analysis
            ], $this->userId);
        } catch (\Throwable $th) {
            Log::error($th);
        }

        $topicRepository->updateTopic($this->topicId, [
            'on_analyze' => false
        ], $this->userId);

        Log::info('AnalyzeGraph@handle end');
    }
}
