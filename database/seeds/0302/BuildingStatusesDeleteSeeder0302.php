<?php

use DB as DB;
use App\Models\BuildingStatus;
use Illuminate\Database\Seeder;

class BuildingStatusesDeleteSeeder0302 extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info(__CLASS__ . ' Start');
        
        $this->createBuildingStatuses();
        $this->deleteBuildingStatuses();

        Log::info(__CLASS__ . ' End');
    }

    /**
     * Create building status = draft
     */
    private function createBuildingStatuses() {
        BuildingStatus::firstOrCreate(['name' => 'Draft', 'is_active' => 'yes', 'priority' => 0, 'type' => 'build']);
    }

    /**
     * Delete building statuses
     */
    private function deleteBuildingStatuses() {
        DB::unprepared("set @var=if((SELECT true FROM information_schema.TABLE_CONSTRAINTS WHERE
            CONSTRAINT_SCHEMA = DATABASE() AND
            TABLE_NAME        = 'building_history' AND
            CONSTRAINT_NAME   = 'building_history_status_id_foreign' AND
            CONSTRAINT_TYPE   = 'FOREIGN KEY') = true,'ALTER TABLE building_history
            drop foreign key building_history_status_id_foreign','select 1');

            prepare stmt from @var;
            execute stmt;
            deallocate prepare stmt;");

        /*DB::statement('ALTER TABLE `building_history`
                       DROP FOREIGN KEY `building_history_status_id_foreign`');*/

        DB::statement('ALTER TABLE `building_history` 
                       ADD CONSTRAINT `building_history_status_id_foreign` 
                       FOREIGN KEY (`status_id`) 
                       REFERENCES `building_statuses`(`id`) 
                       ON DELETE CASCADE ON UPDATE CASCADE;');
        
        BuildingStatus::where('type', '!=', 'build')->forceDelete();

        DB::statement('ALTER TABLE `building_history` 
                       DROP FOREIGN KEY `building_history_status_id_foreign`');
        DB::statement('ALTER TABLE `building_history` 
                       ADD CONSTRAINT `building_history_status_id_foreign` 
                       FOREIGN KEY (`status_id`) 
                       REFERENCES `building_statuses`(`id`) 
                       ON DELETE RESTRICT ON UPDATE CASCADE;');
    }
}
