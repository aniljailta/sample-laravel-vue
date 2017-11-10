<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeUserIdColumnFromFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('ALTER TABLE `files` MODIFY `user_id` INTEGER UNSIGNED DEFAULT NULL;');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('ALTER TABLE `files` MODIFY `user_id` INTEGER UNSIGNED NOT NULL;');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
