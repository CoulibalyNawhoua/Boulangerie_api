<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use App\Jobs\SendUserMailJob;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\StoreUserDepotRequest;
use App\Http\Requests\StoreUserRoleChangeRequest;
use App\Http\Requests\StoreUserEmailChangeRequest;
use App\Http\Requests\StoreUserPasswordChangeRequest;
use App\Http\Requests\StoreUserUsernameChangeRequest;

class UserRepository extends Repository
{
    public function __construct(User $model)
    {
        $this->model = $model ;
    }

    public function storeUser(Request $request)
    {
        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $userDeliveryCount = User::where('bakehouse_id', $bakehouse_id)
             ->whereHas('roles', function($q)
             {
                 $q->where([
                     ['name','=','livreur']
                 ]);
             })
             ->count();

        if(Auth::user()->bakehouse->nb_delivery_person > $userDeliveryCount){
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $input['email'] = $input['email'];
            $input['phone'] = $input['phone'];
            $input['first_name'] = $input['first_name'];
            $input['last_name'] = $input['last_name'];
            $input['bakehouse_id'] = $bakehouse_id;
            $input['added_by'] = Auth::user()->id;
            $input['add_ip'] = $this->getIp();
            $input['add_date'] = Carbon::now();

            $user = User::create($input);
            $user->assignRole("livreur");
        }else{
            return $data = [
                "code"=>"422",
                "msg" => "Vous n'êtes pas autorisé à créer plus de ".$userDeliveryCount." livreurs"
            ];
        }



    }

    public function storeAborne(Request $request)
    {

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['email'] = $input['email'];
        $input['phone'] = $input['phone'];
        $input['first_name'] = $input['first_name'];
        $input['last_name'] = $input['last_name'];
        $input['bakehouse_id'] = $input['bakehouse_id'];
        // $input['username'] = $input['user_name'];
        $input['added_by'] = Auth::user()->id;
        $input['add_ip'] = $this->getIp();
        $input['add_date'] = Carbon::now();

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        // $info = UserInfo::where('user_id', $user->id)->first();

        // if ($info === null) {
        //     $info = new UserInfo();
        // }

        // // attach this info to the current user
        // $info->user()->associate(auth()->user());

        // foreach ($request->only(array_keys($request->rules())) as $key => $value) {
        //     if (is_array($value)) {
        //         $value = serialize($value);
        //     }
        //     $info->$key = $value;
        // }

        // if ($request->depot_id) {
        //     $info->entites_id = $request->depot_id;
        // }

        // $info->save();
    }




    public function updateEmail(Request $request, $id)
    {
        $input = $request->all();

        $user = $this->model->find($id);

        $user->update(['email' => $input['email']]);

        return $user->email;
    }



    public function updatePassword(Request $request, $id)
    {
        $input = $request->all();

        $user = $this->model->find($id);

        $user->update(['password' => Hash::make($input['new_password'])]);
    }


    public function updateRole(Request $request, $id)
    {

        $input = $request->all();

        $user = $this->model->find($id);

        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($input['roles']);

        $userRoles = $user->roles->pluck('name')->all();

        return $userRoles[0];
    }


    public function getRole()
    {
        return Role::whereNotIn('name', ['super-admin','admin'])->get()->pluck('name');
    }



    public function selectRole()
    {
        return Role::whereNotIn('name', ['super-admin'])->get()->pluck('name');
    }



    public function deleteLastRole($id)
    {
       return  DB::table('model_has_roles')->where('model_id',$id)->delete();
    }





    public function usersAccount($id)
    {
        $user = User::find($id);
        $userRole = $user->roles->pluck('name')->all();
        $data = array(
            'user' => $user,
            'userRole'  => $userRole,
        );

        return $data;
    }

    public function disabledAccount($user_id)
    {
       return User::where('id', $user_id)->update(['active' => 0]);
    }

    public function activateAccount($user_id)
    {
        return User::where('id', $user_id)->update(['active' => 1]);
    }


    public function user_edit($id)
    {
        $user = User::find($id);

        $roles = Role::whereNotIn('name', ['super-admin'])->get()->pluck('name');

        $userRole = $user->roles->pluck('name')->all();

        return $data = [
            'user'=>$user,
            'userRole'=>$userRole,
            'roles'=>$roles,
        ];
    }


    public function updateUsername(Request $request, $id)
    {
        $input = $request->all();

        $user = $this->model->find($id);

        $user->update(['user_name' => $input['user_name']]);

        return $user->user_name;
    }

    public function listUsers()
    {
        return User::leftJoin('bakehouses','bakehouses.id','=','users.bakehouse_id')
                    ->selectRaw('users.id,bakehouses.name,users.email,users.phone,bakehouses.name,users.active, CONCAT(users.first_name," ",users.last_name) as complet_name')
                    ->get();
    }


    // public function updateUserEntrepot(Request $request,$id)
    // {
    //     $input = $request->all();

    //     $user = $this->model->find($id);

    //     $user->update(['entite_id' => $input['entite_id']]);


    //     if ($user->entite) {
    //         $entrepot = $user->entite->name;
    //     }

    //     else{
    //         $entrepot = '';
    //     }

    //     return $entrepot;
    // }


}
