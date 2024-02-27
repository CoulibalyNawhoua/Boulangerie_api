<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Procurement;
use App\Models\StockProduct;
use Illuminate\Http\Request;
use App\Models\ProductHistory;
use App\Repositories\Repository;
use App\Models\ProcurementDetails;
use Illuminate\Support\Facades\Auth;

class ProcurementRepository extends Repository
{
    public function __construct(Procurement $model)
    {
        $this->model = $model;
    }

    public function procurementList() {

       return  Procurement::where('is_deleted', 0)->get();
    }

    public function procurementView($uuid)
    {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        return Procurement::where('procurements.uuid', $uuid)
                            ->where('procurements.bakehouse_id', $bakehouse_id)
                            ->with(['procurement_details.product','supplier'])->first();
    }

    public function procurementStore(Request $request)  {


        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $procurementItems = $request->input('procurement_items');

        $procurementdata["supplier_id"] = $request->input("supplier_id");
        $procurementdata["discount_amount"] = $request->input("totaldiscount");
        $procurementdata["tax_amount"] = $request->input("totaltax");
        $procurementdata["subtotal_amount"] = $request->input("subtotal");
        $procurementdata["delivery_date"] = $request->input('delivery_date');
        $procurementdata["total_amount"] = $request->input("total");
        $procurementdata["reference"] = $this->referenceGenerator('Procurement');
        $procurementdata["bakehouse_id"] = $bakehouse_id;
        $procurementdata["status"] = $request->input("status");// 0 en attente et 1 livré
        $procurementdata["added_by"] = Auth::user()->id;
        $procurementdata["add_ip"] = $this->getIp();

        $procurement = Procurement::create($procurementdata);

        foreach (json_decode($procurementItems) as $item) {

            $itemdata['product_id'] = $item->product_id;
            $itemdata['procurement_id'] = $procurement->id;
            $itemdata['quantity'] = $item->quantity;
            $itemdata['unit_price'] = $item->unit_price;
            $itemdata['product_tax'] = $item->product_tax;
            $itemdata['product_discount'] = $item->product_discount;

            ProcurementDetails::create($itemdata);

            if ($request->input("status") == 1) {

                $stockP = StockProduct::where('product_id', $item->product_id)
                            ->where('bakehouse_id', $bakehouse_id)
                            ->first();

                if (is_null($stockP)) {
                    StockProduct::create([
                        'product_id' => $item->quantity,
                        'bakehouse_id' => $bakehouse_id
                    ]);
                } else {

                    $stockP->increment('quantity', $item->quantity);
                }
            }
        }

        return $procurement;
    }

    public function  procurementUpdate(Request $request, $uuid) {


        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;


        $procurement = $this->model->where('uuid', $uuid)->first();


        $supplier_id = $request->supplier_id;
        $subtotal_amount = $request->subtotal ;
        $total_amount = $request->total ;
        $totaldiscount = $request->totaldiscount ;
        $totaltax = $request->totaltax ;
        $edited_by = Auth::user()->id ;
        $status = $request->status;
        $edit_date = Carbon::now();
        $edit_ip = $this->getIp();


        $procurement->update([
            'supplier_id'=> $supplier_id,
            'discount_amount'=> $totaldiscount,
            'tax_amount'=> $totaltax,
            'subtotal_amount'=> $subtotal_amount,
            'total_amount'=> $total_amount,
            'edited_by'=> $edited_by,
            'edit_date'=> $edit_date,
            'status' => $status,
            'edit_ip' => $edit_ip
        ]);


        $procurementItems = $request->input('procurement_items');

        $procurement->procurement_details()->delete();


        foreach (json_decode($procurementItems) as $item) {

            $itemdata['product_id'] = $item->product_id;
            $itemdata['procurement_id'] = $procurement->id;
            $itemdata['quantity'] = $item->quantity;
            $itemdata['unit_price'] = $item->unit_price;
            $itemdata['product_tax'] = $item->product_tax;
            $itemdata['product_discount'] = $item->product_discount;
            $itemdata['sub_total'] = $item->sub_total;
            if ($status == 1) {

                $stockP = StockProduct::where('product_id', $item->product_id)
                            ->where('bakehouse_id', $bakehouse_id)
                            ->first();

                if (is_null($stockP)) {
                    StockProduct::create([
                        'product_id' => $item->quantity,
                        'bakehouse_id' => $bakehouse_id
                    ]);
                } else {

                    $stockP->increment('quantity', $item->quantity);
                }

                ProductHistory::create([
                    'quantity' => $item->quantity,
                    'price' => $item->sub_total,
                    'type' => 1, // procurement,
                    'bakehouse_id' => $bakehouse_id,
                    'product_id' => $item->product_id,
                    'added_by' => Auth::user()->id,
                    'add_ip' => $this->getIp(),
                ]);
            }
        }

        return $procurement;

    }


}
