<?php

namespace App\Repositories;


use Carbon\Carbon;
use App\Models\Ajustement;
use App\Models\StockProduct;
use Illuminate\Http\Request;
use App\Models\AjustementDetails;
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
        
        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $query = Ajustement::where('bakehouse_id', $bakehouse_id)
                        ->where('is_deleted', 0)
                        ->get();

        return $query;
    }

    public function ajustement_delete($id) {
        

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $ajustement = Ajustement::where('id', $id)
                    ->where('bakehouse_id', $bakehouse_id)
                    ->first();

        $ajustementProducts = AjustementDetails::where('ajustement_id', $ajustement->id)->get();

        $ajustement->update([
            'deleted_by'=> Auth::user()->id,
            'delete_date' => Carbon::now(),
            'is_deleted'=>1,
            'delete_ip' => $this->getIp()
        ]);

        foreach ($ajustementProducts as $item) {
            
            $stockP = StockProduct::where('product_id', $item->product_id)->first();

            $stockP->increment('quantity', $item->after_quantity);
        }
    }


    public function ajustement_view($uuid) {
        
        $bakehouse_id = (Auth::user()->bakehouse)? Auth::user()->bakehouse->id : NULL ;

        $ajustement = Ajustement::where('uuid', $uuid)
                ->where('bakehouse_id', $bakehouse_id)
                ->with(['ajustement_details.product'])
                ->first();

        return $ajustement;
    }
}
