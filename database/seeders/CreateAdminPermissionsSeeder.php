<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use App\Constants\Roles;

class CreateAdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role  = Role::where('name', Roles::ADMIN)->first();
        $permissions = Permission::pluck('id','id')->all();
        if($permissions){
            $role->syncPermissions($permissions); 
        }
                
    }



    protected function getPermissions() {

        return [
            'register',
            'forget-password',
            'reset-password',
            'edit-profile',
            'dashboard-home',
        ];
    }



}