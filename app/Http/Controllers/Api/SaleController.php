<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\SaleRepository;

class SaleController extends Controller
{

    private $saleRepository;

    public function __construct(SaleRepository $saleRepository) {
        $this->saleRepository = $saleRepository;
    }

    public function saleIndex()
    {
        $resp = $this->saleRepository->saleList();

        return response()->json(['data' => $resp]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function saleStore(Request $request)
    {
        $resp = $this->saleRepository->saleStore($request);

        return response()->json(['data' => $resp]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $resp = $this->saleRepository->saleView($uuid);

        return response()->json(['data' => $resp]);
    }

    public function saleView(string $uuid)
    {
        $resp = $this->saleRepository->saleView($uuid);

        return response()->json(['data' => $resp]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function saleUpdate(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function saleDestroy(string $id)
    {
        $resp = $this->saleRepository->delete($id);

        return response()->json(['data' => $resp]);
    }

    public function saleSum()
    {
        $resp = $this->saleRepository->saleUserToday();

        return response()->json(['data' => $resp]);
    }

    public function saleUserList()
    {
        $resp = $this->saleRepository->saleUserTodayList();

        return response()->json(['data' => $resp]);
    }


    public function sale_store_epayment(Request $request)
    {
        $resp = $this->saleRepository->saleStoreEpayment($request);

        return response()->json(['data' => $resp]);
    }

    public function sale_update_epayment(Request $request)
    {
        $resp = $this->saleRepository->Sale_update_e_payement($request);

        return response()->json(['data' => $resp]);
    }
}
