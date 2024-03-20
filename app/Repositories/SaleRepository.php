<?php

namespace App\Repositories;

use App\Models\ProductHistory;
use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\StockProduct;
use App\Models\StockProduction;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

            ProductHistory::create([
                'quantity' => $item->quantity,
                'price' => $item->sub_total,
                'type' => 0, // sale,
                'bakehouse_id' => $bakehouse_id,
                'product_id' => $item->product_id,
                'added_by' => Auth::user()->id,
                'add_ip' => $this->getIp(),
            ]);
        }

        return $sale;
    }

    public function saleView($uuid)  {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        return Sale::where('bakehouse_id', $bakehouse_id)
                ->where('uuid', $uuid)
                ->with(['auteur','sale_details.product'])
                ->first();
    }
}
