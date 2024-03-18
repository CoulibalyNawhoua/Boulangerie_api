<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\AjustementRepository;

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
    public function show(string $uuid)
    {
        $resp = $this->ajustementRepository->ajustement_view($uuid);

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
        $resp = $this->ajustementRepository->ajustement_delete($id);

        return response()->json(['data' => $resp]);
    }

    public function ajustements_details()  {
        
        $resp = $this->ajustementRepository->ajustement_details();

        return response()->json(['data' => $resp]);
    }
}
