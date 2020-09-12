<?php

namespace App\Http\Controllers;

use App\Repositories\PreferenceRepository;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    protected $preferenceRepository;

    public function __construct(PreferenceRepository $preferenceRepository)
    {
        $this->preferenceRepository = $preferenceRepository;
    }

    public function setSelectedTopic($topic)
}
