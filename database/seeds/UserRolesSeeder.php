<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'administrator',
                'display_name' => 'Administrator',
                'description' => 'Users in the system with this role are, well, gods'
            ],
            [
                'name' => 'employee',
                'display_name' => 'Employee',
                'description' => 'User in the system with employee role'
            ],
            [
                'name' => 'customer',
                'display_name' => 'Customer',
                'description' => 'User in the system with customer role'
            ]
        ];

        foreach ($roles as $key => $role) {
            # code...
            $count = Role::where('name', $role['name'])->count();
            if (!$count) {
                $admin = Role::create($role);
            }

        }

    }
}
