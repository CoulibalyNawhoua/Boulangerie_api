<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\BakehouseRepository;
use Illuminate\Http\Request;

class BakehouseController extends Controller
{
    private $bakehouseRepository;

    public function __construct(BakehouseRepository $bakehouseRepository)
    {
        $this->bakehouseRepository = $bakehouseRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resp = $this->bakehouseRepository->all();

        return response()->json(['data'=> $resp]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $resp = $this->bakehouseRepository->create($request->all());

        return response()->json(['data'=>$resp]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $resp = $this->bakehouseRepository->findByUuid($uuid);

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
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $resp = $this->bakehouseRepository->update($request->all(), $id);

        return response()->json(['data'=>$resp]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function dashboardIndex(){
        $resp = $this->bakehouseRepository->dashboard_synthese_data();

        return response()->json(['data' => $resp]);
    }
}
