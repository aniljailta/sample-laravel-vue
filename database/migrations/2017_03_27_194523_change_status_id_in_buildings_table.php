<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStatusIdInBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `buildings` CHANGE `status_id` `status_id` INT(10) UNSIGNED NULL DEFAULT NULL');
        DB::statement('UPDATE `buildings` SET `status_id` = NULL');
        Schema::table('buildings', function (Blueprint $table) {
            $table->foreign('status_id')
                ->references('id')
                ->on('building_history')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        Artisan::call('db:seed', ['--class' => 'MainSeeder0302', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'MainSeeder0303', '--force' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET foreign_key_checks = 0;');
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropForeign('buildings_status_id_foreign');
        });
        DB::statement("ALTER TABLE `buildings` CHANGE `status_id` `status_id` ENUM('draft','approved') NULL DEFAULT 'draft'");
        DB::statement('SET foreign_key_checks = 1;');
    }
}
