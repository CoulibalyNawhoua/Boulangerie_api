<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\StockProduct;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;


class ProductRepository extends Repository
{
   public function __construct(Product $model) {

        $this->model = $model;
   }

   public function productList() {

        return Product::where('is_deleted', 0)->get();
   }

   public function productStore(Request $request) {

    $name = $request->name;
    $stock_alert = $request->stock_alert;
    $unit_id = $request->unit_id;
    $price = $request->price;
    $cost = $request->cost;
    $quantity = $request->quantity;
    $sous_famille_id = $request->sous_famille_id;
    $type = $request->type; // 0 pour produit et 1 pour production

    $oldFile = '';
    $directory = 'produits';
    $fieldname = 'image';

    $data_file = $this->fileUpload($request, $fieldname, $directory, $oldFile);

    $image_url = $data_file;

    $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

    $product = Product::create([
        'name' => Str::of($name)->upper(),
        'stock_alert' => $stock_alert,
        'price' => $price,
        'cost' => $cost,
        'bakehouse_id' =>  $bakehouse_id,
        'image' => $image_url,
        'quantity' => $quantity,
        'sous_famille_id' => $sous_famille_id,
        'unit_id' => $unit_id,
        'added_by' => Auth::user()->id,
        'type' => $type,
        'add_ip' => $this->getIp(),
    ]);

    StockProduct::create([
        'quantity' => $quantity,
        'product_id' => $product->id,
        'bakehouse_id' => $bakehouse_id,
        'price' =>  $price,
        'type' => $type,
        'quantity' => $quantity,
    ]);

        return $product;
    }


    public function productUpdate(Request $request, $uuid) {


        $product = $this->model->where('uuid', $uuid)->first();

        $name = $request->name;
        $stock_alert = $request->stock_alert;
        $price = $request->price;
        // $cost = $request->cost;
        $quantity = $request->quantity;
        $unit_id = $request->unit_id;
        $type = $request->type; // 0 pour produit et 1 pour production
        $sous_famille_id = $request->sous_famille_id;

        $oldFile =  ($product->image) ? $product->image : '' ;
        $directory = 'produits';
        $fieldname = 'image';

        $data_file = $this->fileUpload($request, $fieldname, $directory, $oldFile);

        $image_url = $data_file;


        $product->update([
            'name' => Str::of($name)->upper(),
            'stock_alert' => $stock_alert,
            'price' => $price,
            // 'cost' => $cost,
            'unit_id' => $unit_id,
            'image' => $image_url,
            'sous_famille_id' => $sous_famille_id,
            'added_by' => Auth::user()->id,
            'type' => $type,
            'add_ip' => $this->getIp(),
        ]);

            return $product;
        }

    public function product_procurement()
    {
        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        return Product::selectRaw('products.*, units.name AS unit')
                        ->leftJoin('units', 'units.id', '=', 'products.unit_id')
                        ->where('products.is_deleted', 0)
                        ->where('products.type', 0)
                        ->where('products.bakehouse_id', $bakehouse_id)
                        ->get();
    }

    public function product_production()
    {
        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        return Product::selectRaw('products.*, units.name AS unit')
                ->leftJoin('units', 'units.id', '=', 'products.unit_id')
                ->where('products.is_deleted', 0)
                ->where('products.type', 1)
                ->where('products.bakehouse_id', $bakehouse_id)
                ->get();
    }


}
