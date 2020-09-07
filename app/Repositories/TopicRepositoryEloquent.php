<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TopicRepository;
use App\Entities\Topic;
use App\Validators\TopicValidator;
use App\Jobs\MiningTopic;
use App\Jobs\AnalyzeTopic;
use App\Wrappers\Firebase\Firebase;

/**
 * Class TopicRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TopicRepositoryEloquent extends BaseRepository implements TopicRepository
{
    protected $firebase;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Topic::class;
    }

    public function __construct()
    {
        $this->firebase = new Firebase;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getTopics($userId = null)
    {
        if ($userId) {
            return $this->firebase->getTopics($userId);
        } else {
            return $this->firebase->getPublicTopics();
        }
    }

    public function getTopic($topicId, $userId = null)
    {
        if ($userId) {
            return $this->firebase->getTopic($userId, $topicId);
        } else {
            return $this->firebase->getPublicTopic($topicId);
        }
    }

    public function createTopic($param, $userId = null)
    {
        $param = [
            'text' => $param['name'],
        ];

        if ($userId) {
            $this->firebase->addTopic($userId, $param);
        } else {
            $this->firebase->addPublicTopic($param);
        }
    }

    public function updateTopic($topicId, $param, $userId = null)
    {
        if ($userId) {
            $this->firebase->updateTopic($userId, $topicId, $param);
        } else {
            $this->firebase->updatePublicTopic($topicId, $param);
        }
    }

    public function startMining($topicId, $userId = null)
    {
        $param = ['on_queue' => true];

        if ($userId) {
            $this->firebase->updateTopic($userId, $topicId, $param);
            MiningTopic::dispatch($topicId, $userId);
        } else {
            $this->firebase->updatePublicTopic($topicId, $param);
            MiningTopic::dispatch($topicId);
        }
    }

    public function analyze($topicId, $userId = null)
    {
        $topic = $this->getTopic($topicId, $userId);
        $nodes = [];
        $edges = [];

        foreach ($topic['tweets'] as $tweetId => $tweet) {
            $userScreenName = $tweet['user']['screen_name'];

            $nodes[$userScreenName] = [
                'id' => $userScreenName,
                'group' => rand(1, 5), // TODO: you know...
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
                            ];
                        }
                    }
                }
            }
        }

        dd($topic['tweets'], $nodes, $edges);
    }
}
