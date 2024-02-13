<?php

namespace App\Repositories;

use App\Models\Bakehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;

class BakehouseRepository extends Repository
{
    public function __construct(Bakehouse $model)
    {
        $this->model = $model;
    }

}
