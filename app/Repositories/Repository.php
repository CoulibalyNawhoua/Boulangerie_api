<?php

namespace App\Repositories;

use App\Core\Traits\GeneratedCodeTrait;
use Carbon\Carbon;
use App\Models\User;
use App\Core\Traits\Ip;
use App\Core\Traits\ImageTrait;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Repository implements RepositoryInterface
{
    use Ip, ImageTrait, GeneratedCodeTrait;
    // model property on class instances
    protected $model;

    // Constructor to bind model to repo
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    // Get all instances of model
    public function all()
    {
        return $this->model->all();
    }

    // create a new record in the database
    public function create(array $data)
    {
        $param = $data;
        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;
        $param["added_by"] = Auth::user()->id;
        if ($bakehouse_id) {
            $param["bakehouse_id"] =  $bakehouse_id;
        }
        $param["add_ip"] = $this->getIp();
        return $this->model->create($param);
    }

    // update record in the database with uuid
    public function update(array $data, $id)
    {
        $param = $data;
        $record = $this->model->find($id);
        $param["edited_by"] = Auth::user()->id;
        $param["edit_ip"] = $this->getIp();
        $param["edit_date"] = Carbon::now();
        return $record->update($param);
    }

    // remove record from the database
    public function delete($id)
    {
        $_data = $this->model->find($id);

        $_data->is_deleted = 1;
        $_data->deleted_by = Auth::user()->id;
        $_data->delete_ip = $this->getIp();
        $_data->delete_date = Carbon::now();
        $_data->save();

        return $_data;
    }

    // show the record with the given id
    public function edit($id)
    {
        return $this->model->findOrFail($id);
    }

    // show the record with the given id  display view
    public function view($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findByUuid($uuid)
    {
        return $this->model->where('uuid',$uuid)->first();
    }

    // Get the associated model
    public function getModel()
    {
        return $this->model;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations)
    {
        return $this->model->with($relations);
    }

    //get record not delete
    public function getModelNotDelete()
    {
        return $this->model->where('is_deleted',0)->orderBy('id', 'ASC')->get();
    }


    public function listRoles()
    {
        // return Role::whereNotIn('name', ['super-admin'])->get();

        return Role::all();
    }


    public function listUsers()
    {
       return User::whereHas('roles', function($q)
            {
                $q->where([
                    ['name','<>','super-admin']
                ]);
            })
            ->get();
    }


    public function listUsersRoleHasLivreurByBakehouse()
    {

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

       return User::where('bakehouse_id', $bakehouse_id)
            ->whereHas('roles', function($q)
            {
                $q->where([
                    ['name','=','livreur']
                ]);
            })
            ->get();
    }



    public function selectUnit()
    {
        return Unit::where('is_deleted',0)->select('name','id')->get();
    }

    public function selectCivilities()
    {
        return DB::table('civilities')->select('name','id')->get();
    }


    public function liste_permission()
    {
        return Permission::all();
    }


    public function getUser($id)
    {
        return User::find($id);
    }


    public function listeRoles()
    {
        // return Role::whereNotIn('name', ['super-admin'])->get();

        return Role::all();
    }


    public function listeUsers()
    {
       return User::whereHas('roles', function($q)
            {
                $q->where([
                    // ['name','<>','admin'],
                    ['name','<>','super-admin']
                ]);
            })
            ->with('roles')
            ->select('id','first_name','last_name','active','created_at')->get();
    }


}
