<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\OrderReturnRepository;
use Illuminate\Http\Request;

class OrderReturnController extends Controller
{
    private $orderReturnRepository;

    public function __construct(OrderReturnRepository $orderReturnRepository){

        $this->$orderReturnRepository = $orderReturnRepository;
    }

    public function index()
    {
        $resp = $this->orderReturnRepository->order_return_List();

        return response()->json(['data' => $resp]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $resp = $this->orderReturnRepository->order_return_store($request);

        return response()->json(['data' => $resp]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $resp = $this->orderReturnRepository->orderReturnView($uuid);

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
        //
    }
}
