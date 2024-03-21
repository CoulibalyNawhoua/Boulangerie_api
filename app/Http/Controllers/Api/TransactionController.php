<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    private $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository) {

        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeTransacts(Request $request)
    {
        $resp = $this->transactionRepository->storeTransaction($request);

        return response()->json(['data' => $resp]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function versement_delivery()
    {
        $resp = $this->transactionRepository->list_versement_delivery();

        return response()->json(['data'=>$resp]);
    }

    public function versement_customers()
    {
        $resp = $this->transactionRepository->list_versement_customers();

        return response()->json(['data'=>$resp]);
    }

    public function versement_delivery_view($id)
    {
        $resp = $this->transactionRepository->view_versement_delivery($id);

        return response()->json(['data'=>$resp]);
    }

    public function versement_customers_view($id)
    {
        $resp = $this->transactionRepository->views_versement_customers($id);

        return response()->json(['data'=>$resp]);
    }

    public function DeliveryView(string $id)
    {
        $resp = $this->transactionRepository->transactionDeliveryView($id);

        return response()->json(['data' => $resp]);
    }

    public function CustomersView(string $id)
    {
        $resp = $this->transactionRepository->transactionCustomerView($id);

        return response()->json(['data' => $resp]);
    }
}
