<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Procurement;
use App\Models\ProcurementDetails;
use App\Models\ProcurementProduct;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;

class ProcurementRepository extends Repository
{
    public function __construct(Procurement $model)
    {
        $this->model = $model;
    }

    public function store_procurement(Request $request)  {


        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $procurementitem = $request->input('procurement_items');

        $procurementdata["supplier_id"] = $request->input("supplier_id");
        $procurementdata["discount_amount"] = $request->input("totaldiscount");
        $procurementdata["tax_amount"] = $request->input("totaltax");
        $procurementdata["subtotal_amount"] = $request->input("subtotal");
        $procurementdata["total_amount"] = $request->input("total");
        $procurementdata["reference"] = $this->referenceGenerator('Procurement');
        $procurementdata["bakehouse_id"] = $bakehouse_id;
        $procurementdata["status"] = 0;
        $procurementdata["added_by"] = Auth::user()->id;
        $procurementdata["add_ip"] = $this->getIp();

        $procurement = Procurement::create($procurementdata);

        foreach (json_decode($procurementitem) as $item) {

            $itemdata['product_id'] = $item->product_id;
            $itemdata['procurement_id'] = $procurement->id;
            $itemdata['quantity'] = $item->quantity;
            $itemdata['unit_price'] = $item->unit_price;
            $itemdata['product_tax'] = $item->product_tax;
            $itemdata['product_discount'] = $item->product_discount;

            ProcurementDetails::create($itemdata);
        }


        return $procurement;
    }


}
