<?php

namespace App\Repositories;

use Carbon\Carbon;

use App\Models\Customer;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;


class CustomerRepository extends Repository
{
    public function __construct(Customer $model)
    {
        $this->model = $model;
    }

    public function list_customer()
    {
        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        return Customer::where('customers.is_deleted',0)
                            ->where('customers.bakehouse_id', $bakehouse_id)
                            ->leftJoin('users','users.id','=','customers.added_by')
                            ->selectRaw('customers.*, CONCAT(users.first_name," ",users.last_name) as auteur')->get();

    }



}
