<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\ManufacturerCompany;

class SetSortIdForExistingOptionsAndStyles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $manufacturerCompanies = ManufacturerCompany::with(['options', 'styles'])->get();
        foreach ($manufacturerCompanies as $company) {
            $options = $company->options;
            $i = 1;
            foreach ($options as $option) {
                $option->sort_id = $i;
                $option->save();
                $i++;
            }

            $styles = $company->styles;
            $i = 1;
            foreach ($styles as $style) {
                $style->sort_id = $i;
                $style->save();
                $i++;
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
        //
    }
}
