<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Tables to truncate
     * @var array
     */
    protected $tables = [
        'buildings',
        'building_models',
        'building_statuses',
        'locations',
        'plants',
        'styles',
        'options',
        'role_user',
        'permission_role',
        'permissions',
        'roles',
        'dealers',
        'users',
        'settings',
        'colors',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->cleanDatabase();
        $this->call(UserSeeder::class);
        $this->call(ColorSeeder::class);
        $this->call(StyleSeeder::class);
        $this->call(OptionSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(DealerSeeder::class);
        $this->call(PlantSeeder::class);
        $this->call(BuildingModelSeeder::class);
        $this->call(BuildingStatusSeeder::class);
        $this->call(SettingSeeder::class);

        Model::reguard();
    }

    /**
     * Cleans database
     * @return void
     */
    public function cleanDatabase()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach($this->tables as $table)
        {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
