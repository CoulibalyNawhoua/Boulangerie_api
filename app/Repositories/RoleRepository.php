<?php

namespace App\Repositories;

use App\Http\Requests\StoreRoleRequest;
use Illuminate\Http\Request;
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

        $role->syncPermissions($request->permissions);
    }


    public function role_update(Request $request, $id)
    {
        $role = $this->model->find($id);

        $role->name = $request->name;
        $role->save();
        $role->syncPermissions($request->permissions);

        return $role;

    }


    public function role_view($id)
    {

        $role = $this->model->find($id);

        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        $data['role'] = $role;

        $data['rolePermission'] = $rolePermissions;

        return $data;
        return $this->model->find($id);
    }


    public function role_destroy($id)
    {
        return $this->model->find($id)->delete();
    }


}
