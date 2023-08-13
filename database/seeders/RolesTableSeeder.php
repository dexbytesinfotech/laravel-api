<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Constants\Roles;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       /*
         * Role Types
         *
         */
        $RoleItems = [
            [
                'name'          => Roles::MANAGER,
                'guard_name'    => 'web'
            ],
            [
                'name'          => Roles::AGENT,
                'guard_name'    => 'web'
            ],
            [
                'name'          => Roles::CUSOTMER,
                'guard_name'    => 'web'
            ],
            [
                'name'          => Roles::UNVERIFIED,
                'guard_name'    => 'web'
            ]
        ];

        /*
         * Add Role Items
         *
         */
        foreach ($RoleItems as $RoleItem) {
            $newRoleItem = Role::where('name', '=', $RoleItem['name'])->first();
            if ($newRoleItem === null) {
                $newRoleItem = Role::create([
                    'name'                => $RoleItem['name'],
                    'guard_name'          => $RoleItem['guard_name']
                ]);

            }
        }
    }
}
