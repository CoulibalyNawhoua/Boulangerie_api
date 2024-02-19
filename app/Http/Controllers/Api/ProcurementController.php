<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ProcurementRepository;

class ProcurementController extends Controller
{

    private $procurementRepository;

    public function __construct(ProcurementRepository $procurementRepository) {

        $this->procurementRepository = $procurementRepository;

    }
    public function procurementIndex()
    {
        $resp = $this->procurementRepository->procurementList();

        return response()->json(['data' => $resp]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function procurementStore(Request $request)
    {
        $resp = $this->procurementRepository->procurementStore($request);

        return response()->json(['data'=> $resp]);
    }

    /**
     * Display the specified resource.
     */
    public function procurementShow(string $id)
    {
        $resp = $this->procurementRepository->view($id);

        return response()->json(['data' => $resp]);
    }

    public function procurementView(string $uuid)
    {
        $resp = $this->procurementRepository->findByUuid($uuid);

        return response()->json(['data' => $resp]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function procurementUpdate(Request $request, string $uuid)
    {
        $resp = $this->procurementRepository->procurementUpdate($request, $uuid);

        return response()->json(['data'=> $resp]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function procurementDestroy(string $id)
    {

        $resp = $this->procurementRepository->delete($id);

        return response()->json(['data' => $resp]);
    }
}
