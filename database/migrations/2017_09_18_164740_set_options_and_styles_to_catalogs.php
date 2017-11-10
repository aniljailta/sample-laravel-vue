<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Option;
use App\Models\OptionCatalog;
use App\Models\ManufacturerCompany;
use App\Models\Style;
use App\Models\StyleCatalog;

class SetOptionsAndStylesToCatalogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $manufacturerCompanies = ManufacturerCompany::with('options')->get();
        foreach ($manufacturerCompanies as $company) {
            $optionByCategories = $company->options->groupBy('category_id');
            foreach ($optionByCategories as $options) {
                $i = 1;
                foreach ($options as $option) {
                    $option->sort_id = $i;
                    $option->save();
                    $i++;
                }
            }
        }

        $options = Option::with('files')->get();
        $i = 1;
        foreach ($options as $option) {
            $newOption = OptionCatalog::create($option->toArray());
            $option->update(['option_catalog_id' => $newOption->id]);
            $i++;
        }

        $optionByCategories = OptionCatalog::get()->groupBy('category_id');
        foreach ($optionByCategories as $options) {
            $i = 1;
            foreach ($options as $option) {
                $option->sort_id = $i;
                $option->save();
                $i++;
            }
        }

        $styles = Style::get();
        $lastStyle = StyleCatalog::orderBy('sort_id', 'desc')->first();
        $i = 1;
        foreach ($styles as $style) {
            $newStyleData = $style->toArray();
            $newStyleData['sort_id'] = $lastStyle ? $lastStyle->sort_id + $i : $i;
            $newStyle = StyleCatalog::create($newStyleData);
            $style->update(['style_catalog_id' => $newStyle->id]);
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
