<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\TechnicalSheetRepository;

class TechnicalSheetController extends Controller
{
    private $technicalSheetRepository;

    public function __construct(TechnicalSheetRepository $technicalSheetRepository) {

        $this->technicalSheetRepository = $technicalSheetRepository;

    }

    public function index()
    {
        $resp = $this->technicalSheetRepository->technicalSheetList();

        return response()->json(['data' => $resp]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $resp = $this->technicalSheetRepository->technicalSheetStore($request);

        return response()->json(['data' => $resp]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $resp = $this->technicalSheetRepository->technicalSheetView($uuid);

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
        //
    }
}
