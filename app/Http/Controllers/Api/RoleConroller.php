<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;

class RoleConroller extends Controller
{

    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository=$roleRepository;

    }
    public function index()
    {
        $resp = $this->roleRepository->all();

        return response()->json(['data'=> $resp]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
        ]);

        $resp = $this->roleRepository->role_store($request);

        return response()->json(['data'=> 'okey']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $resp = $this->roleRepository->role_view($id);

        return response()->json(['data'=> $resp]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $resp = $this->roleRepository->role_update($request, $id);

        return response()->json(['data'=> 'okey']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resp = $this->roleRepository->role_destroy($id);

        return response()->json(['data'=> 'okey']);
    }

    public function select_livreur_bakehouse()
    {
        $resp = $this->roleRepository->listUsersRoleHasLivreurByBakehouse();

        return response()->json(['data'=> $resp]);
    }
       
}
