<?php

namespace App\Repositories;

use App\Models\Customer;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\StockProduct;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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
                        ->orderByDesc('created_at')
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

    public function order_store_e_payement(Request $request)  {


        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $productItems = $request->input('product_items');

        $data["reference"] = $this->referenceGenerator('Order');
        $data["bakehouse_id"] = $bakehouse_id;
        $data["customer_id"]= $request->input('customer_id');
        $data["total_amount"] = $request->input('total_amount');
        $data["status"] = 0;
        $data["added_by"] = Auth::user()->id;
        $data["add_ip"] = $this->getIp();

        $order = Order::create($data);


        foreach (json_decode($productItems) as $item) {

            $itemdata['product_id'] = $item->product_id;
            $itemdata['order_id'] = $order->id;
            $itemdata['quantity'] = $item->quantity;
            $itemdata['price'] = $item->price;

            OrderDetails::create($itemdata);
        }

        $transaction = Transaction::create([
            "reference" => $this->referenceGenerator('Transaction'),
            "bakehouse_id" =>  $bakehouse_id,
            "customer_id" => $request->input('customer_id'),
            "total_amount" => $request->input('amount_versement'),
            "type_payment" => 0,
            "note" => "Paiement commande via imoney",
            "status_paiement" => 0,
            "add_date" => Carbon::now(),
            "added_by" =>  Auth::user()->id,
            "add_ip" => $this->getIp()
        ]);

        $client = Customer::find($request->input('customer_id'));

        $donnees = [
            'apiKey' => '262425053964adkcz02q1x.7323710',
            'site_id' => "6076583",
            'transaction_id' => $transaction->reference,
            'amount' => $request->total_amount,
            'description' => "TID: ".$transaction->reference." paiement de la BG: ".Auth::user()->bakehouse->name."",
            'customer_id'=>$client->id,
            'customer_name'=>$client->first_name,
            'customer_surname'=>$client->last_name,
            'customer_phone_number'=>$client->phone,
            "distri_seller_name" => Auth::user()->first_name." ".Auth::user()->last_name,
            "distri_seller_id" => Auth::user()->id,
            "plateforme_name" => "Boulangerie-App",

        ];


        $response = Http::post('https://distripay-sanbox-api.distriforce.shop/api/distriforce-check/payment',$donnees);
        $contenu =  $response->json();

        if($contenu['code'] == '201'){

            $data = [
                "payment_url"=>$contenu["data"]["payment_url"],
                "transaction_id" => $transaction->reference,
                "order_id" => $order->id,
            ];

            return $data;

        }else{
           return $contenu;
        }
    }

    public function order_update_e_payement(Request $request){
        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;
        $transaction_id = $request->input('transaction_id');
        $order_id = $request->input('order_id');

        $dataVerify = [
            'apiKey' => '262425053964adkcz02q1x.7323710',
            'site_id' => "6076583",
            "transaction_id" =>$transaction_id
        ];

        $response = Http::post("https://distripay-sanbox-api.distriforce.shop/api/check/verify",$dataVerify);
        $contenu = $response->json();
        // return $transaction_id;
        if($contenu["code"] == "00"){

            $transaction = Transaction::where('reference',$transaction_id)->first();
            $param['status_paiement'] = 1;
            $param["edited_by"] = Auth::user()->id;
            $param["edit_ip"] = $this->getIp();
            $param["edit_date"] = Carbon::now();

            $transaction->update($param);

            $order = Order::where('id', $order_id)
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

                $stockP->increment('quantity', $item->quantity);

                if ($stockP->quantity < 0) {
                    $stockP->update([
                        'quantity' => 0
                    ]);
                }
            }

            return 'OK';
        }else if($contenu["code"]== "600" || $contenu["code"] == "602" || $contenu["code"] == "604" || $contenu["code"] == "625" || $contenu["code"] == "627"){

            $order = Order::where('id', $order_id)
                            ->where('bakehouse_id', $bakehouse_id)
                                ->first();

            $order->update([
                'deleted_by'=> Auth::user()->id,
                'delete_date' => Carbon::now(),
                'is_deleted'=>1,
                'delete_ip' => $this->getIp()
            ]);

            return 'ECHEC';
        }else if($contenu["code"] == "623"){
            return 'PENDING';
        }else{
            return 'NO';
        }

    }



}
