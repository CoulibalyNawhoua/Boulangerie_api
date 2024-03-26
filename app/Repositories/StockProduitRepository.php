<?php

namespace App\Repositories;


use App\Models\StockProduct;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;

class StockProduitRepository extends Repository
{


    public function __construct(StockProduct $model)
    {
        $this->model = $model;
    }

    public function sale_stock(){

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $query = StockProduct::selectRaw('products.uuid,products.price, products_stock.product_id, products_stock.id, products.name AS product_name, products.image, products_stock.quantity')
                    ->where('products.type', 1)
                    ->where('products_stock.bakehouse_id', $bakehouse_id)
                    ->leftJoin('products', 'products.id', '=', 'products_stock.product_id')
                    ->get();

        return $query;
    }


    public function production_stock(){

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $query = StockProduct::selectRaw('products.price,products.unit_id, products_stock.product_id, products_stock.id, products.name AS product_name, products.image, products_stock.quantity, units.name AS unit_name')
                    ->where('products.type', 0)
                    ->where('products_stock.bakehouse_id', $bakehouse_id)
                    ->leftJoin('products', 'products.id', '=', 'products_stock.product_id')
                    ->leftJoin('units', 'units.id', '=', 'products.unit_id')
                    ->get();

        return $query;
    }




}
