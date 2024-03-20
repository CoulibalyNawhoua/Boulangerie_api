<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\StockProduct;
use Illuminate\Http\Request;
use App\Models\DeliveryDetails;
use App\Repositories\Repository;
use Illuminate\Support\Facades\DB;
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
            if($request->input('status') == 1){
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
        if($delivery->status == 1){
            foreach ($deliveryProducts as $item) {

                $stockP = StockProduct::where('product_id', $item->product_id)->first();

                $stockP->increment('quantity', $item->quantity);
            }
        }
    }


    public function delivery_list()  {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $query = Delivery::where('bakehouse_id', $bakehouse_id)
                        ->where('is_deleted', 0)
                        ->with(['delivery_person'])
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

        // AMBEU 17/03/2024
        public function delivery_validate($id) {

            $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

            $delivery = Delivery::where('id', $id)
                    ->where('bakehouse_id', $bakehouse_id)
                    ->first();


                $delivery->update([
                    'deleted_by'=> Auth::user()->id,
                    'delete_date' => Carbon::now(),
                    'status'=>1,
                    'delete_ip' => $this->getIp()
                ]);

            $deliveryProducts = DeliveryDetails::where('delivery_id', $delivery->id)->get();
            // dd($orderProducts);
            foreach ($deliveryProducts as $item) {

                $stockP = StockProduct::where('product_id', $item->product_id)->first();

                $stockP->decrement('quantity', $item->quantity);

                if ($stockP->quantity < 0) {
                    $stockP->update([
                        'quantity' => 0
                    ]);
                }
            }

        }


        public function delivery_by_date() {

            $bakehouse_id = (Auth::user()->bakehouse)? Auth::user()->bakehouse->id : NULL ;

            $query = DeliveryDetails::selectRaw('SUM(delivery_details.quantity) as total_quantity, products.name, products.image, DATE(deliveries.created_at)')
                                    ->leftJoin('deliveries', 'deliveries.id', '=', 'delivery_details.delivery_id')
                                    ->leftJoin('products', 'products.id', '=', 'delivery_details.product_id')
                                    ->where('deliveries.delivery_person_id', Auth::user()->id)
                                    // ->where(DB::raw("(DATE_FORMAT(deliveries.created_at,'%Y-%m-%d'))"), Carbon::now()->format('Y-m-d'))
                                    ->whereRaw("DATE(deliveries.created_at) = CURDATE()")
                                    ->groupBy('products.id', 'products.name', 'products.image','DATE(deliveries.created_at)')
                                    ->get();

            return $query;
        }

        public function delivery_history() {

        }

}
