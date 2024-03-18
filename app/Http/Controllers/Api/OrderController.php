<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository){

        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        $resp = $this->orderRepository->order_list();

        return response()->json(['data'=>$resp]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $resp = $this->orderRepository->order_store($request);

        return response()->json(['data' => $resp]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $resp = $this->orderRepository->order_view($uuid);

        return response()->json(['data' => $resp]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
        $resp = $this->orderRepository->order_delete($id);

        return response()->json(['data' => $resp]);
    }
    // AMBEU 17/03/2024
    public function orderValidate(String $id){

        $resp = $this->orderRepository->order_validate($id);

        return response()->json(['data' => $resp]);
    }
}
