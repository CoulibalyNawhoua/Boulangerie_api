<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ProductionHistoryRepository;
use Illuminate\Http\Request;

class ProductionHistoryController extends Controller
{

    private $productionHistoryRepository;

    public function __construct(ProductionHistoryRepository $productionHistoryRepository)
    {
        $this->productionHistoryRepository = $productionHistoryRepository;
    }

    public function index()
    {
        $resp = $this->productionHistoryRepository->ProductionHistoryList();

        return response()->json(['data' => $resp]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $resp = $this->productionHistoryRepository->store($request);

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

    public function details_history_products(String $uuid)
    {
        $resp = $this->productionHistoryRepository->ProductionHistoryListDetail($uuid);

        return response()->json(['data' => $resp]);
    }

}
