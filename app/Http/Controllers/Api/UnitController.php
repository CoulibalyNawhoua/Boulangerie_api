<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UnitRepository;
use App\Repositories\UniteRepository;

class UnitController extends Controller
{
    private $unitRepository;

    public function __construct(UnitRepository $unitRepository) {

        $this->unitRepository = $unitRepository;

    }

    public function index()
    {
        $resp = $this->unitRepository->List_units();

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
            'name' => 'required',
        ]);

        $resp = $this->unitRepository->create($request->all());

        return response()->json(['data'=>$resp]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $resp = $this->unitRepository->findByUuid($uuid);

        return response()->json(['data' => $resp]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $resp = $this->unitRepository->edit($id);

        return response()->json(['data' => $resp]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $resp = $this->unitRepository->update($request->all(), $id);

        return response()->json(['data'=>$resp]);
    }


    public function select_unit()
    {
        $resp = $this->unitRepository->selectUnit();

        return response()->json(['data' => $resp]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resp = $this->unitRepository->delete($id);

        return response()->json(['data' => $resp]);
    }
}
