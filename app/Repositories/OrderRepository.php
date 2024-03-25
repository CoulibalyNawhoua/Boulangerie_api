<?php

namespace App\Repositories;


use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\StockProduct;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;

class OrderRepository extends Repository
{

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function order_store(Request $request)  {


        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $productItems = $request->input('product_items');

        $data["reference"] = $this->referenceGenerator('Order');
        $data["bakehouse_id"] = $bakehouse_id;
        $data["customer_id"]= $request->input('customer_id');
        $data["total_amount"] = $request->input('total_amount');
        $data["status"] = $request->input('status');
        $data["added_by"] = Auth::user()->id;
        $data["add_ip"] = $this->getIp();

        $order = Order::create($data);


        foreach (json_decode($productItems) as $item) {

            $itemdata['product_id'] = $item->product_id;
            $itemdata['order_id'] = $order->id;
            $itemdata['quantity'] = $item->quantity;
            $itemdata['price'] = $item->price;


            OrderDetails::create($itemdata);
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

        if($request->input('status') == 1){
            Transaction::create([
                "reference" => $this->referenceGenerator('Transaction'),
                "bakehouse_id" =>  $bakehouse_id,
                "customer_id" => $request->input('customer_id'),
                "total_amount" => $request->input('amount_versement'),
                "type_payment" => 0,
                "note" => "Commande versement",
                "status_paiement" => 1,
                "add_date" => Carbon::now(),
                "added_by" =>  Auth::user()->id,
                "add_ip" => $this->getIp()
            ]);
        }


        return $order;
    }


    public function order_delete($id) {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $order = Order::where('id', $id)
                    ->where('bakehouse_id', $bakehouse_id)
                    ->first();



        $order->update([
            'deleted_by'=> Auth::user()->id,
            'delete_date' => Carbon::now(),
            'is_deleted'=>1,
            'delete_ip' => $this->getIp()
        ]);
        if($order->status == 1){
            $orderProducts = OrderDetails::where('order_id', $order->id)->get();
            foreach ($orderProducts as $item) {

                $stockP = StockProduct::where('product_id', $item->product_id)->first();

                $stockP->increment('quantity', $item->quantity);
            }
        }



    }

    public function order_view($uuid) {

        $bakehouse_id = (Auth::user()->bakehouse)? Auth::user()->bakehouse->id : NULL ;

        $order = Order::where('uuid', $uuid)
                ->where('bakehouse_id', $bakehouse_id)
                ->with(['order_details.product','customer'])
                ->first();

        return $order;
    }

    public function order_list()  {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $query = Order::where('bakehouse_id', $bakehouse_id)
                        ->where('is_deleted', 0)
                        ->with(['customer'])
                        ->get();

        return $query;
    }

    // AMBEU 17/03/2024
    public function order_validate($id) {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $order = Order::where('id', $id)
                    ->where('bakehouse_id', $bakehouse_id)
                    ->first();



        $order->update([
            'deleted_by'=> Auth::user()->id,
            'delete_date' => Carbon::now(),
            'status' => 1,
            'delete_ip' => $this->getIp()
        ]);

        $orderProducts = OrderDetails::where('order_id', $order->id)->get();
        // dd($orderProducts);
        foreach ($orderProducts as $item) {

            $stockP = StockProduct::where('product_id', $item->product_id)->first();

            $stockP->decrement('quantity', $item->quantity);

            if ($stockP->quantity < 0) {
                $stockP->update([
                    'quantity' => 0
                ]);
            }
        }


        Transaction::create([
            "reference" => $this->referenceGenerator('Transaction'),
            "bakehouse_id" =>  $bakehouse_id,
            "customer_id" => $order->customer_id,
            "total_amount" => $order->total_amount,
            "type_payment" => 0,
            "note" => "Commande versement",
            "status_paiement" => 1,
            "add_date" => Carbon::now(),
            "added_by" =>  Auth::user()->id,
            "add_ip" => $this->getIp()
        ]);



    }


}
