<?php

namespace App\Repositories;

use App\Models\Expense;
use App\Repositories\Repository;

class ExpenseRepository extends Repository
{
    public function __construct(Expense $model)
    {
        $this->model = $model;
    }

    public function list_expense()
    {
        return Expense::where('expenses.is_deleted',0)
                            ->leftJoin('users','users.id','=','expenses.added_by')
                            ->selectRaw('expenses.*, CONCAT(users.first_name," ",users.last_name) as auteur');

    }
}
