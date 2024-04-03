<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TechnicalSheet;
use App\Repositories\TechnicalSheetRepository;
use PDF;
use Illuminate\Support\Facades\Auth;

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

    public function export_technicalsheet_pdf($uuid)
    {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $data = TechnicalSheet::where('technical_sheet.uuid', $uuid)
                                ->where('technical_sheet.bakehouse_id', $bakehouse_id)
                                ->with(['technical_sheet_details.product', 'technical_sheet_details.unit'])
                                ->first();

        $pdf = PDF::loadView('pdf.detail_production',["items"=>$data]);
        // dd($pdf);
        $headers = [
            'Content-Type' => 'application/pdf',
        ];
        return $pdf->download('production.pdf',$headers);
    }
}
