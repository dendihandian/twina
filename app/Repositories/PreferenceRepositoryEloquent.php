<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PreferenceRepository;
use App\Entities\Preference;

/**
 * Class PreferenceRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PreferenceRepositoryEloquent extends BaseRepository implements PreferenceRepository
{
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
        $preference = $this->getPreference($userId);
        return $preference['selected_topic'] ?? null;
    }

    public function setSelectedTopic($topicId, $userId = null)
    {
        $param = ['selected_topic' => $topicId];
        return $this->updatePreference($param, $userId);
    }
}
