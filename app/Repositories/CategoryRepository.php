<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Category;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;

class CategoryRepository extends Repository
{
    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function listCategory()
    {
        return Category::where('is_deleted',0)
                ->leftJoin('users','users.id','=','categories.added_by')
                ->selectRaw('categories.name, CONCAT(users.first_name," ",users.first_name) as created_by');
    }


}
