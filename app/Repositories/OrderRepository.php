<?php

namespace App\Repositories;


use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;

class OrderRepository extends Repository
{

    public function __construct(Order $model)
    {
        $this->model = $model;
    }


}
