<?php

namespace App\Repositories;

use App\Models\OrderReturn;
use App\Models\OrderReturnDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\StockProduct;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;

class OrderReturnRepository extends Repository
{
    public function __construct()
    {


    }


    public function order_return_List() {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

       return  OrderReturn::where('is_deleted', 0)
                ->where('bakehouse_id', $bakehouse_id)
                ->get();
    }


    public function order_return_store(Request $request)  {


        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $returnItems = $request->input('returnItems');
        $returndata["bakehouse_id"] = $bakehouse_id;
        $returndata["date"] = $request->input("date");
        $returndata["total_amount"] = $request->input("total_amount");
        $returndata["reference"] = $this->referenceGenerator('Order_return');
        $returndata["delivery_person_id"] = $request->input("delivery_person_id");
        $returndata["added_by"] = Auth::user()->id;
        $returndata["add_ip"] = $this->getIp();

        // dd($returndata);
        $return = OrderReturn::create($returndata);

        foreach (json_decode($returnItems) as $item) {

            // if ($item->in_stock) {
            //     $in_stock = 1;
            // } else {
            //     $in_stock = 0;
            // }

            $itemdata['product_id'] = $item->product_id;
            $itemdata['order_return_id'] = $return->id;
            $itemdata['quantity'] = $item->quantity;
            $itemdata['price'] = $item->price;
            $itemdata['in_stock'] = $item->in_stock;


            OrderReturnDetail::create($itemdata);


            if ($item->in_stock) {

                $stockP = StockProduct::where('product_id', $item->product_id)
                    ->where('bakehouse_id', $bakehouse_id)
                    ->first();

                    $stockP->increment('quantity', $item->quantity);

            }

        }

        return $return;
    }


    public function orderReturnView($uuid)
    {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        return OrderReturn::where('order_returns.uuid', $uuid)
                            ->where('order_returns.bakehouse_id', $bakehouse_id)
                            ->with(['order_return_details.product','livreur'])->first();
    }
}
