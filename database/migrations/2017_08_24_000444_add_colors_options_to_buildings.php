<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColorsOptionsToBuildings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->integer('roof_bo_id')->nullable()->after('sort_id');
            $table->integer('trim_bo_id')->nullable()->after('roof_bo_id');
            $table->integer('siding_bo_id')->nullable()->after('trim_bo_id');
        });
        // field in buildings => option category
        $paramsToUpdate = ['roof_bo_id' => 'roof', 'trim_bo_id' => 'trim', 'siding_bo_id' => 'siding'];
        $buildings = \App\Models\Building::all();

        foreach ($paramsToUpdate as $buildingField => $categoryGroup) {
            foreach ($buildings as $building) {
                DB::statement("UPDATE buildings AS T1,
                (SELECT bo.id FROM building_options bo 
                INNER JOIN buildings b ON b.id = bo.building_id
                INNER JOIN building_option_colors bop ON bop.building_option_id = bo.id
                INNER JOIN colors c ON bop.color_id = c.id
                INNER JOIN options o ON o.id = bo.option_id
                INNER JOIN option_categories oc ON oc.id = o.category_id
                WHERE oc.group='".$categoryGroup."' AND b.id=".$building->id." GROUP BY b.id) AS T2 
              SET T1.".$buildingField."=T2.id
            WHERE T1.id = ".$building->id.";");
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn('roof_bo_id');
            $table->dropColumn('trim_bo_id');
            $table->dropColumn('siding_bo_id');
        });
    }
}
