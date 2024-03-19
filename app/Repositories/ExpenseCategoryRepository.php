<?php

namespace App\Repositories;

use App\Models\Expense_category;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;

class ExpenseCategoryRepository extends Repository
{
    public function __construct(Expense_category $model)
    {
        $this->model = $model;
    }

    public function list_expense_category()
    {
        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        return Expense_category::where('expense_categories.is_deleted',0)
                            ->where('expense_categories.bakehouse_id', $bakehouse_id)
                            ->leftJoin('users','users.id','=','expense_categories.added_by')
                            ->selectRaw('expense_categories.id,expense_categories.name, CONCAT(users.first_name," ",users.last_name) as auteur')->get();

    }
}
