<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PreferenceRepository;
use App\Entities\Preference;
use App\Repositories\Traits\RepositoryCacheTrait;
use App\Wrappers\CacheExtended as Cache;

/**
 * Class PreferenceRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PreferenceRepositoryEloquent extends BaseRepository implements PreferenceRepository
{
    use RepositoryCacheTrait;

    protected $preferenceEntity;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Preference::class;
    }

    public function __construct()
    {
        $this->preferenceEntity = new Preference;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getPreference($userId = null)
    {
        return $this->preferenceEntity->getPreference($userId) ?? null;
    }

    public function updatePreference($param, $userId = null)
    {
        return $this->preferenceEntity->updatePreference($param, $userId);
    }

    public function getSelectedTopic($userId = null)
    {
        $cachePath = $this->buildCachePath('preferences_selected_topic', [], $userId);

        if (Cache::has($cachePath)) {
            $selectedTopicId = Cache::get($cachePath);
        } else {
            $preference = $this->getPreference($userId);
            $selectedTopicId = $preference['selected_topic'] ?? null;
            Cache::put($cachePath, $selectedTopicId);
        }

        return $selectedTopicId;
    }

    public function setSelectedTopic($topicId, $userId = null)
    {
        $param = ['selected_topic' => $topicId];
        $result = $this->updatePreference($param, $userId);

        $cachePath = $this->buildCachePath('preferences_selected_topic', [], $userId);
        if (Cache::has($cachePath)) Cache::forget($cachePath);

        return $result;
    }
}
