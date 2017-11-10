<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeColumnsInSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `sales` MODIFY `building_id` INTEGER  UNSIGNED DEFAULT NULL;');
        DB::statement('ALTER TABLE `sales` MODIFY `location_id` INTEGER  UNSIGNED DEFAULT NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `sales` MODIFY `building_id` INTEGER  UNSIGNED NOT NULL;');
        DB::statement('ALTER TABLE `sales` MODIFY `location_id` INTEGER  UNSIGNED NOT NULL;');
    }
}
