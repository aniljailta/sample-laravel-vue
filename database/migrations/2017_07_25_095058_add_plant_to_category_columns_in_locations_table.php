<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Location;

class AddPlantToCategoryColumnsInLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE locations CHANGE COLUMN category category ENUM('dealer', 'customer', 'other','plant')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE locations CHANGE COLUMN category category ENUM('dealer', 'customer', 'other')");

    }
}
