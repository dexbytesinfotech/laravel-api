<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use App\Constants\Roles;


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
            'first_name' => 'Admin',
            'last_name' => 'user',
            'email' => 'admin@admin.com',
            'password' => 'admin123',
            'remember_token' => Str::random(60)
        ]);

        $role = Role::create(['name' => Roles::ADMIN,'guard_name' => 'web']);
        $user->assignRole([$role->id]);

    }
}
