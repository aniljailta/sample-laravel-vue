<?php

use App\Models\Option;
use App\Models\OptionCategory;
use App\Models\OptionCatalog;

use Illuminate\Database\Seeder;

class LiveOptionsTableSeeder extends Seeder
{
    private $optionCategories;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info(__CLASS__ . ' Start');

        try {
            DB::beginTransaction();
                
            $this->fetchOptionCatalogs();
            $this->fetchOptionCategories();
            $this->updateOptions();

            DB::commit();
        } catch (Exception $e) {
            $output = new Symfony\Component\Console\Output\ConsoleOutput();
            $output->writeln("<error>{$e->getMessage()}</error>");
            $output->writeln($e->getTraceAsString());
            DB::rollback();
        }

        Log::info(__CLASS__ . ' End');
    }

    private function fetchOptionCategories()
    {
        $this->optionCategories = OptionCategory::all();
    }

    private function fetchOptionCatalogs()
    {
        $this->optionCatalogs = OptionCatalog::all();
    }

    /**
     * @param string $name
     * @return int|null
     */
    private function getOptionCategoryId(string $name): ?int
    {
        $category = $this->optionCategories->first(function ($category) use ($name) {
            return $category->name === $name;
        });

        if ($category) return $category->id;
        return null;
    }

    /**
     * @param string $name
     * @return int|null
     */
    private function getOptionCatalogId(string $name): ?int
    {
        $catalog = $this->optionCatalogs->first(function ($catalog) use ($name) {
            return $catalog->name === $name;
        });

        if ($catalog) return $catalog->id;
        return null;
    }

    private function updateOptions()
    {
        Log::info(__CLASS__ . ' Update option START');

        $this->setOption(['name' => "8' Wide Loft ($/lf)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "8' wide loft (1 lf)",
            'unit_price' => 20,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 1
            ]);

        $this->setOption(['name' => "10' Wide Loft ($/lf)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "10' wide loft (1 lf)",
            'unit_price' => 29,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 2
            ]);

        $this->setOption(['name' => "12' Wide Loft (($/lf))", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "12' wide loft (1 lf)",
            'unit_price' => 29,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 3
            ]);

        $this->setOption(['name' => "8' x 4' Deck", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "8' x 4' deck and posts",
            'unit_price' => 340,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 4
            ]);

        $this->setOption(['name' => "10' x 4' Deck", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "10'x4' deck and posts",
            'unit_price' => 380,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 5
            ]);

        $this->setOption(['name' => "12' x 4' Deck", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "12'x4' deck and posts",
            'unit_price' => 440,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 6
            ]);

        $this->setOption(['name' => "Steel handrail", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "Steel handrail panel (up to 5')",
            'unit_price' => 92,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 7
            ]);

        $this->setOption(['name' => "4' Shed Door", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "4'x6' Heavy-duty shed door",
            'unit_price' => 270,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 8
            ]);

        $this->setOption(['name' => "Double Shed Door", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Double 3'x6' Heavy-duty shed doors",
            'unit_price' => 500,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 10
            ]);

        $this->setOption(['name' => "3-0 6-8 RH Steel Paneled Door", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3-0 6-8 door. Hinges on right, facing door from the outside of building",
            'unit_price' => 300,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 10,
            'option_catalog_id' => 11
            ]);

        $this->setOption(['name' => "3-0 6-0 RH Steel Paneled 9-light Door", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3-0 6-0 door. Hinges on right, facing door from the outside of building. 9 light.",
            'unit_price' => 350,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 12,
            'option_catalog_id' => 12
            ]);

        $this->setOption(['name' => "2' x 3' Single Pane Window", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "2'x3' Single pane gridded aluminum window",
            'unit_price' => 110,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 13
            ]);

        $this->setOption(['name' => "3' x'3 Single Pane Window", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "3'x3' Single pane gridded aluminum window",
            'unit_price' => 125,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 14
            ]);

        $this->setOption(['name' => "2' x 3' Double Pane Window", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "2'x3' Double pane gridded aluminum window",
            'unit_price' => 225,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 15
            ]);

        $this->setOption(['name' => "3' x 3' Double Pane Window", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "3'x3' Double pane gridded aluminum window",
            'unit_price' => 260,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 16
            ]);

        $this->setOption(['name' => "6' x 7' Roll-up Door", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "6'x7' Roll-up door",
            'unit_price' => 690,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 18,
            'option_catalog_id' => 17
            ]);

        $this->setOption(['name' => "9' x 7' Roll-up Door", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "9'x7' Roll-up door",
            'unit_price' => 780,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 19,
            'option_catalog_id' => 18
            ]);

        $this->setOption(['name' => "12' Stall / 10' Opening", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "12' wide horse stall, 3/4 CCA kickboard",
            'unit_price' => 775,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 19
            ]);

        $this->setOption(['name' => "Custom Paint Color", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => "wall_area",
            'description' => "Custom paint color",
            'unit_price' => 0.3,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 23
            ]);

        $this->setOption(['name' => "Custom wall height (shorter than standard)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => "building_length",
            'description' => "Custom wall height (shorter than standard)",
            'unit_price' => 6.5,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 24
            ]);

        $this->setOption(['name' => "8' Wide Extra Strong ($/lf)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => NULL,
            'description' => "8' Wide extra strength floor (12\" O.C) (1 lf)",
            'unit_price' => 5,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 25
            ]);

        $this->setOption(['name' => "10' Wide Extra Strong ($/lf)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => NULL,
            'description' => "10' Wide extra strength floor (12\" O.C) (1 lf)",
            'unit_price' => 6,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 26
            ]);

        $this->setOption(['name' => "12' Wide Extra Strong Floor ($/lf)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => NULL,
            'description' => "12' Wide extra strength floor (12\" O.C) (1 lf)",
            'unit_price' => 8,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 27
            ]);

        $this->setOption(['name' => "Wiring Package", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Electrical"),
            'force_quantity' => NULL,
            'description' => "100 amp panel, 5 - 20 amp circuits, up to 12 boxes",
            'unit_price' => 750,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 28
            ]);

        $this->setOption(['name' => "Manufacturer Discount", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Discount"),
            'force_quantity' => NULL,
            'description' => "Discount set by the manufacturer",
            'unit_price' => -1,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 29
            ]);

        $this->setOption(['name' => "Dry Wall Framing", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "Framing for drywall",
            'unit_price' => 250,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 30
            ]);

        $this->setOption(['name' => "8' Wide Spray Foam (per linear foot of building length)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Insulation"),
            'force_quantity' => NULL,
            'description' => "Spray foam for 8 wide building (1 LF)",
            'unit_price' => 65,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 31
            ]);

        $this->setOption(['name' => "10' Wide Spray Foam (per linear foot of building length)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Insulation"),
            'force_quantity' => NULL,
            'description' => "Spray foam for 10 wide building (1 LF)",
            'unit_price' => 70,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 32
            ]);

        $this->setOption(['name' => "12' Wide Spray Foam (per linear foot of building length)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Insulation"),
            'force_quantity' => NULL,
            'description' => "Spray foam for 12 wide building (1 LF)",
            'unit_price' => 85,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 33
            ]);

        $this->setOption(['name' => "Miscellaneous", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "Finished Interior (varies)",
            'unit_price' => 1,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 11,
            'option_catalog_id' => 35
            ]);

        $this->setOption(['name' => "Econ Door", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Economy door",
            'unit_price' => 90,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 36
            ]);

        $this->setOption(['name' => "Custom Shingle Color", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => "floor_area",
            'description' => "Custom shingle color",
            'unit_price' => 0.5,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 37
            ]);

        $this->setOption(['name' => "Shelf/Workbench(20\") (per Linear Foot)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "20\" Shelf (1 LF)",
            'unit_price' => 10,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 38
            ]);

        $this->setOption(['name' => "Shutters", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "3' Shutters",
            'unit_price' => 55,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 12,
            'option_catalog_id' => 39
            ]);

        $this->setOption(['name' => "12' Wide Radiant Barrier Insulation ($/lf)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Insulation"),
            'force_quantity' => NULL,
            'description' => "12' Wide Radiant Barrier Insulation ($/lf)",
            'unit_price' => 30,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 6,
            'option_catalog_id' => 40
            ]);

        $this->setOption(['name' => "2'x2' Tinted Skylight (Shingle only)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "2'x2' Tinted Skylight (shingle roof only)",
            'unit_price' => 250,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 10,
            'option_catalog_id' => 41
            ]);

        $this->setOption(['name' => "Ridge Vent ($/lf)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "Ridge Vent ($/lf)",
            'unit_price' => 4,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 42
            ]);

        $this->setOption(['name' => "220 V - 20 Amp Box - outlet not included (requires standard wire package)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Electrical"),
            'force_quantity' => NULL,
            'description' => "220 V - 20 Amp Box - outlet not included (requires standard wire package) ",
            'unit_price' => 75,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 6,
            'option_catalog_id' => 43
            ]);

        $this->setOption(['name' => "220 V - 30 Amp Box - outlet not included (requires standard wire package)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Electrical"),
            'force_quantity' => NULL,
            'description' => "220 V - 30 Amp Box - outlet not included (requires standard wire package)",
            'unit_price' => 125,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 7,
            'option_catalog_id' => 44
            ]);

        $this->setOption(['name' => "Electrical Box - GFI Upgrade (requires standard wiring package)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Electrical"),
            'force_quantity' => NULL,
            'description' => "Electrical Box - GFI Upgrade, with or without outdoor cover (requires standard wiring package)",
            'unit_price' => 50,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 45
            ]);

        $this->setOption(['name' => "Electrical - 3 Way Switch (Requires standard wiring package)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Electrical"),
            'force_quantity' => NULL,
            'description' => "Electrical - 3 Way Switch setup, includes both switches (Requires standard wiring package)",
            'unit_price' => 75,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 46
            ]);

        $this->setOption(['name' => "Extra Electrical Box - (requires standard wiring package)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Electrical"),
            'force_quantity' => NULL,
            'description' => "Extra Electrical Box - (requires standard wiring package)",
            'unit_price' => 50,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 47
            ]);

        $this->setOption(['name' => "Interior wall (1lf)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "Interior wall (1 linear foot), framing only.",
            'unit_price' => 15,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 6,
            'option_catalog_id' => 48
            ]);

        $this->setOption(['name' => "AC Framing", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => NULL,
            'description' => "Framing for in-wall AC unit (requires dimensions from customer, up to 4'x3')",
            'unit_price' => 75,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 49
            ]);

        $this->setOption(['name' => "6-0 6-8 Steel Paneled 15-light French Doors ", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "15-light steel French Walk-in doors, 6'-8\" high",
            'unit_price' => 700,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 17,
            'option_catalog_id' => 51
            ]);

        $this->setOption(['name' => "6-0 6-8 Steel Paneled French Doors ", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "6-0 6-8 Steel Paneled French doors",
            'unit_price' => 600,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 16,
            'option_catalog_id' => 52
            ]);

        $this->setOption(['name' => "Flower Box", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "Wooden flower box with insert",
            'unit_price' => 75,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 13,
            'option_catalog_id' => 53
            ]);

        $this->setOption(['name' => "21\" Cupola", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "21\" Cupola",
            'unit_price' => 495,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 6,
            'option_catalog_id' => 54
            ]);

        $this->setOption(['name' => "2' Ramp (VETERAN DISCOUNT ONLY)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Order"),
            'force_quantity' => NULL,
            'description' => "2' wide ramp (free for Veterans)",
            'unit_price' => 0,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 7,
            'option_catalog_id' => 56
            ]);

        $this->setOption(['name' => "Double Shed Doors (w/ transom)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Double 3'x6' Heavy-duty shed doors with 29\" transoms",
            'unit_price' => 575,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 6,
            'option_catalog_id' => 57
            ]);

        $this->setOption(['name' => "29\" Transom Window", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "29\" transom window, installed in wall.",
            'unit_price' => 75,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 6,
            'option_catalog_id' => 58
            ]);

        $this->setOption(['name' => "60\" Transom window", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "60\" transom window, installed in wall.",
            'unit_price' => 105,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 8,
            'option_catalog_id' => 59
            ]);

        $this->setOption(['name' => "10' Wide Radiant Barrier Insulation ($/lf)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Insulation"),
            'force_quantity' => NULL,
            'description' => "10' Wide Radiant Barrier Insulation ($/lf)",
            'unit_price' => 27,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 60
            ]);

        $this->setOption(['name' => "8' Wide Radiant Barrier Insulation ($/lf)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Insulation"),
            'force_quantity' => NULL,
            'description' => "8' Wide Radiant Barrier Insulation ($/lf)",
            'unit_price' => 24,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 61
            ]);

        $this->setOption(['name' => "1'x1' Gable Window", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "1'x1' Window, placed in gable",
            'unit_price' => 75,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 63
            ]);

        $this->setOption(['name' => "Interior Door Framing (requires door dimensions)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 75,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 7,
            'option_catalog_id' => 64
            ]);

        $this->setOption(['name' => "12-wide Metal (Barn)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "",
            'unit_price' => 16,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => 1,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 65
            ]);

        $this->setOption(['name' => "8-wide Metal (Barn)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "",
            'unit_price' => 14,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => 1,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 66
            ]);

        $this->setOption(['name' => "10-Wide Metal Roof (Barn)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "",
            'unit_price' => 15,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => 1,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 67
            ]);

        $this->setOption(['name' => "8-Wide Metal (Shack)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "",
            'unit_price' => 10,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => 1,
            'is_default' => 0,
            'sort_id' => 6,
            'option_catalog_id' => 68
            ]);

        $this->setOption(['name' => "10-Wide Metal (Shack)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "",
            'unit_price' => 11,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => 1,
            'is_default' => 0,
            'sort_id' => 7,
            'option_catalog_id' => 69
            ]);

        $this->setOption(['name' => "12-Wide Metal (Shack)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "",
            'unit_price' => 12,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => 50,
            'is_default' => 0,
            'sort_id' => 8,
            'option_catalog_id' => 70
            ]);

        $this->setOption(['name' => "Floor Insulation (Closed Cell, R13) ($/sqft)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Insulation"),
            'force_quantity' => NULL,
            'description' => "Spray foam floor insulation, closed cell 2\" at R13 (1 sqft)",
            'unit_price' => 3.6,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 7,
            'option_catalog_id' => 73
            ]);

        $this->setOption(['name' => "Floor Insulation (Closed Cell, R26) ($/sqft)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Insulation"),
            'force_quantity' => NULL,
            'description' => "Spray foam floor insulation, closed cell 4\" at R26 (1 sqft)",
            'unit_price' => 7.2,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 8,
            'option_catalog_id' => 74
            ]);

        $this->setOption(['name' => "Electrical - 4 Way Switch (Requires standard wiring package)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Electrical"),
            'force_quantity' => NULL,
            'description' => "Electrical - 4 Way Switch setup, includes 3 switches (Requires standard wiring package)",
            'unit_price' => 125,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 75
            ]);

        $this->setOption(['name' => "10% Home Show Discount", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Order"),
            'force_quantity' => NULL,
            'description' => "10% Home Show Discount",
            'unit_price' => -1,
            'is_active' => "yes",
            'rto' => 1,
            'taxable' => 1,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 77
            ]);

        $this->setOption(['name' => "4' Shed Door (w/transom)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "4' Shed door with 29\" transom window",
            'unit_price' => 340,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 78
            ]);

        $this->setOption(['name' => "Extra Solar Blaster Vent", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "Extra Solar Blaster Vent",
            'unit_price' => 190,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 80
            ]);

        $this->setOption(['name' => "9 ft. Wall Height ($/lf)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => "building_length",
            'description' => "9 ft. wall height, 7/16 Smartside, 2x4 framing",
            'unit_price' => 38,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 7,
            'option_catalog_id' => 81
            ]);

        $this->setOption(['name' => "Site Build", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Miscellaneous"),
            'force_quantity' => NULL,
            'description' => "Site build $650 or 15% whichever is greater",
            'unit_price' => 1,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 82
            ]);

        $this->setOption(['name' => "4' Dutch Shed Door", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "4'x6' Dutch Shed Door (Horizontal split)",
            'unit_price' => 375,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 83
            ]);

        $this->setOption(['name' => "3'x2' Opaque Skylight (metal roof only)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "3'x2' Opaque Skylight (metal roof only)",
            'unit_price' => 250,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 11,
            'option_catalog_id' => 84
            ]);

        $this->setOption(['name' => "8-Wide Metal (Lean-to)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "",
            'unit_price' => 11,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => 1,
            'is_default' => 0,
            'sort_id' => 10,
            'option_catalog_id' => 85
            ]);

        $this->setOption(['name' => "Double Dutch Shed Door (Horizontal split)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Double 4'x6' Dutch Shed Door (Horizontal split)",
            'unit_price' => 750,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 7,
            'option_catalog_id' => 86
            ]);

        $this->setOption(['name' => "Shingle Roof", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "",
            'unit_price' => 0,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 1,
            'sort_id' => 2,
            'option_catalog_id' => 87
            ]);

        $this->setOption(['name' => "LP Smartside Trim", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Trim"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 0,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 1,
            'sort_id' => 1,
            'option_catalog_id' => 88
            ]);

        $this->setOption(['name' => "LP Smartside Siding ", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Siding"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 0,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 1,
            'sort_id' => 1,
            'option_catalog_id' => 89
            ]);

        $this->setOption(['name' => "Aluminum corner trim", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Trim"),
            'force_quantity' => NULL,
            'description' => "Aluminum corner trim, Urban Econ only",
            'unit_price' => 0,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 90
            ]);

        $this->setOption(['name' => "Econ Shingle Roof", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "Shingle roof, Urban Econ Black only.",
            'unit_price' => 0,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => 21,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 91
            ]);

        $this->setOption(['name' => "8'x8' Roll-up Door", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "8'x8' Roll-up Garage Door",
            'unit_price' => 675,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 20,
            'option_catalog_id' => 92
            ]);

        $this->setOption(['name' => "Interior finish ($/sqft)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "Painted Fiber SmartSide, corrugated galvanized metal ceiling, includes one door and beam wrap",
            'unit_price' => 12,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 8,
            'option_catalog_id' => 93
            ]);

        $this->setOption(['name' => "Interior window finish (per window)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "Window wrap, painted SmartSide Fiber and trim",
            'unit_price' => 40,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 9,
            'option_catalog_id' => 94
            ]);

        $this->setOption(['name' => "Interior door finish (per 3' door)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "Interior door finish (per 3' door)",
            'unit_price' => 50,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 10,
            'option_catalog_id' => 95
            ]);

        $this->setOption(['name' => "6 Wide Metal (Lean To)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "6 Wide Metal (Lean To)",
            'unit_price' => 10,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 9,
            'option_catalog_id' => 96
            ]);

        $this->setOption(['name' => "2x6 w/8-ft wall height", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => "building_length",
            'description' => "2x6 walls, 8' tall wall height. Price is per linear foot of building.",
            'unit_price' => 19,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 8,
            'option_catalog_id' => 97
            ]);

        $this->setOption(['name' => "Structural changes for less than 40 lb snowload", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Order"),
            'force_quantity' => NULL,
            'description' => "Required structural changes for up to 40 lb snowload",
            'unit_price' => 500,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 98
            ]);

        $this->setOption(['name' => "Structural changes for less than 60 lb snowload", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Order"),
            'force_quantity' => NULL,
            'description' => "Required structural changes for up to 60 lb snowloads",
            'unit_price' => 750,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 6,
            'option_catalog_id' => 99
            ]);

        $this->setOption(['name' => "Wrap Around Porch (12' x12')", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "12' by 12' wrap around porch",
            'unit_price' => 875,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 100
            ]);

        $this->setOption(['name' => "Dealer Commission Discount", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Order"),
            'force_quantity' => NULL,
            'description' => "Discount at the dealer's discretion, will be deducted from the dealer's commission. ",
            'unit_price' => -1,
            'is_active' => "yes",
            'rto' => 1,
            'taxable' => 1,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 101
            ]);

        $this->setOption(['name' => "Used Building Discount", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Discount"),
            'force_quantity' => NULL,
            'description' => "Discount to apply to used buildings",
            'unit_price' => -1,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 102
            ]);

        $this->setOption(['name' => "8' Wall Height ($/lf)", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => "building_length",
            'description' => "8' Wall height",
            'unit_price' => 16,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 6,
            'option_catalog_id' => 104
            ]);

        $this->setOption(['name' => "2x6 w/9-ft wall height", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => "building_length",
            'description' => "2x6 w/9-ft wall height, 7/16 Smartside ",
            'unit_price' => 51,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 9,
            'option_catalog_id' => 105
            ]);

        $this->setOption(['name' => "Custom Paint Color", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "Custom paint color",
            'unit_price' => 100,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 106
            ]);

        $this->setOption(['name' => "2x3 Single Pane Window", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "Aluminum",
            'unit_price' => 100,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 107
            ]);

        $this->setOption(['name' => "2x3 Double Pane Window", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "Aluminum",
            'unit_price' => 160,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 108
            ]);

        $this->setOption(['name' => "3x3 Single Pane Window", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "Aluminum",
            'unit_price' => 115,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 109
            ]);

        $this->setOption(['name' => "3x3 Double Pane Window", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "Aluminum",
            'unit_price' => 175,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 110
            ]);

        $this->setOption(['name' => "3060 House Door No Window", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Inswing",
            'unit_price' => 295,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 111
            ]);

        $this->setOption(['name' => "3060 House Door 9-lite", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Inswing",
            'unit_price' => 375,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 112
            ]);

        $this->setOption(['name' => "3068 House Door No Window", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Inswing",
            'unit_price' => 295,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 113
            ]);

        $this->setOption(['name' => "3068 House Door 9-lite", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Inswing",
            'unit_price' => 375,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 114
            ]);

        $this->setOption(['name' => "Single Wood Barn Door", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Barn Style",
            'unit_price' => 80,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 115
            ]);

        $this->setOption(['name' => "Single Wood Basic Door", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Basic Door",
            'unit_price' => 80,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 6,
            'option_catalog_id' => 116
            ]);

        $this->setOption(['name' => "Single Wood Heritage Door", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Heritage Door",
            'unit_price' => 80,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 7,
            'option_catalog_id' => 117
            ]);

        $this->setOption(['name' => "Double Wood Barn Door", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "6' Barn Door",
            'unit_price' => 160,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 8,
            'option_catalog_id' => 118
            ]);

        $this->setOption(['name' => "Double Wood Basic Door", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "6' Basic Door",
            'unit_price' => 160,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 9,
            'option_catalog_id' => 119
            ]);

        $this->setOption(['name' => "Double Wood Heritage Door", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "6' Heritage Door",
            'unit_price' => 160,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 10,
            'option_catalog_id' => 120
            ]);

        $this->setOption(['name' => "6' Ramp", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 175,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 121
            ]);

        $this->setOption(['name' => "4' Ramp", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 160,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 122
            ]);

        $this->setOption(['name' => "Shutters", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "per window",
            'unit_price' => 25,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 123
            ]);

        $this->setOption(['name' => "Decorative Hinges 17\" ", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Price per door panel",
            'unit_price' => 60,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 11,
            'option_catalog_id' => 124
            ]);

        $this->setOption(['name' => "Floor Insulation", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Insulation"),
            'force_quantity' => "floor_area",
            'description' => "R20 ",
            'unit_price' => 2.75,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 125
            ]);

        $this->setOption(['name' => "Railing", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "Per Section",
            'unit_price' => 150,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 126
            ]);

        $this->setOption(['name' => "2x2 Skylight", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 250,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 6,
            'option_catalog_id' => 127
            ]);

        $this->setOption(['name' => "2x4 Skylight", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 280,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 7,
            'option_catalog_id' => 128
            ]);

        $this->setOption(['name' => "8' Wide Loft ($/lf)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "8' wide loft (1 lf)",
            'unit_price' => 18.75,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 129
            ]);

        $this->setOption(['name' => "10' Wide Loft ($/lf)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "10' wide loft (1 lf)",
            'unit_price' => 25,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 130
            ]);

        $this->setOption(['name' => "12' Wide Loft (($/lf))", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "12' wide loft (1 lf)",
            'unit_price' => 31.25,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 131
            ]);

        $this->setOption(['name' => "8' x 4' Deck", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "8' x 4' deck and posts",
            'unit_price' => 505,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 132
            ]);

        $this->setOption(['name' => "10' x 4' Deck", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "10'x4' deck and posts",
            'unit_price' => 625,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 133
            ]);

        $this->setOption(['name' => "12' x 4' Deck", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "12'x4' deck and posts",
            'unit_price' => 780,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 134
            ]);

        $this->setOption(['name' => "Steel handrail", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "Steel handrail panel (up to 5')",
            'unit_price' => 85,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 8,
            'option_catalog_id' => 135
            ]);

        $this->setOption(['name' => "4' Shed Door", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "4'x6' Heavy-duty shed door",
            'unit_price' => 190,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 12,
            'option_catalog_id' => 136
            ]);

        $this->setOption(['name' => "3' Shed Door", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3'x6' Heavy-duty shed door",
            'unit_price' => 175,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 13,
            'option_catalog_id' => 137
            ]);

        $this->setOption(['name' => "Double Shed Door", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Double 3'x6' Heavy-duty shed doors",
            'unit_price' => 350,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 14,
            'option_catalog_id' => 138
            ]);

        $this->setOption(['name' => "Steel Walk-in Door", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3-0 Steel Door",
            'unit_price' => 300,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 15,
            'option_catalog_id' => 139
            ]);

        $this->setOption(['name' => "Steel 9-light Walk-in Door", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3' 9-light steel walk-in door",
            'unit_price' => 350,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 16,
            'option_catalog_id' => 140
            ]);

        $this->setOption(['name' => "2' x 3' Single Pane Window", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "2'x3' Single pane gridded aluminum window",
            'unit_price' => 100,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 141
            ]);

        $this->setOption(['name' => "3' x'3 Single Pane Window", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "3'x3' Single pane gridded aluminum window",
            'unit_price' => 115,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 6,
            'option_catalog_id' => 142
            ]);

        $this->setOption(['name' => "2' x 3' Double Pane Window", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "2'x3' Double pane gridded aluminum window",
            'unit_price' => 200,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 7,
            'option_catalog_id' => 143
            ]);

        $this->setOption(['name' => "3' x 3' Double Pane Window", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "3'x3' Double pane gridded aluminum window",
            'unit_price' => 235,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 8,
            'option_catalog_id' => 144
            ]);

        $this->setOption(['name' => "6' x 7' Roll-up Door", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "6'x7' Roll-up door",
            'unit_price' => 570,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 17,
            'option_catalog_id' => 145
            ]);

        $this->setOption(['name' => "9' x 7' Roll-up Door", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "9'x7' Roll-up door",
            'unit_price' => 595,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 18,
            'option_catalog_id' => 146
            ]);

        $this->setOption(['name' => "10' Horse Stall", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "10' wide horse stall, 3/4 CCA kickboard",
            'unit_price' => 775,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 9,
            'option_catalog_id' => 147
            ]);

        $this->setOption(['name' => "6' Ramp", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "6' Wide ramp",
            'unit_price' => 200,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 10,
            'option_catalog_id' => 148
            ]);

        $this->setOption(['name' => "4' Ramp", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "4' Wide ramp",
            'unit_price' => 150,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 11,
            'option_catalog_id' => 149
            ]);

        $this->setOption(['name' => "Additional Paint Color", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "Extra Paint Color (Accent only)",
            'unit_price' => 100,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 12,
            'option_catalog_id' => 150
            ]);

        $this->setOption(['name' => "Custom Paint Color", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "Custom paint color",
            'unit_price' => 50,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 13,
            'option_catalog_id' => 151
            ]);

        $this->setOption(['name' => "Custom wall height (shorter than standard)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => NULL,
            'description' => "Custom wall height (shorter than standard)",
            'unit_price' => 50,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 152
            ]);

        $this->setOption(['name' => "8' Wide Extra Strong ($/lf)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => "building_length",
            'description' => "8' Wide extra strength floor (12\" O.C) (1 lf)",
            'unit_price' => 5,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 153
            ]);

        $this->setOption(['name' => "10' Wide Extra Strong ($/lf)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => "building_length",
            'description' => "10' Wide extra strength floor (12\" O.C) (1 lf)",
            'unit_price' => 6,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 154
            ]);

        $this->setOption(['name' => "12' Wide Extra Strong Floor ($/lf)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => "building_length",
            'description' => "12' Wide extra strength floor (12\" O.C) (1 lf)",
            'unit_price' => 8,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 155
            ]);

        $this->setOption(['name' => "Wiring Package", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Electrical"),
            'force_quantity' => NULL,
            'description' => "100 amp panel, 5 - 20 amp circuits, up to 12 boxes",
            'unit_price' => 750,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 156
            ]);

        $this->setOption(['name' => "Manufacturer Discount", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Discount"),
            'force_quantity' => NULL,
            'description' => "Discount set by the manufacturer",
            'unit_price' => -1,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 157
            ]);

        $this->setOption(['name' => "Site Build", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Miscellaneous"),
            'force_quantity' => NULL,
            'description' => "On-site build (Greater of $800 or 10%)",
            'unit_price' => 1,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 158
            ]);

        $this->setOption(['name' => "Dry Wall Framing", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "Framing for drywall",
            'unit_price' => 250,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 159
            ]);

        $this->setOption(['name' => "8' Wide Spray Foam (per linear foot of building length)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Insulation"),
            'force_quantity' => NULL,
            'description' => "Spray foam for 8 wide building (1 LF)",
            'unit_price' => 65,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 160
            ]);

        $this->setOption(['name' => "10' Wide Spray Foam (per linear foot of building length)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Insulation"),
            'force_quantity' => NULL,
            'description' => "Spray foam for 10 wide building (1 LF)",
            'unit_price' => 70,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 161
            ]);

        $this->setOption(['name' => "12' Wide Spray Foam (per linear foot of building length)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Insulation"),
            'force_quantity' => NULL,
            'description' => "Spray foam for 12 wide building (1 LF)",
            'unit_price' => 85,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 162
            ]);

        $this->setOption(['name' => "14' Wide Spray Foam (per linear foot of building length)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Insulation"),
            'force_quantity' => NULL,
            'description' => "Spray foam for 14 wide building (1 LF)",
            'unit_price' => 90,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 163
            ]);

        $this->setOption(['name' => "Miscellaneous", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "Finished Interior (varies)",
            'unit_price' => 1,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 164
            ]);

        $this->setOption(['name' => "Econ Door", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Economy door",
            'unit_price' => 85,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 19,
            'option_catalog_id' => 165
            ]);

        $this->setOption(['name' => "Custom Shingle Color", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "Custom shingle color",
            'unit_price' => 100,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 14,
            'option_catalog_id' => 166
            ]);

        $this->setOption(['name' => "Shelf/Workbench(16\") (per Linear Foot)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "16\" Shelf (1 LF)",
            'unit_price' => 8,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 6,
            'option_catalog_id' => 167
            ]);

        $this->setOption(['name' => "Shutters", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "3' Shutters",
            'unit_price' => 55,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 15,
            'option_catalog_id' => 168
            ]);

        $this->setOption(['name' => "12' Wide Radiant Barrier Insulation ($/lf)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Insulation"),
            'force_quantity' => NULL,
            'description' => "12' Wide Radiant Barrier Insulation ($/lf)",
            'unit_price' => 30,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 6,
            'option_catalog_id' => 169
            ]);

        $this->setOption(['name' => "2'x2' Tinted Skylight", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "2'x2' Tinted Skylight (shingle roof only)",
            'unit_price' => 250,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 16,
            'option_catalog_id' => 170
            ]);

        $this->setOption(['name' => "Ridge Vent ($/lf)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "Ridge Vent ($/lf)",
            'unit_price' => 4,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 17,
            'option_catalog_id' => 171
            ]);

        $this->setOption(['name' => "220 V - 20 Amp Box - outlet not included (requires standard wire package)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Electrical"),
            'force_quantity' => NULL,
            'description' => "220 V - 20 Amp Box - outlet not included (requires standard wire package) ",
            'unit_price' => 75,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 172
            ]);

        $this->setOption(['name' => "220 V - 30 Amp Box - outlet not included (requires standard wire package)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Electrical"),
            'force_quantity' => NULL,
            'description' => "220 V - 30 Amp Box - outlet not included (requires standard wire package)",
            'unit_price' => 125,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 173
            ]);

        $this->setOption(['name' => "Electrical Box - GFI Upgrade (requires standard wiring package)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Electrical"),
            'force_quantity' => NULL,
            'description' => "Electrical Box - GFI Upgrade, with or without outdoor cover (requires standard wiring package)",
            'unit_price' => 50,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 174
            ]);

        $this->setOption(['name' => "Electrical - 3 Way Switch (Requires standard wiring package)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Electrical"),
            'force_quantity' => NULL,
            'description' => "Electrical - 3 Way Switch setup, includes both switches (Requires standard wiring package)",
            'unit_price' => 75,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 175
            ]);

        $this->setOption(['name' => "Extra Electrical Box - (requires standard wiring package)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Electrical"),
            'force_quantity' => NULL,
            'description' => "Extra Electrical Box - (requires standard wiring package)",
            'unit_price' => 50,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 6,
            'option_catalog_id' => 176
            ]);

        $this->setOption(['name' => "Interior wall (1lf)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "Interior wall (1 linear foot), framing only.",
            'unit_price' => 15,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 7,
            'option_catalog_id' => 177
            ]);

        $this->setOption(['name' => "AC Framing", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "Framing for in-wall AC unit (requires dimensions from customer, up to 4'x3')",
            'unit_price' => 75,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 18,
            'option_catalog_id' => 178
            ]);

        $this->setOption(['name' => "9' Ramp", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "9' wide ramp",
            'unit_price' => 300,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 19,
            'option_catalog_id' => 179
            ]);

        $this->setOption(['name' => "15-light French doors (6'-8\") ", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "15-light steel French Walk-in doors, 6'-8\" high",
            'unit_price' => 700,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 20,
            'option_catalog_id' => 180
            ]);

        $this->setOption(['name' => "Steel French doors (6'-8\")", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Steel French doors, 6'-8\" high",
            'unit_price' => 600,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 21,
            'option_catalog_id' => 181
            ]);

        $this->setOption(['name' => "Flower Box", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 75,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 20,
            'option_catalog_id' => 182
            ]);

        $this->setOption(['name' => "21\" Cupola", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "21\" Cupola",
            'unit_price' => 495,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 21,
            'option_catalog_id' => 183
            ]);

        $this->setOption(['name' => "14' Wide Deck", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 500,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 184
            ]);

        $this->setOption(['name' => "4' Ramp (VETERAN DISCOUNT ONLY)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 0,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 22,
            'option_catalog_id' => 185
            ]);

        $this->setOption(['name' => "Double Shed Doors (w/ transom)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 450,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 22,
            'option_catalog_id' => 186
            ]);

        $this->setOption(['name' => "29\" Transom Window", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 100,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 9,
            'option_catalog_id' => 187
            ]);

        $this->setOption(['name' => "60\" Transom window", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 105,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 10,
            'option_catalog_id' => 188
            ]);

        $this->setOption(['name' => "10' Wide Radiant Barrier Insulation ($/lf)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Insulation"),
            'force_quantity' => NULL,
            'description' => "10' Wide Radiant Barrier Insulation ($/lf)",
            'unit_price' => 27,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 7,
            'option_catalog_id' => 189
            ]);

        $this->setOption(['name' => "8' Wide Radiant Barrier Insulation ($/lf)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Insulation"),
            'force_quantity' => NULL,
            'description' => "8' Wide Radiant Barrier Insulation ($/lf)",
            'unit_price' => 24,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 8,
            'option_catalog_id' => 190
            ]);

        $this->setOption(['name' => "8-ft wall height ($/lf)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 7,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 191
            ]);

        $this->setOption(['name' => "1'x1' Loft Window", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 75,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 11,
            'option_catalog_id' => 192
            ]);

        $this->setOption(['name' => "Interior Door Framing (requires door dimensions)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 75,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 8,
            'option_catalog_id' => 193
            ]);

        $this->setOption(['name' => "12-wide Metal (Barn)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "",
            'unit_price' => 13,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 194
            ]);

        $this->setOption(['name' => "8-wide Metal (Barn)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "",
            'unit_price' => 10,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 195
            ]);

        $this->setOption(['name' => "10-Wide Metal Roof (Barn)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "",
            'unit_price' => 12,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 196
            ]);

        $this->setOption(['name' => "8-Wide Metal Gable", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "",
            'unit_price' => 9,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 197
            ]);

        $this->setOption(['name' => "10-Wide Metal Gable", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "",
            'unit_price' => 10,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 198
            ]);

        $this->setOption(['name' => "12-Wide Metal Gable", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "",
            'unit_price' => 11,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 6,
            'option_catalog_id' => 199
            ]);

        $this->setOption(['name' => "14' Wide Loft", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "14' Wide Loft",
            'unit_price' => 25,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 9,
            'option_catalog_id' => 200
            ]);

        $this->setOption(['name' => "14-Wide Metal Gable", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "",
            'unit_price' => 15,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 7,
            'option_catalog_id' => 201
            ]);

        $this->setOption(['name' => "Floor Insulation (Closed Cell, R13) ($/sqft)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Insulation"),
            'force_quantity' => NULL,
            'description' => "Spray foam floor insulation, closed cell 2\" at R13 (1 sqft)",
            'unit_price' => 3.6,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 9,
            'option_catalog_id' => 202
            ]);

        $this->setOption(['name' => "Floor Insulation (Closed Cell, R26) ($/sqft)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Insulation"),
            'force_quantity' => NULL,
            'description' => "Spray foam floor insulation, closed cell 4\" at R26 (1 sqft)",
            'unit_price' => 7.2,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 10,
            'option_catalog_id' => 203
            ]);

        $this->setOption(['name' => "Electrical - 4 Way Switch (Requires standard wiring package)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Electrical"),
            'force_quantity' => NULL,
            'description' => "Electrical - 4 Way Switch setup, includes 3 switches (Requires standard wiring package)",
            'unit_price' => 125,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 7,
            'option_catalog_id' => 204
            ]);

        $this->setOption(['name' => "14' Extra Strong Floor", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => "building_length",
            'description' => "14' Extra Strong Floor",
            'unit_price' => 9,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 6,
            'option_catalog_id' => 205
            ]);

        $this->setOption(['name' => "10% Home Show Discount", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Discount"),
            'force_quantity' => NULL,
            'description' => "10% Home Show Discount",
            'unit_price' => -1,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 206
            ]);

        $this->setOption(['name' => "4' Shed Door (w/transom)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "4' Shed door with 29\" transom window",
            'unit_price' => 265,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 23,
            'option_catalog_id' => 207
            ]);

        $this->setOption(['name' => "3' Shed Door (w/transom)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3' Shed door with 29\" transom window",
            'unit_price' => 250,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 24,
            'option_catalog_id' => 208
            ]);

        $this->setOption(['name' => "Extra Solar Blaster Vent", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "Extra Solar Blaster Vent",
            'unit_price' => 190,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 23,
            'option_catalog_id' => 209
            ]);

        $this->setOption(['name' => "9 ft. Wall Height ($/lf)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => "building_length",
            'description' => "9 ft. wall height, 7/16 Smartside, 2x4 framing",
            'unit_price' => 38,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 7,
            'option_catalog_id' => 210
            ]);

        $this->setOption(['name' => "Site Build", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Permit"),
            'force_quantity' => NULL,
            'description' => "Site build $650 or 15% whichever is greater",
            'unit_price' => 1,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 211
            ]);

        $this->setOption(['name' => "4' Dutch Shed Door", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "4'x6' Dutch Shed Door (Horizontal split)",
            'unit_price' => 300,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 25,
            'option_catalog_id' => 212
            ]);

        $this->setOption(['name' => "3'x2' Opaque Skylight ", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Exterior"),
            'force_quantity' => NULL,
            'description' => "3'x2' Opaque Skylight (metal roof only)",
            'unit_price' => 250,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 24,
            'option_catalog_id' => 213
            ]);

        $this->setOption(['name' => "8-Wide Metal (Lean-to)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "",
            'unit_price' => 11,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 8,
            'option_catalog_id' => 214
            ]);

        $this->setOption(['name' => "Double Dutch Shed Door (Horizontal split)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Double 4'x6' Dutch Shed Door (Horizontal split)",
            'unit_price' => 500,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 26,
            'option_catalog_id' => 215
            ]);

        $this->setOption(['name' => "Shingle Roof", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "",
            'unit_price' => 0,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 9,
            'option_catalog_id' => 216
            ]);

        $this->setOption(['name' => "Metalic Roof 1' (lf)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "",
            'unit_price' => 0,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 10,
            'option_catalog_id' => 217
            ]);

        $this->setOption(['name' => "LP Smartside Trim", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Trim"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 0,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 218
            ]);

        $this->setOption(['name' => "LP Smartside Siding ", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Siding"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 0,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 1,
            'option_catalog_id' => 219
            ]);

        $this->setOption(['name' => "Aluminum corner trim", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Trim"),
            'force_quantity' => NULL,
            'description' => "Aluminum corner trim, Urban Econ only",
            'unit_price' => 0,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 220
            ]);

        $this->setOption(['name' => "Econ Shingle Roof", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "Shingle roof, Urban Econ Black only.",
            'unit_price' => 0,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 11,
            'option_catalog_id' => 221
            ]);

        $this->setOption(['name' => "8'x8' Roll-up Door", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "8'x8' Roll-up Garage Door",
            'unit_price' => 625,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 27,
            'option_catalog_id' => 222
            ]);

        $this->setOption(['name' => "Interior finish ($/sqft)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "Painted Fiber SmartSide, corrugated galvanized metal ceiling, includes one door and beam wrap",
            'unit_price' => 12,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 10,
            'option_catalog_id' => 223
            ]);

        $this->setOption(['name' => "Interior window finish (per window)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "Window wrap, painted SmartSide Fiber and trim",
            'unit_price' => 40,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 11,
            'option_catalog_id' => 224
            ]);

        $this->setOption(['name' => "Interior door finish (per 3' door)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "Interior door finish (per 3' door)",
            'unit_price' => 50,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 12,
            'option_catalog_id' => 225
            ]);

        $this->setOption(['name' => "6 Wide Metal (Lean To)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => "building_length",
            'description' => "6 Wide Metal (Lean To)",
            'unit_price' => 10,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 12,
            'option_catalog_id' => 226
            ]);

        $this->setOption(['name' => "Engineered Drawings (for permit submittal, >40# snowloads)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Permit"),
            'force_quantity' => NULL,
            'description' => "Engineered drawings for permit submittal when required snowloads exceed 40#. Includes jurisdictional building modifications (hardware, framing and nailing schedules). ",
            'unit_price' => 1150,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 227
            ]);

        $this->setOption(['name' => "2x6 Studs ($/lf)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => "building_length",
            'description' => "2x6 walls. Price is per linear foot of building.",
            'unit_price' => 18,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 8,
            'option_catalog_id' => 228
            ]);

        $this->setOption(['name' => "Engineered Drawings (for permit submittal, no snowload)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Permit"),
            'force_quantity' => NULL,
            'description' => "Engineered drawings for permit submittal, no snowloads. Includes jurisdictional building modifications (hardware, framing and nailing schedules). ",
            'unit_price' => 250,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 229
            ]);

        $this->setOption(['name' => "Engineered Drawings (for permit submittal, up to 40# snowloads)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Permit"),
            'force_quantity' => NULL,
            'description' => "Engineered drawings for permit submittal when required snowloads are up to 40#. Includes jurisdictional building modifications (hardware, framing and nailing schedules). ",
            'unit_price' => 750,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 230
            ]);

        $this->setOption(['name' => "Wrap Around Porch (12' x12')", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "12' by 12' wrap around porch",
            'unit_price' => 920,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 231
            ]);

        $this->setOption(['name' => "Dealer Commission Discount", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Discount"),
            'force_quantity' => NULL,
            'description' => "Discount at the dealer's discretion, will be deducted from the dealer's commission. ",
            'unit_price' => -1,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 232
            ]);

        $this->setOption(['name' => "Used Building Discount", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Discount"),
            'force_quantity' => NULL,
            'description' => "Discount to apply to used buildings",
            'unit_price' => -1,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 233
            ]);

        $this->setOption(['name' => "8' Wall Height (8' Wide Buildings)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => "building_length",
            'description' => "8' Wall height, 8' wide buildings",
            'unit_price' => 15,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 9,
            'option_catalog_id' => 234
            ]);

        $this->setOption(['name' => "7' Wall Height ($/lf)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => "building_length",
            'description' => "7' Wall height",
            'unit_price' => 8,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 10,
            'option_catalog_id' => 235
            ]);

        $this->setOption(['name' => "2x6 w/9-ft wall height ($/lf)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => "building_length",
            'description' => "2x6 w/9-ft wall height, 7/16 Smartside ",
            'unit_price' => 51,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 11,
            'option_catalog_id' => 236
            ]);

        $this->setOption(['name' => "14' Wide Loft (($/lf))", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "14' Wide Loft ($/lf)",
            'unit_price' => 37.5,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 13,
            'option_catalog_id' => 237
            ]);

        $this->setOption(['name' => "14'x4' Deck", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "14'x4' deck and posts",
            'unit_price' => 885,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 6,
            'option_catalog_id' => 238
            ]);

        $this->setOption(['name' => "12' Wide extra strength floor (1 lf)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => NULL,
            'description' => "12' Wide extra strength floor (12\" O.C) (1 lf)",
            'unit_price' => 9.35,
            'is_active' => "no",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 12,
            'option_catalog_id' => 239
            ]);

        $this->setOption(['name' => "14-Wide Metal (Barn)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Roof"),
            'force_quantity' => NULL,
            'description' => "14-Wide Metal (Barn)",
            'unit_price' => 17,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 13,
            'option_catalog_id' => 240
            ]);

        $this->setOption(['name' => "Wrap Around Porch (14' x14')", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "14' x 14' Wrap Around Porch ",
            'unit_price' => 1020,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 7,
            'option_catalog_id' => 241
            ]);

        $this->setOption(['name' => "5\" - 10\" Overhangs", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => "building_length",
            'description' => "5\" - 10\" Overhangs",
            'unit_price' => 16,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 13,
            'option_catalog_id' => 242
            ]);

        $this->setOption(['name' => "Cupola", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Miscellaneous"),
            'force_quantity' => NULL,
            'description' => "21\"x21\" Cupola",
            'unit_price' => 265,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 243
            ]);

        $this->setOption(['name' => "Crane Set (Small Building)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Miscellaneous"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 1050,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 244
            ]);

        $this->setOption(['name' => "No Paint Discount", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Miscellaneous"),
            'force_quantity' => "building_length",
            'description' => "No Paint",
            'unit_price' => -12,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 245
            ]);

        $this->setOption(['name' => "No Roofing Discount", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Miscellaneous"),
            'force_quantity' => "building_length",
            'description' => "No Roofing",
            'unit_price' => -13,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 246
            ]);

        $this->setOption(['name' => "House Wrap Walls (Tyvek)", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Miscellaneous"),
            'force_quantity' => "floor_area",
            'description' => "House Wrap",
            'unit_price' => 1,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 6,
            'option_catalog_id' => 247
            ]);

        $this->setOption(['name' => "Tack Room 6'", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => NULL,
            'description' => "Tack Room 6' (10' wide bldg)",
            'unit_price' => 450,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 14,
            'option_catalog_id' => 248
            ]);

        $this->setOption(['name' => "Tack Room 6'", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => NULL,
            'description' => "Tack Room 6' (12' wide bldg)",
            'unit_price' => 495,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 15,
            'option_catalog_id' => 249
            ]);

        $this->setOption(['name' => "No Floor Discount", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => "building_length",
            'description' => "No Flooring Discount 10' wide",
            'unit_price' => 9.35,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 14,
            'option_catalog_id' => 250
            ]);

        $this->setOption(['name' => "No Floor Discount ", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => "building_length",
            'description' => "No Flooring Discount 12' wide",
            'unit_price' => 11,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 15,
            'option_catalog_id' => 251
            ]);

        $this->setOption(['name' => "No Door", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "No Door",
            'unit_price' => 0,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 28,
            'option_catalog_id' => 252
            ]);

        $this->setOption(['name' => "Horse Stall Sheathing", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => "building_length",
            'description' => "Horse Stall Sheathing 4' high Larrabee",
            'unit_price' => 9.27,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 16,
            'option_catalog_id' => 253
            ]);

        $this->setOption(['name' => "2' Ramp", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Order"),
            'force_quantity' => NULL,
            'description' => "2' Ramp Section",
            'unit_price' => 90,
            'is_active' => "yes",
            'rto' => 1,
            'taxable' => 1,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 8,
            'option_catalog_id' => 254
            ]);

        $this->setOption(['name' => "Increased Snow Load", 'manufacturer_company_id' => 4], [
            'category_id' => $this->getOptionCategoryId("Structural"),
            'force_quantity' => "floor_area",
            'description' => "2x6 trusses",
            'unit_price' => 1.5,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => "equal_to",
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 16,
            'option_catalog_id' => 255
            ]);

        $this->setOption(['name' => "Exterior - Misc.", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Miscellaneous"),
            'force_quantity' => NULL,
            'description' => "Miscellaneous exterior options",
            'unit_price' => 1,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 2,
            'option_catalog_id' => 256
            ]);

        $this->setOption(['name' => "Engineered drawings", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Order"),
            'force_quantity' => NULL,
            'description' => "Engineered drawings (credited against building when ready to purchase).",
            'unit_price' => 1000,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 257
            ]);

        $this->setOption(['name' => "Permit acquired discount", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Order"),
            'force_quantity' => NULL,
            'description' => "Discount for deposit against engineered drawings",
            'unit_price' => -750,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 258
            ]);

        $this->setOption(['name' => "Doors - Misc.", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Miscellaneous"),
            'force_quantity' => NULL,
            'description' => "Miscellaneous door option",
            'unit_price' => 1,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 259
            ]);

        $this->setOption(['name' => "Windows - Misc.", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Miscellaneous"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 1,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 4,
            'option_catalog_id' => 260
            ]);

        $this->setOption(['name' => "Interior - Misc.", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Miscellaneous"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 1,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 5,
            'option_catalog_id' => 261
            ]);

        $this->setOption(['name' => "Dealer Commission Discount", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Order"),
            'force_quantity' => NULL,
            'description' => "Discount at the dealer's discretion, will be deducted from the dealer's commission. ",
            'unit_price' => -1,
            'is_active' => "yes",
            'rto' => 1,
            'taxable' => 1,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 9,
            'option_catalog_id' => 232
            ]);

        $this->setOption(['name' => "Used Building Discount", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Discount"),
            'force_quantity' => NULL,
            'description' => "Discount to apply to used buildings",
            'unit_price' => -1,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 3,
            'option_catalog_id' => 233
            ]);

        $this->setOption(['name' => "Extra Delivery Charge", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Order"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 1,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 10,
            'option_catalog_id' => 262
            ]);

        $this->setOption(['name' => "3-0 6-0 LH Steel Paneled Door", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3-0 6-0 door. Hinges on left, facing door from the outside of building",
            'unit_price' => 300,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 9,
            'option_catalog_id' => NULL
            ]);

        $this->setOption(['name' => "3-0 6-0 RH Steel Paneled Door", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3-0 6-0 door. Hinges on right, facing door from the outside of building",
            'unit_price' => 300,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 8,
            'option_catalog_id' => NULL
            ]);

        $this->setOption(['name' => "3-0 6-8 LH Steel Paneled Door", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3-0 6-8 door. Hinges on left, facing door from the outside of building",
            'unit_price' => 300,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 11,
            'option_catalog_id' => NULL
            ]);

        $this->setOption(['name' => "3-0 6-0 LH Steel Paneled 9-light Door", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3-0 6-0 door. Hinges on left, facing door from the outside of building. 9 light.",
            'unit_price' => 350,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 13,
            'option_catalog_id' => NULL
            ]);

        $this->setOption(['name' => "3-0 6-8 LH Steel Paneled Door 9-light", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3-0 6-8 door. Hinges on left, facing door from the outside of building",
            'unit_price' => 350,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 15,
            'option_catalog_id' => NULL
            ]);

        $this->setOption(['name' => "3-0 6-8 RH Steel Paneled Door 9-light", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3-0 6-8 door. Hinges on right, facing door from the outside of building",
            'unit_price' => 350,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 14,
            'option_catalog_id' => NULL
            ]);

        $this->setOption(['name' => "29\" Transom Gable Window", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "29\" transom window, installed in gable.",
            'unit_price' => 85,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 7,
            'option_catalog_id' => NULL
            ]);

        $this->setOption(['name' => "60\" Transom Gable Window", 'manufacturer_company_id' => 2], [
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "60\" transom window, installed in gable.",
            'unit_price' => 115,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'is_default' => 0,
            'sort_id' => 9,
            'option_catalog_id' => NULL
            ]);


        Log::info(__CLASS__ . ' Update option END');
    }

/**
     * @param array $params
     * @param $payload
     */
private function setOption($params = [], $payload)
{
    $option = Option::UpdateOrCreate($params, $payload);
    $option->option_catalog_id = $this->getOptionCatalogId($option->name);
    $option->save();
}
}
