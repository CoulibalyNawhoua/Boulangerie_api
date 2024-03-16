<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Delivery;
use App\Models\StockProduct;
use Illuminate\Http\Request;
use App\Models\DeliveryDetails;
use App\Models\Order;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;

class DeliveryRepository extends Repository
{

    public function __construct(Delivery  $model)
    {
        $this->model = $model;
    }

    public function delivery_store(Request $request)  {


        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $productItems = $request->input('product_items');

        $data["reference"] = $this->referenceGenerator('Delivery');
        $data["bakehouse_id"] = $bakehouse_id;
        $data["delivery_person_id"]= $request->input('delivery_person_id');
        $data["total_amount"] = $request->input('total_amount');
        $data["status"] = $request->input('status');
        $data["added_by"] = Auth::user()->id;
        $data["add_ip"] = $this->getIp();

        $delivery = Delivery::create($data);

        foreach (json_decode($productItems) as $item) {

            $itemdata['product_id'] = $item->product_id;
            $itemdata['quantity'] = $item->quantity;
            $itemdata['delivery_id'] = $delivery->id;
            $itemdata['price'] = $item->price;


            DeliveryDetails::create($itemdata);

            $stockP = StockProduct::where('product_id', $item->product_id)
                        ->where('bakehouse_id', $bakehouse_id)
                        ->first();
            $stockP->decrement('quantity', $item->quantity);

            if ($stockP->quantity < 0) {
                $stockP->update([
                    'quantity' => 0
                ]);
            }

        }

        return $delivery;
    }


    public function delivery_delete($id) {


        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $delivery = Delivery::where('id', $id)
                    ->where('bakehouse_id', $bakehouse_id)
                    ->first();

        $deliveryProducts = DeliveryDetails::where('delivery_id', $delivery->id)->get();

        $delivery->update([
            'deleted_by'=> Auth::user()->id,
            'delete_date' => Carbon::now(),
            'is_deleted'=>1,
            'delete_ip' => $this->getIp()
        ]);

        foreach ($deliveryProducts as $item) {

            $stockP = StockProduct::where('product_id', $item->product_id)->first();

            $stockP->increment('quantity', $item->quantity);
        }
    }


    public function delivery_list()  {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $query = Delivery::where('bakehouse_id', $bakehouse_id)
                        ->where('is_deleted', 0)
                        ->get();

        return $query;
    }


    public function delivery_view($uuid) {

        $bakehouse_id = (Auth::user()->bakehouse)? Auth::user()->bakehouse->id : NULL ;

        $delivery = Delivery::where('uuid', $uuid)
                ->where('bakehouse_id', $bakehouse_id)
                ->with(['delivery_details.product','delivery_person'])
                ->first();

        return $delivery;
    }

}
