<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\Delivery;
use App\Models\OrderReturn;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Repository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TransactionRepository extends Repository
{
    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }

    public function list_versement_delivery(){
        $bakehouse_id = (Auth::user()->bakehouse)? Auth::user()->bakehouse->id : NULL ;

        $versement = User::where('users.bakehouse_id', $bakehouse_id)
                            ->withSum([
                                'retours' => function ($query) {
                                    $query->select(DB::raw('SUM(total_amount)'));
                                },
                                'livraisons' => function ($query) {
                                    $query->where('status', 1)
                                        ->select(DB::raw('SUM(total_amount)'));
                                },
                                'transactions' => function ($query) {
                                    $query->where('status_paiement', 1)
                                    ->select(DB::raw('SUM(total_amount)'));
                                }
                            ], 'total_amount')
                            ->whereHas('roles', function ($query) {
                                $query->where('name', 'livreur');
                            })
                    ->get();

        return $versement;
    }

    public function list_versement_customers(){
        $bakehouse_id = (Auth::user()->bakehouse)? Auth::user()->bakehouse->id : NULL ;

        $versement = Customer::where('customers.bakehouse_id', $bakehouse_id)
                            ->withSum([
                                'orders' => function ($query) {
                                    $query->where('status', 1)
                                        ->select(DB::raw('SUM(total_amount)'));
                                },
                                'transactions' => function ($query) {
                                    $query->where('status_paiement', 1)
                                    ->select(DB::raw('SUM(total_amount)'));
                                }
                            ], 'total_amount')

                    ->get();

        return $versement;
    }

    public function view_versement_delivery($id){
        $bakehouse_id = (Auth::user()->bakehouse)? Auth::user()->bakehouse->id : NULL ;

        $versement = User::where('users.bakehouse_id', $bakehouse_id)
                            ->where('id',$id)
                            ->withSum([
                                'retours' => function ($query) {
                                    $query->select(DB::raw('SUM(total_amount)'));
                                },
                                'livraisons' => function ($query) {
                                    $query->where('status', 1)
                                        ->select(DB::raw('SUM(total_amount)'));
                                },
                                'transactions' => function ($query) {
                                    $query->where('status_paiement', 1)
                                                ->select(DB::raw('SUM(total_amount)'));
                                }
                            ], 'total_amount')
                            ->whereHas('roles', function ($query) {
                                $query->where('name', 'livreur');
                            })
                    ->first();

        return $versement;
    }

    public function views_versement_customers($id){
        $bakehouse_id = (Auth::user()->bakehouse)? Auth::user()->bakehouse->id : NULL ;

        $versement = Customer::where('customers.bakehouse_id', $bakehouse_id)
                            ->where('id',$id)
                            ->withSum([
                                'orders' => function ($query) {
                                    $query->where('status', 1)
                                        ->select(DB::raw('SUM(total_amount)'));
                                },
                                'transactions' => function ($query) {
                                    $query->where('status_paiement', 1)
                                    ->select(DB::raw('SUM(total_amount)'));
                                }
                            ], 'total_amount')

                    ->first();

        return $versement;
    }

    public function storeTransaction(Request $request){

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;
        $data["reference"] = $this->referenceGenerator('Transaction');
        $data["bakehouse_id"] = $bakehouse_id;
        $data["delivery_person_id"]= $request->input("delivery_person_id");
        $data["customer_id"]= $request->input("customer_id");
        $data["total_amount"] = $request->input('total_amount');
        $data["type_payment"] = $request->input('type_payment');
        $data["note"] = $request->input('note');
        $data["status_paiement"] = 1;
        $data["add_date"] = Carbon::now();
        $data["added_by"] = Auth::user()->id;
        $data["add_ip"] = $this->getIp();

        $transaction = Transaction::create($data);

        return $transaction;
    }

    public function transactionDeliveryView($id)
    {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        return Transaction::where('transactions.delivery_person_id', $id)
                            ->where('transactions.bakehouse_id', $bakehouse_id)
                            ->where('status_paiement',1)
                            ->with(['reception','livreur'])->get();
    }

    public function transactionCustomerView($id)
    {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        return Transaction::where('transactions.customer_id', $id)
                            ->where('transactions.bakehouse_id', $bakehouse_id)
                            ->where('status_paiement',1)
                            ->with(['reception','customer'])->get();
    }

    public function transaction_by_livreur() {


        $query = Transaction::selectRaw('total_amount,type_payment,created_at')
                                ->where('delivery_person_id', Auth::user()->id)
                                ->where('status_paiement', 1)
                                ->orderByDesc('created_at')
                                ->get();

        return $query;
    }

    public function reliquat_versement_delivery(){

        $orderReturnTotal = OrderReturn::where('delivery_person_id',Auth::user()->id)
                                ->sum('total_amount');

        $saleDeliveryTotal = Delivery::where('delivery_person_id',Auth::user()->id)
                                            ->where('status',1)
                                            ->where('is_deleted',0)
                                            ->sum('total_amount');

        $trasactionTotal = Transaction::where('delivery_person_id',Auth::user()->id)
                                            ->where('status_paiement',1)
                                            ->sum('total_amount');

        $dette = ($saleDeliveryTotal - $orderReturnTotal) - $trasactionTotal;

        return $dette;
    }


    public function transaction_by_livreur_recent() {

        $query = Transaction::selectRaw('total_amount,type_payment,created_at')
                                ->where('delivery_person_id', Auth::user()->id)
                                ->where('status_paiement', 1)
                                ->orderByDesc('created_at')
                                ->limit(15)
                                ->get();

        return $query;

    }

    public function create_transaction_mobile(Request $request){
        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;
        $user = Auth::user();

        $data["reference"] = $this->referenceGenerator('Transaction');
        $data["bakehouse_id"] = $bakehouse_id;
        $data["delivery_person_id"]= $user->id;
        $data["total_amount"] = $request->input('total_amount');
        $data["type_payment"] = 1;
        $data["note"] = "Paiement via imoney";
        $data["status_paiement"] = 0;
        $data["add_date"] = Carbon::now();
        $data["added_by"] = Auth::user()->id;
        $data["add_ip"] = $this->getIp();

        $transaction = Transaction::create($data);

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
                "transaction_id" => $transaction->reference
            ];

            return $data;

        }else{
           return $contenu;
        }
    }


    public function update_transaction_mobile(Request $request){
        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;
        $transaction_id = $request->input('transaction_id');

        $transaction = Transaction::where('reference',$transaction_id)->first();
        $param['status_paiement'] = 1;
        $param["edited_by"] = Auth::user()->id;
        $param["edit_ip"] = $this->getIp();
        $param["edit_date"] = Carbon::now();

        return $transaction->update($param);
    }

}
