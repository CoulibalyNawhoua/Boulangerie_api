<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Unit;
use App\Repositories\Repository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class UnitRepository extends Repository
{
    public function __construct(Unit $model)
    {
        $this->model = $model;
    }

    public function List_units()
    {
        return Unit::where('units.is_deleted',0)
            ->leftJoin('users','users.id','=','units.added_by')
            ->selectRaw('units.*, CONCAT(users.first_name," ",users.first_name) as auteur')->get();
    }

}
