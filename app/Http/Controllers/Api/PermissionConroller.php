<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\PermissionRepository;
use Illuminate\Http\Request;

class PermissionConroller extends Controller
{
    private $permissionRepository;

   public function __construct(PermissionRepository $permissionRepository)
   {
       $this->permissionRepository = $permissionRepository;
   }

    public function index()
    {
        $resp = $this->permissionRepository->all();

        return response()->json(['data'=>$resp]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $resp = $this->permissionRepository->permission_store($request);

        return response()->json(['data'=>'okey']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $resp = $this->permissionRepository->permission_view($id);

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

        $resp = $this->permissionRepository->permission_update($request, $id);

        return response()->json(['data' => $resp]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resp = $this->permissionRepository->permission_destroy($id);

        return response()->json(['data'=>'okey']);
    }

    public function  permissionSelect(Request $request)
    {
        $resp = $this->permissionRepository->liste_permission();

        return response()->json(['data'=> $resp]);
    }
}
