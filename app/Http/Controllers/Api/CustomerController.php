<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CustomerRepository;

class CustomerController extends Controller
{
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository) {
        $this->customerRepository = $customerRepository;
    }
    public function index()
    {
        $resp = $this->customerRepository->list_customer();

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


        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $resp = $this->customerRepository->create($request->all());

        return response()->json(['data'=>$resp]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $resp = $this->customerRepository->findByUuid($uuid);

        return response()->json(['data'=>$resp]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $resp = $this->customerRepository->edit($id);

        return response()->json(['data'=>$resp]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $resp = $this->customerRepository->update($request->all(), $id);

        return response()->json(['data'=>$resp]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resp = $this->customerRepository->delete($id);

        return response()->json(['data'=>$resp]);
    }


    public function select_customer_by_bakehouse() {
        
        $resp = $this->customerRepository->selectCustomerByBakehouse();

        return response()->json(['data'=>$resp]);
    }
}
