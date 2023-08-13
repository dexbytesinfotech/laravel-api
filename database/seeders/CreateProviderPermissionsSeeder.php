<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class CreateProviderPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role  = Role::where('name', 'Provider')->first();
        $permissions = Permission::whereIn('name', $this->getPermissions())->where('guard_name', 'web')->pluck('id','id')->all();
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