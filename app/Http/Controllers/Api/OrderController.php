<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Repositories\OrderRepository;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository){

        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        $resp = $this->orderRepository->order_list();

        return response()->json(['data'=>$resp]);
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
        $resp = $this->orderRepository->order_store($request);

        return response()->json(['data' => $resp]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $resp = $this->orderRepository->order_view($uuid);

        return response()->json(['data' => $resp]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
        $resp = $this->orderRepository->order_delete($id);

        return response()->json(['data' => $resp]);
    }
    // AMBEU 17/03/2024
    public function orderValidate(String $id){

        $resp = $this->orderRepository->order_validate($id);

        return response()->json(['data' => $resp]);
    }

    public function export_commande_liste_pdf()
    {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $data = Order::where('bakehouse_id', $bakehouse_id)
                        ->where('is_deleted', 0)
                        ->with(['customer'])
                        ->get();

        $pdf = PDF::loadView('pdf.commande',["items"=>$data]);
        // dd($pdf);
        $headers = [
            'Content-Type' => 'application/pdf',
        ];
        return $pdf->download('commandes.pdf',$headers);
    }

    public function export_orders_pdf($uuid)
    {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $data = Order::where('uuid', $uuid)
                            ->where('bakehouse_id', $bakehouse_id)
                            ->with(['order_details.product','customer'])
                            ->first();

        $pdf = PDF::loadView('pdf.commande_detail',["items"=>$data]);
        // dd($pdf);
        $headers = [
            'Content-Type' => 'application/pdf',
        ];
        return $pdf->download('commandes_detail.pdf',$headers);
    }

    public function order_store_Epayement(Request $request){

        $resp = $this->orderRepository->order_store_e_payement($request);

        return response()->json(['data' => $resp]);
    }

    public function order_update_Epayement(Request $request){

        $resp = $this->orderRepository->order_update_e_payement($request);

        return response()->json(['data' => $resp]);
    }

}
