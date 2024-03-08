<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\DeliveryRepository;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    private $deliveryRepository;

    public function __construct(DeliveryRepository $deliveryRepository) {

        $this->deliveryRepository = $deliveryRepository;
    }

    public function index()
    {
        $resp = $this->deliveryRepository->delivery_list();

        return response()->json(['data' => $resp]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $resp = $this->deliveryRepository->delivery_store($request);

        return response()->json(['data' => $resp]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $resp = $this->deliveryRepository->delivery_view($uuid);

        return response()->json(['data' => $resp]);
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
        $res= $this->deliveryRepository->delivery_delete($id);
        
        return response()->json(['data' => $res]);
    }
}
