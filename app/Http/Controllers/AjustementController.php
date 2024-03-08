<?php

namespace App\Http\Controllers;

use App\Repositories\AjustementRepository;
use Illuminate\Http\Request;

class AjustementController extends Controller
{

    private $ajustementRepository;

    public function __construct(AjustementRepository $ajustementRepository)
    {
        $this->ajustementRepository = $ajustementRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resp = $this->ajustementRepository->ajustement_list();

        return response()->json(['data' => $resp]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $resp = $this->ajustementRepository->ajustement_store($request);

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
}
