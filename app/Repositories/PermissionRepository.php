<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\StorePermissionRequest;



class PermissionRepository extends Repository
{
    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    public function permission_destroy($id)
    {
        return $this->model->find($id)->delete();
    }


    public function permission_update(Request $request, $id)
    {
        $permissions = $request->permissions;

        $user = User::find($id);

        $user->syncPermissions($permissions);
    }

    public function permission_store(Request $request)
    {
        Permission::create([
            'name'=> $request->name
        ]);
    }

    public function permission_view($id)
    {
        return $this->model->find($id);
    }

}

