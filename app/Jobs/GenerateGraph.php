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
                $userScreenName = $tweet['user']['screen_name'];
                $nodes[$userScreenName] = [
                    'id' => $userScreenName,
                    'name' => $tweet['user']['name'],
                    'img' => $tweet['user']['profile_image_url'],
                    'verified' => $tweet['user']['verified'] ?? false,
                    'group' => rand(1, 5), // TODO: you know...
                    // 'img_https' => $tweet['user']['profile_image_url_https'],
                    // TODO: profile, link profile, etc. here
                ];

                // check for in reply
                $inReplyScreenName = null;
                if (isset($tweet['in_reply_to_screen_name']) && !empty($tweet['in_reply_to_screen_name'])) {
                    $inReplyScreenName = $tweet['in_reply_to_screen_name'];
                    if (isset($edges[$userScreenName . '@' . $inReplyScreenName])) {
                        $edges[$userScreenName . '@' . $inReplyScreenName]['value'] += 1;
                        $edges[$userScreenName . '@' . $inReplyScreenName]['text'] = $tweet['text'];
                        $edges[$userScreenName . '@' . $inReplyScreenName]['tweet_id'] = $tweet['id'];
                    } else if (isset($edges[$inReplyScreenName . '@' . $userScreenName])) {
                        $edges[$inReplyScreenName . '@' . $userScreenName]['value'] += 1;
                        $edges[$inReplyScreenName . '@' . $userScreenName]['text'] = $tweet['text'];
                        $edges[$inReplyScreenName . '@' . $userScreenName]['tweet_id'] = $tweet['id'];
                    } else {
                        $edges[$userScreenName . '@' . $inReplyScreenName] = [
                            'source' => $userScreenName,
                            'target' => $inReplyScreenName,
                            'value' => 1,
                            'text' => $tweet['text'],
                            'tweet_id' => $tweet['id'],
                            'date' => Carbon::parse($tweet['created_at'])->toDateTimeString(),
                        ];
                    }
                }

                // check for mentions
                if (isset($tweet['entities']['user_mentions']) && !empty($tweet['entities']['user_mentions'])) {
                    foreach ($tweet['entities']['user_mentions'] as $mention) {
                        if ($mention['screen_name'] != $inReplyScreenName) {
                            if (isset($edges[$userScreenName . '@' . $mention['screen_name']])) {
                                $edges[$userScreenName . '@' . $mention['screen_name']]['value'] += 1;
                                $edges[$userScreenName . '@' . $mention['screen_name']]['text'] = $tweet['text'];
                                $edges[$userScreenName . '@' . $mention['screen_name']]['tweet_id'] = $tweet['id'];
                            } else if (isset($edges[$mention['screen_name'] . '@' . $userScreenName])) {
                                $edges[$mention['screen_name'] . '@' . $userScreenName]['value'] += 1;
                                $edges[$mention['screen_name'] . '@' . $userScreenName]['text'] = $tweet['text'];
                                $edges[$mention['screen_name'] . '@' . $userScreenName]['tweet_id'] = $tweet['id'];
                            } else {
                                $edges[$userScreenName . '@' . $mention['screen_name']] = [
                                    'source' => $userScreenName,
                                    'target' => $mention['screen_name'],
                                    'value' => 1,
                                    'text' => $tweet['text'],
                                    'tweet_id' => $tweet['id'],
                                    'date' => Carbon::parse($tweet['created_at'])->toDateTimeString(),
                                ];
                            }

                            // adding to nodes
                            $nodes[$mention['screen_name']] = [
                                'id' => $mention['screen_name'],
                                'name' => $mention['name'],
                                'group' => rand(1, 5),
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

                $oldNodeScreenNamesChunk = Collection::make($oldNodes)->keys()->chunk(100);

                foreach ($oldNodeScreenNamesChunk as $screenNames) {
                    $peopleObjects = $peopleRepository->getPeoplesByScreenNames($screenNames->toArray());
                    if ($peopleObjects) {
                        foreach ($peopleObjects as $peopleObject) {
                            $newNodes[$peopleObject->screen_name] = [
                                'id' => $oldNodes[$peopleObject->screen_name]['id'],
                                'name' => $oldNodes[$peopleObject->screen_name]['name'],
                                'group' => $oldNodes[$peopleObject->screen_name]['group'],
                                'img' => $peopleObject->profile_image_url,
                                'verified' => $peopleObject->verified,
                            ];
                        }
                    }
                }

                // find the deleted accounts
                foreach (array_diff(array_keys($oldNodes), array_keys($newNodes)) as $screenName) {
                    $newNodes[$screenName] = $oldNodes[$screenName];
                    $newNodes[$screenName]['deleted'] = true;
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
            }
        } catch (\Throwable $th) {
            Log::error($th);
            $topicRepository->updateTopic($this->topicId, ['on_generate' => false], $this->userId);
        }


        Log::debug('GenerateGraph@handle end');
    }
}
