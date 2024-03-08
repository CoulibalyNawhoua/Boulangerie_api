<?php

namespace App\Repositories;


use App\Models\Ajustement;
use Illuminate\Http\Request;
use App\Models\AjustementDetails;
use App\Models\StockProduct;
use Illuminate\Support\Facades\Auth;

class AjustementRepository extends Repository
{
   public function __construct(Ajustement $model)
   {
        $this->model = $model;
   }

   public function ajustement_store(Request $request)  {


        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $productItems = $request->input('product_items');

        $data["reference"] = $this->referenceGenerator('Ajustement');
        $data["bakehouse_id"] = $bakehouse_id;
        $data["added_by"] = Auth::user()->id;
        $data["add_ip"] = $this->getIp();

        $ajustement = Ajustement::create($data);

        foreach (json_decode($productItems) as $item) {

            $itemdata['product_id'] = $item->product_id;
            $itemdata['before_quantity'] = $item->before_quantity;
            $itemdata['after_quantity'] = $item->after_quantity;
            $itemdata['ajustement_id'] = $ajustement->id;

            AjustementDetails::create($itemdata);

            $stockP = StockProduct::where('product_id', $item->product_id)
                        ->where('bakehouse_id', $bakehouse_id)
                        ->first();
            if ($stockP) {
                $stockP->update('quantity', $item->after_quantity);
            }
        }

        return $ajustement;
    }

    public function ajustement_list()  {
        
    }

}
