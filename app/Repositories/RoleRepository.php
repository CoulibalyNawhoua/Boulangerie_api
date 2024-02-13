<?php

namespace App\Repositories;

use App\Http\Requests\StoreRoleRequest;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleRepository extends Repository
{

    public function __construct(Role $model)
    {
        $this->model = $model;
    }


    public function role_store(StoreRoleRequest $request)
    {


        $role = $this->model->create([
            'name'=>$request->name
        ]);

        $role->syncPermissions($request->permissions);
    }


    public function role_update(StoreRoleRequest $request, $id)
    {
        $role = $this->model->find($id);

        $role->name = $request->name;
        $role->save();
        $role->syncPermissions($request->permissions);

        return $role;

    }


    public function destroy($id)
    {
        return $this->model->find($id)->delete();
    }

    public function getPermissionList()
    {
        return Permission::all()->pluck('name','id');
    }

    public function listeRole()
    {
        return Role::all();
    }
}
