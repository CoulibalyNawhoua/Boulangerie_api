<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreFamilleRequest;
use App\Models\Famille;
use Illuminate\Http\Request;

class FamilleRepository extends Repository
{
    public function __construct(Famille $model)
    {
        $this->model = $model;
    }

    public function ListFamilles()
    {
        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        return Famille::where('is_deleted',0)
                    // ->where('familles.bakehouse_id', $bakehouse_id)
                    ->leftJoin('users','users.id','=','familles.added_by')
                    ->selectRaw('familles.*, CONCAT(users.first_name," ",users.first_name) as created_by')->get();
    }

}
