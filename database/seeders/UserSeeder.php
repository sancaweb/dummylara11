<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        /**
         * roles
         */
        Role::create([
            'name' => 'super admin',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);



        Role::create([
            'name' => 'user',
            'guard_name' => 'web'
        ]);


        /**
         * PERMISSIONS
         */
        $permissions = [
            'user create', 'user read', 'user update', 'user delete', 'user destroy',
            'activity create', 'activity read', 'activity update', 'activity delete',
            'role create', 'role read', 'role update', 'role delete',
            'permission create', 'permission read', 'permission update', 'permission delete',
            'assign sync',

            'profile read', 'profile update',

        ];

        $adminPermissions = [

            'profile read', 'profile update',

            'user create', 'user read', 'user update',
            'activity create', 'activity read', 'activity update', 'activity delete',
        ];


        $userPermissions = [
            'profile read', 'profile update',
        ];


        foreach ($permissions as $permit) {
            Permission::create([
                'name' => $permit,
                'guard_name' => "web"
            ]);
        }

        /**
         * # CREATE USER
         * 1. super admin
         */

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'username' => 'super',
            'email' => "super@email.com",
            'password' => bcrypt('passwordsuper')
        ]);

        $superAdmin->assignRole('super admin');

        /**
         * 2. admin
         */
        $admin = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => "admin@email.com",
            'password' => bcrypt('passwordadmin')
        ]);

        $admin->assignRole('admin');

        //** give role permissions */
        $roleSuper = Role::find(1);
        $roleSuper->syncPermissions($permissions);

        $roleAdmin = Role::find(2);
        $roleAdmin->syncPermissions($adminPermissions);

    }
}
