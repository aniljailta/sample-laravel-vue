<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBuildingRtoContractOwnerField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE buildings CHANGE COLUMN building_rto_contract_owner_id used_rto_owner INT(11) NOT NULL;");
        DB::statement("INSERT INTO option_categories SET `name`='Discounts', `group`='discounts'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE buildings CHANGE COLUMN used_rto_owner building_rto_contract_owner_id INT(11) NOT NULL;");
        DB::statement("DELETE FROM option_categories WHERE `group`='discounts'");
    }
}
