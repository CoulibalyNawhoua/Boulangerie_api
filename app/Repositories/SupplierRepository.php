<?php

namespace App\Repositories;

use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;
use App\Models\Supplier;
use Carbon\Carbon;

class SupplierRepository extends Repository
{
    public function __construct(Supplier $model)
    {
        $this->model = $model;
    }


    public function list_supplier()
    {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $query = Supplier::where('suppliers.is_deleted',0)
                        ->where('suppliers.bakehouse_id', $bakehouse_id)
                        ->leftJoin('users','users.id','=','suppliers.added_by')
                        ->leftJoin('familles','familles.id','=','suppliers.famille_id')
                        ->orderByDesc('suppliers.created_at');

        return $query->selectRaw('suppliers.*, CONCAT(users.first_name," ",users.last_name) as created_by,familles.name as category')->get();
    }

}
