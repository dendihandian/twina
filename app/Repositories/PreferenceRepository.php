<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface PreferenceRepository.
 *
 * @package namespace App\Repositories;
 */
interface PreferenceRepository extends RepositoryInterface
{
    public function getPreference($userId = null);
    public function updatePreference($param, $userId = null);

    public function getSelectedTopic($userId = null);
    public function setSelectedTopic($topicId, $userId = null);
}
