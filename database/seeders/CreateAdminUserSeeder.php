<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\Hash;


class CreateAdminUserSeeder extends Seeder

{

    /**

     * Run the database seeds.

     *

     * @return void

     */

    public function run()

    {

        $user = User::create([
            'name' => 'Ambeu',
            'first_name' => 'Ambeu',
            'last_name' => 'Aka Anderson',
            'email' => 'andersonambeu@gmail.com',
            'phone' => '0768121340',
            'password' => '123456'
        ]);


        // $role = Role::create(['name' => 'livreur']);

        // $permissions = Permission::pluck('id','id')->all();

        // $role->syncPermissions($permissions);

        $user->assignRole(['superadmin']);


    }

}
