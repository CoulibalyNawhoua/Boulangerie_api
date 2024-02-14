<?php

namespace App\Repositories;


use Carbon\Carbon;
use App\Models\Reception;
use App\Models\Procurement;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;



class ReceptionRepository extends Repository
{
    public function __construct(Reception $model)
    {
        $this->model = $model;
    }

    public function storeProcurementReceipt(Request $request) {

        $quantity_received = $request->quantity_received;
        $quantity= $request->quantity;
        $purchase_price = $request->purchase_price;
        $total_amount = $request->total_amount;
        $invoice_reference = $request->invoice_reference;
        $total_receipt_price = $request->total_receipt_price;
        $products = $request->products;
        $mark_received = $request->mark_received;
        $procurement_id = $request->procurement_id;
        $product_unit = $request->product_unit;
        $product_count = $request->product_count;

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $procurement =  Procurement::where('id', $procurement_id)->firstOrFail();

        $reception = Reception::create([
            'reference'=>  $this->referenceGenerator('Reception'),
            'total_amount' => $total_amount,
            'procurement_id' => $procurement->id,
            'added_by' => Auth::user()->id,
            'add_date' => Carbon::now(),
            'add_ip' => $this->getIp(),
            'bakehouse_id' => $bakehouse_id,
        ]);

        $procurementItems = $request->input('procurement_items');

        foreach (json_decode($procurementItems) as $item) {

            $itemdata['product_id'] = $item->product_id;
            $itemdata['procurement_id'] = $procurement->id;
            $itemdata['quantity'] = $item->quantity;
            $itemdata['unit_price'] = $item->unit_price;
            $itemdata['product_tax'] = $item->product_tax;
            $itemdata['product_discount'] = $item->product_discount;
        }

    }

}
