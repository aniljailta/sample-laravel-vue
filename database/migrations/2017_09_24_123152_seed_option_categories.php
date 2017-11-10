<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedOptionCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('option_categories', 'sort_id')) {
            Schema::table('option_categories', function (Blueprint $table) {
                $table->integer('sort_id')->nullable()->unsigned()->index()->after('qty_limit');
            }); 
        }    
        \Artisan::call('db:seed', ['--class' => OptionCategoriesTableSeeder::class, '--force' => true ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('option_categories', 'sort_id')) {
            Schema::table('option_categories', function (Blueprint $table) {
                $table->dropColumn('sort_id');
            }); 
        }
    }
}
