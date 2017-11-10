<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Plant;

class AddPlantIdToPlantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plants', function (Blueprint $table) {
            $table->integer('plant_id')->unsigned()->after(MANUFACTURER_COMPANY_ID)->comment('company scoped sequence ID')->nullable();
            $table->unique(['plant_id', MANUFACTURER_COMPANY_ID]);
        });

        DB::transaction(function() {
            $this->assignCompanyScopedIDs();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plants', function (Blueprint $table) {
            $table->dropUnique('plants_plant_id_manufacturer_company_id_unique');
            $table->dropColumn('plant_id');
        });
    }

    private function assignCompanyScopedIDs() {
        $plants = Plant::orderBy('id', 'asc')->get()->groupBy(MANUFACTURER_COMPANY_ID);
        $plants->each(function($plants, $companyID) {
            $idPerCompany = 1;
            $plants->each(function ($plant) use ($companyID, &$idPerCompany) {
                $plant->plant_id = $idPerCompany;
                $plant->save();
                $idPerCompany++;
            });
        });
    }
}
