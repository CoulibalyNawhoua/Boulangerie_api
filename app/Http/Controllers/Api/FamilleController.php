<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\FamilleRepository;
use Illuminate\Http\Request;

class FamilleController extends Controller
{

    private $familleRepository;

    public function __construct(FamilleRepository $familleRepository)
    {
        $this->familleRepository = $familleRepository;
    }

    public function index()
    {
        $resp = $this->familleRepository->ListFamilles();

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

        $resp = $this->familleRepository->create($request->all());

        return response()->json(['data' => $resp]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $resp = $this->familleRepository->findByUuid($uuid);

        return response()->json(['data' => $resp]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,  $id)
    {
        $resp = $this->familleRepository->view($id);

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

        $resp = $this->familleRepository->update($request->all(), $id);

        return response()->json(['data' => $resp]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resp = $this->familleRepository->delete($id);

        return response()->json(['data' => $resp]);
    }
}
