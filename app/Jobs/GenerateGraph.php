<?php

namespace App\Jobs;

use App\Repositories\TopicRepository;
use App\Repositories\PeopleRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class GenerateGraph implements ShouldQueue
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
    public function handle(TopicRepository $topicRepository, PeopleRepository $peopleRepository)
    {
        Log::debug('GenerateGraph@handle start');

        try {
            $topic = $topicRepository->getTopic($this->topicId, $this->userId);
            $nodes = [];
            $edges = [];

            foreach ($topic['tweets'] as $tweetId => $tweet) {
                $nodes[$tweet['user']['id']] = [
                    'id' => $tweet['user']['id'],
                    'screen_name' => $tweet['user']['screen_name'],
                    'name' => $tweet['user']['name'],
                    'img' => $tweet['user']['profile_image_url'],
                    'img_https' => $tweet['user']['profile_image_url_https'],
                    'verified' => $tweet['user']['verified'] ?? false,
                ];

                // check for in reply
                $inReplyUserId = null;
                if (isset($tweet['in_reply_to_id']) && !empty($tweet['in_reply_to_id'])) {
                    $inReplyUserId = $tweet['in_reply_to_id'];
                    if (isset($edges[$tweet['user']['id'] . '@' . $inReplyUserId])) {
                        $edges[$tweet['user']['id'] . '@' . $inReplyUserId]['value'] += 1;
                        $edges[$tweet['user']['id'] . '@' . $inReplyUserId]['text'] = $tweet['text'];
                        $edges[$tweet['user']['id'] . '@' . $inReplyUserId]['tweet_id'] = $tweet['id'];
                    } else if (isset($edges[$inReplyUserId . '@' . $tweet['user']['id']])) {
                        $edges[$inReplyUserId . '@' . $tweet['user']['id']]['value'] += 1;
                        $edges[$inReplyUserId . '@' . $tweet['user']['id']]['text'] = $tweet['text'];
                        $edges[$inReplyUserId . '@' . $tweet['user']['id']]['tweet_id'] = $tweet['id'];
                    } else {
                        $edges[$tweet['user']['id'] . '@' . $inReplyUserId] = [
                            'source' => $tweet['user']['id'],
                            'target' => $inReplyUserId,
                            'value' => 1,
                            'tweet' => [
                                'id' => $tweet['id'],
                                'text' => $tweet['text'],
                                'date' => Carbon::parse($tweet['created_at'])->toDateTimeString(),
                            ],
                        ];
                    }
                }

                // check for mentions
                if (isset($tweet['entities']['user_mentions']) && !empty($tweet['entities']['user_mentions'])) {
                    foreach ($tweet['entities']['user_mentions'] as $mention) {
                        if ($mention['id'] != $inReplyUserId) {
                            if (isset($edges[$tweet['user']['id'] . '@' . $mention['id']])) {
                                $edges[$tweet['user']['id'] . '@' . $mention['id']]['value'] += 1;
                                $edges[$tweet['user']['id'] . '@' . $mention['id']]['text'] = $tweet['text'];
                                $edges[$tweet['user']['id'] . '@' . $mention['id']]['tweet_id'] = $tweet['id'];
                            } else if (isset($edges[$mention['id'] . '@' . $tweet['user']['id']])) {
                                $edges[$mention['id'] . '@' . $tweet['user']['id']]['value'] += 1;
                                $edges[$mention['id'] . '@' . $tweet['user']['id']]['text'] = $tweet['text'];
                                $edges[$mention['id'] . '@' . $tweet['user']['id']]['tweet_id'] = $tweet['id'];
                            } else {
                                $edges[$tweet['user']['id'] . '@' . $mention['id']] = [
                                    'source' => $tweet['user']['id'],
                                    'target' => $mention['id'],
                                    'value' => 1,
                                    'tweet' => [
                                        'id' => $tweet['id'],
                                        'text' => $tweet['text'],
                                        'date' => Carbon::parse($tweet['created_at'])->toDateTimeString(),
                                    ],
                                ];
                            }

                            // adding to nodes
                            $nodes[$mention['id']] = [
                                'id' => $mention['id'],
                                'name' => $mention['name'],
                                'screen_name' => $mention['screen_name'],
                                'verified' => false, // set to false because of mini object data
                            ];
                        }
                    }
                }
            }

            if ($edges) {
                // Filtering edges that contains non-exist node (people) in their source or target.
                // NOTE: non-exist source or target node will cause the d3.js graph to be error...
                $nodesKeys = array_keys($nodes);
                $edges = collect($edges)->filter(function ($edge) use ($nodesKeys) {
                    return in_array($edge['source'], $nodesKeys) && in_array($edge['target'], $nodesKeys);
                })->toArray();
            }

            if (isset($nodes) && !empty($nodes)) {

                $oldNodes = $nodes;
                $newNodes = [];

                Log::debug([
                    'oldNodesCount' => count($oldNodes)
                ]);

                $oldNodeUserIds = Collection::make($oldNodes)->keys()->chunk(100);

                foreach ($oldNodeUserIds as $userIds) {
                    $peopleObjects = $peopleRepository->getPeoplesByUserIds($userIds->toArray());
                    if ($peopleObjects) {
                        foreach ($peopleObjects as $peopleObject) {
                            // dd($peopleObject);
                            $newNodes[$peopleObject->id] = [
                                'id' => $oldNodes[$peopleObject->id]['id'],
                                'name' => $oldNodes[$peopleObject->id]['name'],
                                'screen_name' => $oldNodes[$peopleObject->id]['screen_name'],
                                'img' => $peopleObject->profile_image_url,
                                'verified' => $peopleObject->verified,
                            ];
                        }
                    }
                }

                // find the deleted accounts
                foreach (array_diff(array_keys($oldNodes), array_keys($newNodes)) as $userId) {
                    $newNodes[$userId] = $oldNodes[$userId];
                    $newNodes[$userId]['deleted'] = true;
                }

                Log::debug([
                    'newNodesCount' => count($newNodes)
                ]);

                $nodes = $newNodes;
            }

            if ($nodes || $edges) {
                $topicRepository->updateTopic($this->topicId, [
                    'graph' => [
                        'nodes' => $nodes,
                        'edges' => $edges,
                    ],
                    'on_generate' => false,
                ], $this->userId);

                $topicRepository->clearCaches($this->topicId, $this->userId);
            }
        } catch (\Throwable $th) {
            Log::error($th);
            $topicRepository->updateTopic($this->topicId, ['on_generate' => false], $this->userId);
        }


        Log::debug('GenerateGraph@handle end');
    }
}
