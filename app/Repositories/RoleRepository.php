<?php

namespace App\Repositories;

use App\Http\Requests\StoreRoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleRepository extends Repository
{

    public function __construct(Role $model)
    {
        $this->model = $model;
    }


    public function role_store(Request $request)
    {

        $role = $this->model->create([
            'name'=>$request->name
        ]);

        $role->syncPermissions(explode(',',$request->permissions));
    }


    public function role_update(Request $request, $id)
    {
        $role = $this->model->find($id);

        $role->name = $request->name;
        $role->save();
        $role->syncPermissions(explode(',',$request->permissions));

        return $role;

    }


    public function role_view($id)
    {

        $role = Role::findById($id);

        if (!$role) {
            return response()->json(['error' => 'Role introuvable'], 404);
        }

        $rolePermissions = $role->permissions->pluck('name');

        $data['role'] = $role;
        $data['rolePermission'] = $rolePermissions;

        return $data;
    }


    public function role_destroy($id)
    {
        return $this->model->find($id)->delete();
    }


}
