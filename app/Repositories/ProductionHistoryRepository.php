<?php

namespace App\Repositories;


use App\Models\ProductHistory;
use App\Models\ProductionHistory;
use App\Models\ProductStock;
use App\Repositories\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductionHistoryRepository extends Repository
{

    public function __construct(ProductionHistory $model)
    {
        $this->model=$model;
    }


    public function ProductionHistoryList() {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $query = ProductHistory::leftJoin('products', 'products.id', '=', 'products_histories.product_id')
                ->selectRaw('products.name,products.price, products.image, products_histories.quantity, products_histories.add_date')
                ->where('products_histories.bakehouse_id', $bakehouse_id)
                ->where('products.type', 0)
                ->where('products_histories.is_deleted', 0)
                ->get();

        return $query;
    }


    public function store(Request $request) {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        ProductHistory::create([
            'quantity'=> $request->quantity,
            'product_id' => $request->product_id,
            'added_by' => Auth::user()->id,
            'add_ip' => $this->getIp(),
            'bakehouse_id' => $bakehouse_id
        ]);

        $productInStk = ProductStock::where('product_id', $request->product_id)
                            ->where('bakehouse_id',  $bakehouse_id)
                            ->first();

        $productInStk->increment('quantity', $request->quantity);

    }

    public function ProductionHistoryListDetail($uuid) {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $query = ProductHistory::leftJoin('products', 'products.id', '=', 'products_histories.product_id')
                ->selectRaw('products.name,products.price, products.image, products_histories.quantity, products_histories.add_date')
                ->where('products_histories.bakehouse_id', $bakehouse_id)
                ->where('products.uuid', $uuid)
                ->where('products_histories.is_deleted', 0)
                ->get();

        return $query;
    }
}
