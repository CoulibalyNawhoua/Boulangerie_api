<?php

namespace App\Repositories;

use Carbon\Carbon;

use App\Models\Customer;
use App\Repositories\Repository;


class CustomerRepository extends Repository
{
    public function __construct(Customer $model)
    {
        $this->model = $model;
    }

    public function list_customer()
    {
        return Customer::where('customers.is_deleted',0)
                            ->leftJoin('users','users.id','=','customers.added_by')
                            ->selectRaw('customers.name, CONCAT(users.first_name," ",users.last_name) as auteur');

    }



}
