<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\SupplierRepository;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    private $supplierRepository;

    public function __construct(SupplierRepository $supplierRepository) {

        $this->supplierRepository = $supplierRepository;

    }

    public function index()
    {
        $resp = $this->supplierRepository->list_supplier();

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
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $resp = $this->supplierRepository->create($request->all());

        return response()->json(['data'=>$resp]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $resp = $this->supplierRepository->findByUuid($uuid);

        return response()->json(['data'=>$resp]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $resp = $this->supplierRepository->edit($id);

        return response()->json(['data'=>$resp]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $resp = $this->supplierRepository->update($request->all(), $id);

        return response()->json(['data'=>$resp]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resp = $this->supplierRepository->delete($id);

        return response()->json(['data'=>$resp]);
    }
}
