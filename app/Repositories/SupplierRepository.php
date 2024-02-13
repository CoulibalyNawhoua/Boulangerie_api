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

        $query = Supplier::where('suppliers.is_deleted',0)
                        ->leftJoin('users','users.id','=','suppliers.added_by');

        return $query->selectRaw('suppliers.first_name, suppliers.last_name, suppliers.phone, suppliers.address, suppliers.status, CONCAT(users.first_name," ",users.last_name) as created_by')->get();
    }

}
