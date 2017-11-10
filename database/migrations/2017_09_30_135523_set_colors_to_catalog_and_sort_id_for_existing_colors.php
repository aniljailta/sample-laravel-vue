<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\ManufacturerCompany;
use App\Models\Color;
use App\Models\ColorCatalog;

class SetColorsToCatalogAndSortIdForExistingColors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $manufacturerCompanies = ManufacturerCompany::with(['colors'])->get();
        foreach ($manufacturerCompanies as $company) {
            $colors = $company->colors;
            $i = 1;
            foreach ($colors as $color) {
                $color->sort_id = $i;
                $color->save();
                $i++;
            }
        }

        $colors = Color::get();
        $i = 1;
        foreach ($colors as $color) {
            $newColorData = $color->toArray();
            $newColorData['sort_id'] = $i;
            $newColor = ColorCatalog::create($newColorData);
            $color->update(['color_catalog_id' => $newColor->id]);
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
