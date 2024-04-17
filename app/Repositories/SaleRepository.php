<?php

namespace App\Repositories;

use App\Models\ProductHistory;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\StockProduct;
use App\Models\StockProduction;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SaleRepository extends Repository
{
    public function __construct(Sale $model)
    {
        $this->model = $model;
    }

    public function saleList() {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        return Sale::where('is_deleted', 0)
                ->where('bakehouse_id', $bakehouse_id)
                ->with('auteur')
                ->withCount('sale_details')
                ->orderByDesc('created_at')
                ->get();
    }

    public function saleStore(Request $request)  {


        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $saleItems = $request->input('sale_items');


        $due_amount = $request->total_amount - $request->paid_amount;

        if ($due_amount === $request->total_amount) {
            $payment_status = 0 ;// payment pending
        } elseif ($due_amount > 0) {
            $payment_status = 1; // payment partial;
        } else {
            $payment_status = 2; // payment paid
        }

        $saledata["customer_id"] = $request->input("customer_id");
        $saledata["paid_amount"] = $request->input("paid_amount");
        $saledata["total_amount"] = $request->input("total_amount");
        $saledata["due_amount"] = $due_amount;
        $saledata["balance"] = $request->input("balance");
        $saledata["reference"] = $this->referenceGenerator('Sale');
        $saledata["bakehouse_id"] = $bakehouse_id;
        $saledata["payment_status"] = $payment_status;
        $saledata["payment_date"] = Carbon::now();
        $saledata["added_by"] = Auth::user()->id;
        $saledata["add_ip"] = $this->getIp();

        $sale = Sale::create($saledata);

        foreach (json_decode($saleItems) as $item) {

            $itemdata['product_id'] = $item->product_id;
            $itemdata['sale_id'] = $sale->id;
            $itemdata['quantity'] = $item->quantity;
            $itemdata['unit_price'] = $item->unit_price;
            $itemdata['sub_total'] = $item->sub_total;

            SaleDetails::create($itemdata);

            $stockP = StockProduct::where('product_id', $item->product_id)
                        ->where('bakehouse_id', $bakehouse_id)
                        ->first();

            $stockP->decrement('quantity', $item->quantity);

            if ($stockP->quantity < 0) {

                $stockP->update([
                    'quantity' => 0
                ]);
            }

            // ProductHistory::create([
            //     'quantity' => $item->quantity,
            //     'price' => $item->sub_total,
            //     'type' => 0, // sale,
            //     'bakehouse_id' => $bakehouse_id,
            //     'product_id' => $item->product_id,
            //     'added_by' => Auth::user()->id,
            //     'add_ip' => $this->getIp(),
            // ]);


        }
        Transaction::create([
            "reference" => $this->referenceGenerator('Transaction'),
            "bakehouse_id" =>  $bakehouse_id,
            "total_amount" => $request->input('total_amount'),
            "type_payment" => 0,
            "note" => "vente caisse",
            "status_paiement" => 1,
            "add_date" => Carbon::now(),
            "added_by" =>  Auth::user()->id,
            "add_ip" => $this->getIp()
        ]);

        return $sale;
    }

    public function saleView($uuid)  {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        return Sale::where('bakehouse_id', $bakehouse_id)
                ->where('uuid', $uuid)
                ->with(['auteur','sale_details.product'])
                ->first();
    }

    public function saleUserToday()  {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        return Sale::where('bakehouse_id', $bakehouse_id)
                ->where('added_by', Auth::user()->id)
                ->whereDate('created_at', Carbon::now())
                ->where('is_deleted', 0)
                ->sum('total_amount');
    }

    public function saleUserTodayList()  {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        return Sale::where('bakehouse_id', $bakehouse_id)
                ->where('added_by', Auth::user()->id)
                ->whereDate('created_at', Carbon::now())
                ->with('auteur')
                ->where('is_deleted', 0)
                ->withCount('sale_details')
                ->orderByDesc('created_at')
                ->get();
    }

    public function saleStoreEpayment(Request $request)  {


        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $saleItems = $request->input('sale_items');


        $due_amount = $request->total_amount - $request->paid_amount;

        if ($due_amount === $request->total_amount) {
            $payment_status = 0 ;// payment pending
        } elseif ($due_amount > 0) {
            $payment_status = 1; // payment partial;
        } else {
            $payment_status = 2; // payment paid
        }

        $saledata["customer_id"] = $request->input("customer_id");
        $saledata["paid_amount"] = $request->input("paid_amount");
        $saledata["total_amount"] = $request->input("total_amount");
        $saledata["due_amount"] = $due_amount;
        $saledata["balance"] = $request->input("balance");
        $saledata["reference"] = $this->referenceGenerator('Sale');
        $saledata["bakehouse_id"] = $bakehouse_id;
        $saledata["payment_status"] = $payment_status;
        $saledata["payment_date"] = Carbon::now();
        $saledata["is_deleted"] = 1;
        $saledata["added_by"] = Auth::user()->id;
        $saledata["add_ip"] = $this->getIp();

        $sale = Sale::create($saledata);

        foreach (json_decode($saleItems) as $item) {

            $itemdata['product_id'] = $item->product_id;
            $itemdata['sale_id'] = $sale->id;
            $itemdata['quantity'] = $item->quantity;
            $itemdata['unit_price'] = $item->unit_price;
            $itemdata['sub_total'] = $item->sub_total;

            SaleDetails::create($itemdata);
        }
        $transaction = Transaction::create([
            "reference" => $this->referenceGenerator('Transaction'),
            "bakehouse_id" =>  $bakehouse_id,
            "total_amount" => $request->input('total_amount'),
            "type_payment" => 1,
            "note" => "vente caisse",
            "status_paiement" => 0,
            "add_date" => Carbon::now(),
            "added_by" =>  Auth::user()->id,
            "add_ip" => $this->getIp()
        ]);

        $donnees = [
            'apiKey' => '262425053964adkcz02q1x.7323710',
            'site_id' => "6076583",
            'transaction_id' => $transaction->reference,
            'amount' => $request->total_amount,
            'description' => "TID: ".$transaction->reference." paiement de la BG: ".Auth::user()->bakehouse->name."",
            'customer_id'=>Auth::user()->id,
            'customer_name'=>Auth::user()->first_name,
            'customer_surname'=>Auth::user()->last_name,
            'customer_phone_number'=>Auth::user()->phone,
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
                "sale_id" => $sale->id,
            ];

            return $data;

        }else{
           return $contenu;
        }
    }

    public function Sale_update_e_payement(Request $request){
        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;
        $transaction_id = $request->input('transaction_id');
        $sale_id = $request->input('sale_id');

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

            $sale = Sale::where('id', $sale_id)
                            ->where('bakehouse_id', $bakehouse_id)
                                ->first();

            $sale->update([
                'deleted_by'=> Auth::user()->id,
                'delete_date' => Carbon::now(),
                'is_deleted' => 0,
                'delete_ip' => $this->getIp()
            ]);

            $saleItems = SaleDetails::where('sale_id', $sale->id)->get();
            // dd($orderProducts);
            foreach ($saleItems as $item) {

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

            return 'OK';
        }else if($contenu["code"]== "600" || $contenu["code"] == "602" || $contenu["code"] == "604" || $contenu["code"] == "625" || $contenu["code"] == "627"){

            $sale = Sale::where('id', $sale_id)
                            ->where('bakehouse_id', $bakehouse_id)
                                ->first();

            $sale->update([
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
