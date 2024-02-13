<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ProductRepository extends Repository
{
    public function __construct(Product $model=null)
    {
        $this->model = $model;
    }


    public function ListProduct()
    {
        return DB::table('products')
                ->leftJoin('users','users.id','=','products.added_by')
                ->leftJoin('units','units.id','=','products.unit_id')
                ->leftJoin('sous_familles','sous_familles.id','=','products.sous_famille_id')
                ->leftJoin('categories','categories.id','=','products.category_id')
                ->leftJoin('familles','familles.id','=','sous_familles.famille_id')
                ->selectRaw('produits.*, units.name AS product_unit, CONCAT(users.first_name," ",users.first_name) as created_by, familles.name AS famille, sous_familles.name AS sous_famille, categories.nom_categorie')
                ->get();
    }



}
