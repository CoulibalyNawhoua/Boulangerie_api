<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Sous_familleRepository;
use Illuminate\Http\Request;

class SousFamilleController extends Controller
{

    private $sous_familleRepository;

    public function __construct(Sous_familleRepository $sous_familleRepository) {
        $this->sous_familleRepository = $sous_familleRepository;
    }

    public function index()
    {
        $resp = $this->sous_familleRepository->list_sous_fammilles();

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

        $resp = $this->sous_familleRepository->create($request->all());

        return response()->json(['data' => $resp]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $resp = $this->sous_familleRepository->findByUuid($uuid);

        return response()->json(['data' => $resp]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $resp = $this->sous_familleRepository->view($id);

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
        
        $resp = $this->sous_familleRepository->update($request->all(), $id);

        return response()->json(['data' => $resp]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resp = $this->sous_familleRepository->delete($id);

        return response()->json(['data' => $resp]);
    }
}
