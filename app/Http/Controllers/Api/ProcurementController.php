<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Procurement;
use App\Repositories\ProcurementRepository;
use PDF;
use Illuminate\Support\Facades\Auth;

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
        $resp = $this->procurementRepository->procurementView($uuid);

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

    public function export_procurements_pdf(string $uuid){
        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $data = Procurement::where('procurements.uuid', $uuid)
                            ->where('procurements.bakehouse_id', $bakehouse_id)
                            ->with(['procurement_details.product','supplier'])->first();

        $pdf = PDF::loadView('pdf.bondelivraison',["items"=>$data]);
        // dd($pdf);
        $headers = [
            'Content-Type' => 'application/pdf',
         ];
        return $pdf->download('achats.pdf',$headers);
    }
}
