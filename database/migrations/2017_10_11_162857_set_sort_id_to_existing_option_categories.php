<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\OptionCategory;

class SetSortIdToExistingOptionCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $optionCats = OptionCategory::get();
        $i = 1;
        foreach ($optionCats as $optionCat) {
            $optionCat->sort_id = $i;
            $optionCat->save();
            $i++;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
