<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Repositories\DeliveryRepository;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    private $deliveryRepository;

    public function __construct(DeliveryRepository $deliveryRepository) {

        $this->deliveryRepository = $deliveryRepository;
    }

    public function index()
    {
        $resp = $this->deliveryRepository->delivery_list();

        return response()->json(['data' => $resp]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $resp = $this->deliveryRepository->delivery_store($request);

        return response()->json(['data' => $resp]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $resp = $this->deliveryRepository->delivery_view($uuid);

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
        $res= $this->deliveryRepository->delivery_delete($id);

        return response()->json(['data' => $res]);
    }

    public function deliveryValidate(String $id){

        $resp = $this->deliveryRepository->delivery_validate($id);

        return response()->json(['data' => $resp]);
    }

    public function delivery_by_date()  {

        $resp = $this->deliveryRepository->delivery_by_date();

        return response()->json(['data' => $resp]);
    }

    public function delivery_by_livreurs()  {

        $resp = $this->deliveryRepository->delivery_by_livreur();

        return response()->json(['data' => $resp]);
    }

    public function export_livraison_liste_pdf()
    {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $data = Delivery::where('bakehouse_id', $bakehouse_id)
                            ->where('is_deleted', 0)
                            ->with(['delivery_person'])
                            ->get();

        $pdf = PDF::loadView('pdf.livraisons',["items"=>$data]);
        // dd($pdf);
        $headers = [
            'Content-Type' => 'application/pdf',
        ];
        return $pdf->download('livraison_liste.pdf',$headers);
    }

    public function export_delivery_pdf($uuid)
    {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $data = Delivery::where('uuid', $uuid)
                            ->where('bakehouse_id', $bakehouse_id)
                            ->with(['delivery_details.product','delivery_person'])
                            ->first();

        $pdf = PDF::loadView('pdf.livraison_detail',["items"=>$data]);
        // dd($pdf);
        $headers = [
            'Content-Type' => 'application/pdf',
        ];
        return $pdf->download('livraisons_detail.pdf',$headers);
    }
}
