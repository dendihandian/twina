<?php

namespace App\Jobs;

use App\Repositories\PeopleRepository;
use App\Repositories\TopicGraphRepository;
use App\Repositories\TopicRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ComplementGraph implements ShouldQueue
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
    public function handle(
        TopicRepository $topicRepository,
        PeopleRepository $peopleRepository,
        TopicGraphRepository $topicGraphRepository
    ) {
        Log::info('ComplementGraph@handle start');
        Log::debug([
            'topicId' => $this->topicId,
            'userId' => $this->userId,
        ]);

        try {
            $topic = $topicRepository->getTopic($this->topicId, $this->userId);

            if (isset($topic['graph']['nodes']) && !empty($topic['graph']['nodes'])) {

                $oldNodes = $topic['graph']['nodes'];
                $newNodes = [];

                Log::debug([
                    'oldNodesCount' => count($oldNodes)
                ]);

                $screenNamesChunk = Collection::make($oldNodes)->keys()->chunk(100);

                foreach ($screenNamesChunk as $screenNames) {
                    $peopleObjects = $peopleRepository->getPeoplesByScreenNames($screenNames->toArray());
                    if ($peopleObjects) {
                        foreach ($peopleObjects as $peopleObject) {
                            $newNodes[$peopleObject->screen_name] = [
                                'id' => $oldNodes[$peopleObject->screen_name]['id'],
                                'group' => $oldNodes[$peopleObject->screen_name]['group'],
                                'img' => $peopleObject->profile_image_url,
                                'verified' => $peopleObject->verified,
                            ];
                        }
                    }
                }

                Log::debug([
                    'newNodesCount' => count($newNodes)
                ]);

                if ($newNodes) {
                    $topicGraphRepository->updateTopicGraph($this->topicId, [
                        'nodes' => $newNodes,
                    ], $this->userId);
                }
            }
        } catch (\Throwable $th) {
            Log::error($th);
        }

        $topicRepository->updateTopic($this->topicId, [
            'on_complement_graph' => false,
        ], $this->userId);

        Log::debug('ComplementGraph@handle end');
    }
}
