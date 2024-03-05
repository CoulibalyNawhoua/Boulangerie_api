<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\StockProduct;
use Illuminate\Http\Request;
use App\Models\ProductHistory;
use App\Models\TechnicalSheet;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;
use App\Models\TechnicalSheetDetails;

class TechnicalSheetRepository extends Repository
{
    public function __construct(TechnicalSheet $model)
    {
        $this->model = $model;
    }

    public function technicalSheetList()  {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        return TechnicalSheet::where('is_deleted', 0)
            ->where('bakehouse_id', $bakehouse_id)
            ->get();
    }

    public function technicalSheetView($uuid)
    {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        return TechnicalSheet::where('technical_sheet.uuid', $uuid)
                            ->where('technical_sheet.bakehouse_id', $bakehouse_id)
                            ->with(['technical_sheet_details.product', 'technical_sheet_details.unit'])
                            ->first();
    }

    public function technicalSheetStore(Request $request)  {


        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $productItems = $request->input('product_details');

        $detailData["time"] = $request->input("time");
        $detailData["date"] = $request->input('date');
        $detailData["bakehouse_id"] = $bakehouse_id;
        $detailData["added_by"] = Auth::user()->id;
        $detailData["add_ip"] = $this->getIp();

        $data = TechnicalSheet::create($detailData);

        foreach (json_decode($productItems) as $item) {

            $itemdata['product_id'] = $item->product_id;
            $itemdata['technical_sheet_id'] = $data->id;
            $itemdata['quantity'] = $item->quantity;
            $itemdata['unit_id'] = $item->unit_id;

            TechnicalSheetDetails::create($itemdata);

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

        return $data;
    }
}
