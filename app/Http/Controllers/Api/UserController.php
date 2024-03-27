<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository) {

        $this->userRepository = $userRepository;

    }

    public function index()
    {
        $resp = $this->userRepository->all();

        return response()->json(['data'=>$resp]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $resp = $this->userRepository->storeAborne($request);

        return response()->json(['data'=> 'okey']);
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

    public function select_delivery_person_bakehouse() {

        $resp = $this->userRepository->select_delivery_person_bakehouse();

        return response()->json(['data'=>$resp]);
    }

    public function select_abonnes() {

        $resp = $this->userRepository->listUsers();

        return response()->json(['data'=>$resp]);
    }

    public function store_select_delivery(Request $request) {

        $resp = $this->userRepository->storeUser($request);

        return response()->json(['data'=>$resp]);
    }
}
