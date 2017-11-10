<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BooleanFlagIsEsignedInFileSignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE `file_signs` SET `is_esigned` = '1' WHERE `is_esigned` = 'yes'");
        DB::statement("UPDATE `file_signs` SET `is_esigned` = '0' WHERE `is_esigned` = 'no'");
        DB::statement('ALTER TABLE `file_signs` CHANGE `is_esigned` `is_esigned` TINYINT(10) UNSIGNED NULL DEFAULT 0');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `file_signs` CHANGE `is_esigned` `is_esigned` ENUM('yes','no', '0', '1') NULL DEFAULT '0'");
        DB::statement("UPDATE `file_signs` SET `is_esigned` = 'yes' WHERE `is_esigned` = '1'");
        DB::statement("UPDATE `file_signs` SET `is_esigned` = 'no' WHERE `is_esigned` = '0'");
        DB::statement("ALTER TABLE `file_signs` CHANGE `is_esigned` `is_esigned` ENUM('yes','no') NULL DEFAULT NULL");
    }
}
