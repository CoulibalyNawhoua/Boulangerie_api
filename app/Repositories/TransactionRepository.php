<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Repository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                                    $query->select(DB::raw('SUM(total_amount)'));
                                }
                            ], 'total_amount')
                            ->whereHas('roles', function ($query) {
                                $query->where('name', 'livreur');
                            })
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
                                    $query->select(DB::raw('SUM(total_amount)'));
                                }
                            ], 'total_amount')
                            ->whereHas('roles', function ($query) {
                                $query->where('name', 'livreur');
                            })
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
        $saledata["add_date"] = Carbon::now();
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
                            ->with(['reception','livreur'])->get();
    }

    public function transactionCustomerView($id)
    {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        return Transaction::where('transactions.customer_id', $id)
                            ->where('transactions.bakehouse_id', $bakehouse_id)
                            ->with(['reception','customer'])->get();
    }
}
