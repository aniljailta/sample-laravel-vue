<?php

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create([
            'name' => 'administrator',
            'display_name' => 'Administrator',
            'description' => 'Users in the system with this role are, well, gods'
            ]);

        $bob = factory(User::class, 1)->create([
            'first_name' => 'Robert',
            'last_name' => 'Oxley',
            'email' => 'bob@urbanshedconcepts.com',
            'password' => bcrypt('secret123'),
            // 'verified' => true,
            // 'is_active' => 'yes',
            // 'last_login' => date('Y-m-d H:i:s')
            ]);
        $bob->attachRole($admin);
    }
}
