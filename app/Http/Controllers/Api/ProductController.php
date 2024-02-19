<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    private $productRepository;

    public function __construct(ProductRepository $productRepository) {

        $this->productRepository = $productRepository;

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resp = $this->productRepository->productList();

        return response()->json(['data' => $resp]);
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
        $resp = $this->productRepository->productStore($request);

        return response()->json(['data' => $resp]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $resp = $this->productRepository->findByUuid($uuid);

        return response()->json(['data' => $resp]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $resp = $this->productRepository->edit($id);

        return response()->json(['data' => $resp]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $resp = $this->productRepository->productUpdate($request, $uuid);

        return response()->json(['data' => $resp]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resp = $this->productRepository->delete($id);

        return response()->json(['data' => $resp]);
    }
}
