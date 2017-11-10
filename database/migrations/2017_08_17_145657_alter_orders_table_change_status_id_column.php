<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdersTableChangeStatusIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE `orders` 
              CHANGE COLUMN `status_id` `status_id` 
              ENUM('draft', 'submitted', 'review_needed', 'sale_generated', 'cancellation_requested', 'cancelled', 'signature_pending', 'signed') 
              CHARACTER SET 'utf8' NULL DEFAULT NULL ;
            ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE `orders` 
              CHANGE COLUMN `status_id` `status_id` 
              ENUM('draft', 'submitted', 'review_needed', 'sale_generated', 'cancelled', 'signature_pending', 'signed') 
              CHARACTER SET 'utf8' NULL DEFAULT NULL ;
            ");
    }
}
