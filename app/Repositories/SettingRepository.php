<?php

namespace App\Repositories;

use App\Models\Setting;
use App\Repositories\Repository;

class SettingRepository extends Repository
{
    public function __construct(Setting $model)
    {
        $this->model = $model;
    }
}
