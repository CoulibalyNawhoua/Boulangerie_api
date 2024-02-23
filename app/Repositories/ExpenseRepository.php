<?php

namespace App\Repositories;

use App\Models\Expense;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;

class ExpenseRepository extends Repository
{
    public function __construct(Expense $model)
    {
        $this->model = $model;
    }

    public function list_expense()
    {
        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        return Expense::where('expenses.is_deleted',0)
                            ->where('expenses.bakehouse_id', $bakehouse_id)
                            ->leftJoin('users','users.id','=','expenses.added_by')
                            ->selectRaw('expenses.*, CONCAT(users.first_name," ",users.last_name) as auteur');

    }
}
