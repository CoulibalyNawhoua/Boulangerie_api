<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Sous_famille;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;

/**
 * Class Sous_familleRepository.
 */
class Sous_familleRepository extends Repository
{
    public function __construct(Sous_famille $model)
    {
        $this->model = $model;
    }


    public function list_sous_fammilles()
    {
        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        return Sous_famille::where('sous_familles.is_deleted',0)
                            ->where('sous_familles.bakehouse_id', $bakehouse_id)
                            ->leftJoin('familles','familles.id','=','sous_familles.famille_id')
                            ->leftJoin('users','users.id','=','sous_familles.added_by')
                            ->selectRaw('sous_familles.*, familles.name AS nom_famille, CONCAT(users.first_name," ",users.last_name) as created_by')->get();

    }


}
