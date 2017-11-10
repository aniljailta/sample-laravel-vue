<?php

use App\Models\Option;
use App\Models\OptionCatalog;
use App\Models\OptionCategory;
use Illuminate\Database\Seeder;

class OptionsTableSeeder extends Seeder
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

            $this->fetchOptionCategories();
            $this->updateOptionCatalog();
            $this->map3dOptions();

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

    private function updateOptionCatalog()
    {
        Log::info(__CLASS__ . ' Update option catalog START');

        $this->setOptionCatalog(['name' => "8' Wide Loft ($/lf)"], [
            'description' => "8' wide loft (1 lf)",
            'unit_price' => 15,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Interior'),
        ]);

        $this->setOptionCatalog(['name' => "10' Wide Loft ($/lf)"], [
            'description' => "10' wide loft (1 lf)",
            'unit_price' => 20,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Interior'),
        ]);

        $this->setOptionCatalog(['name' => "12' Wide Loft (($/lf))"], [
            'description' => "12' wide loft (1 lf)",
            'unit_price' => 20,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Interior'),
        ]);

        $this->setOptionCatalog(['name' => "8' x 4' Deck"], [
            'description' => "8' x 4' deck and posts",
            'unit_price' => 340,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Deck'),
        ]);

        $this->setOptionCatalog(['name' => "10' x 4' Deck"], [
            'description' => "10'x4' deck and posts",
            'unit_price' => 380,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Deck'),
        ]);

        $this->setOptionCatalog(['name' => "12' x 4' Deck"], [
            'description' => "12'x4' deck and posts",
            'unit_price' => 440,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Deck'),
        ]);

        $this->setOptionCatalog(['name' => "10' Horse Stall"], [
            'description' => "10' wide horse stall, 3/4 CCA kickboard",
            'unit_price' => 775,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Deck'),
        ]);

        $this->setOptionCatalog(['name' => "Wrap Around Porch(12' x 12')"], [
            'description' => "12' by 12' wrap around porch",
            'unit_price' => 875,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Deck'),
        ]);

        $this->setOptionCatalog(['name' => "Steel handrail"], [
            'description' => "Steel handrail panel (up to 5')",
            'unit_price' => 85,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Deck'),
        ]);

        $this->setOptionCatalog(['name' => "4' Shed Door"], [
            'description' => "4'x6' Heavy-duty shed door",
            'unit_price' => 190,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Door'),
        ]);

        $this->setOptionCatalog(['name' => "3' Shed Door"], [
            'description' => "3'x6' Heavy-duty shed door",
            'unit_price' => 175,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Door'),
        ]);

        $this->setOptionCatalog(['name' => "Double Shed Door"], [
            'description' => "Double 3'x6' Heavy-duty shed doors",
            'unit_price' => 350,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Door'),
        ]);

        $this->setOptionCatalog(['name' => "Steel Walk-in Door"], [
            'description' => "3-0 Steel Door",
            'unit_price' => 300,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Door'),
        ]);

        $this->setOptionCatalog(['name' => "Steel 9-light Walk-in Door"], [
            'description' => "3' 9-light steel walk-in door",
            'unit_price' => 350,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Door'),
        ]);

        $this->setOptionCatalog(['name' => "2' x 3' Single Pane Window"], [
            'description' => "2'x3' Single pane gridded aluminum window",
            'unit_price' => 100,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Window'),
        ]);

        $this->setOptionCatalog(['name' => "3' x'3 Single Pane Window"], [
            'description' => "3'x3' Single pane gridded aluminum window",
            'unit_price' => 115,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Window'),
        ]);

        $this->setOptionCatalog(['name' => "2' x 3' Double Pane Window"], [
            'description' => "2'x3' Double pane gridded aluminum window",
            'unit_price' => 200,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Window'),
        ]);

        $this->setOptionCatalog(['name' => "3' x 3' Double Pane Window"], [
            'description' => "3'x3' Double pane gridded aluminum window",
            'unit_price' => 235,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Window'),
        ]);

        $this->setOptionCatalog(['name' => "6' x 7' Roll-up Door"], [
            'description' => "6'x7' Roll-up door",
            'unit_price' => 575,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Door'),
        ]);

        $this->setOptionCatalog(['name' => "9' x 7' Roll-up Door"], [
            'description' => "9'x7' Roll-up door",
            'unit_price' => 625,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Door'),
        ]);

        $this->setOptionCatalog(['name' => "6' Ramp"], [
            'description' => "6' Wide ramp",
            'unit_price' => 200,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Exterior'),
        ]);

        $this->setOptionCatalog(['name' => "4' Ramp"], [
            'description' => "4' Wide ramp",
            'unit_price' => 150,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Exterior'),
        ]);

        $this->setOptionCatalog(['name' => "Additional Paint Color"], [
            'description' => "Extra Paint Color (Accent only)",
            'unit_price' => 100,
            'force_quantity' => NULL,
            'is_active' => 'no',
            'category_id' => $this->getOptionCategoryId('Exterior'),
        ]);

        $this->setOptionCatalog(['name' => "Custom Paint Color"], [
            'description' => "Custom paint color",
            'unit_price' => 50,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Exterior'),
        ]);

        $this->setOptionCatalog(['name' => "Custom wall height (shorter than standard)"], [
            'description' => "Custom wall height (shorter than standard)",
            'unit_price' => 50,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Structural'),
        ]);

        $this->setOptionCatalog(['name' => "8' Wide Extra Strong ($/lf)"], [
            'description' => "8' Wide extra strength floor (12 \"O.C)(1 lf)",
            'unit_price' => 5,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Structural'),
        ]);

        $this->setOptionCatalog(['name' => "10' Wide Extra Strong ($/lf)"], [
            'description' => "10' Wide extra strength floor (12 \"O.C)(1 lf)",
            'unit_price' => 6,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Structural'),
        ]);

        $this->setOptionCatalog(['name' => "12' Wide Extra Strong Floor ($/lf)"], [
            'description' => "12' Wide extra strength floor (12 \"O.C)(1 lf)",
            'unit_price' => 8,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Structural'),
        ]);

        $this->setOptionCatalog(['name' => "Wiring Package"], [
            'description' => "100 amp panel, 5 - 20 amp circuits, up to 12 boxes",
            'unit_price' => 750,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Electrical'),
        ]);

        $this->setOptionCatalog(['name' => "Manufacturer Discount"], [
            'description' => "Discount set by the manufacturer",
            'unit_price' => -1,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => NULL,
        ]);

        $this->setOptionCatalog(['name' => "Dry Wall Framing"], [
            'description' => "Framing for drywall",
            'unit_price' => 250,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Interior'),
        ]);

        $this->setOptionCatalog(['name' => "8' Wide Spray Foam (per linear foot of building length)"], [
            'description' => "Spray foam for 8 wide building (1 LF)",
            'unit_price' => 65,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Insulation'),
        ]);

        $this->setOptionCatalog(['name' => "10' Wide Spray Foam (per linear foot of building length)"], [
            'description' => "Spray foam for 10 wide building (1 LF)",
            'unit_price' => 70,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Insulation'),
        ]);

        $this->setOptionCatalog(['name' => "12' Wide Spray Foam (per linear foot of building length)"], [
            'description' => "Spray foam for 12 wide building (1 LF)",
            'unit_price' => 85,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Insulation'),
        ]);

        $this->setOptionCatalog(['name' => "14' Wide Spray Foam (per linear foot of building length)"], [
            'description' => "Spray foam for 14 wide building (1 LF)",
            'unit_price' => 90,
            'force_quantity' => NULL,
            'is_active' => 'no',
            'category_id' => $this->getOptionCategoryId('Insulation'),
        ]);

        $this->setOptionCatalog(['name' => "Miscellaneous"], [
            'description' => "Finished Interior (varies)",
            'unit_price' => 1,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Interior'),
        ]);

        $this->setOptionCatalog(['name' => "Econ Door"], [
            'description' => "Economy door",
            'unit_price' => 85,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Door'),
        ]);

        $this->setOptionCatalog(['name' => "Custom Shingle Color"], [
            'description' => "Custom shingle color",
            'unit_price' => 100,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Exterior'),
        ]);

        $this->setOptionCatalog(['name' => "Shelf/Workbench(20\")(per Linear Foot)"], [
            'description' => "20\"Shelf(1 LF)",
            'unit_price' => 10,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Interior'),
        ]);

        $this->setOptionCatalog(['name' => "Shutters"], [
            'description' => "3' Shutters",
            'unit_price' => 55,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Exterior'),
        ]);

        $this->setOptionCatalog(['name' => "12' Wide Radiant Barrier Insulation ($/lf)"], [
            'description' => "12' Wide Radiant Barrier Insulation ($/lf)",
            'unit_price' => 30,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Insulation'),
        ]);

        $this->setOptionCatalog(['name' => "2'x2' Tinted Skylight"], [
            'description' => "2'x2' Tinted Skylight (shingle roof only)",
            'unit_price' => 250,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Exterior'),
        ]);

        $this->setOptionCatalog(['name' => "Ridge Vent ($/lf)"], [
            'description' => "Ridge Vent ($/lf)",
            'unit_price' => 4,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Exterior'),
        ]);

        $this->setOptionCatalog(['name' => "220 V - 20 Amp Box - outlet not included (requires standard wire package)"], [
            'description' => "220 V - 20 Amp Box - outlet not included (requires standard wire package) ",
            'unit_price' => 75,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Electrical'),
        ]);

        $this->setOptionCatalog(['name' => "220 V - 30 Amp Box - outlet not included (requires standard wire package)"], [
            'description' => "220 V - 30 Amp Box - outlet not included (requires standard wire package)",
            'unit_price' => 125,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Electrical'),
        ]);

        $this->setOptionCatalog(['name' => "Electrical Box - GFI Upgrade (requires standard wiring package)"], [
            'description' => "Electrical Box - GFI Upgrade, with or without outdoor cover (requires standard wiring package)",
            'unit_price' => 50,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Electrical'),
        ]);

        $this->setOptionCatalog(['name' => "Electrical - 3 Way Switch (Requires standard wiring package)"], [
            'description' => "Electrical - 3 Way Switch setup, includes both switches (Requires standard wiring package)",
            'unit_price' => 75,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Electrical'),
        ]);

        $this->setOptionCatalog(['name' => "Extra Electrical Box - (requires standard wiring package)"], [
            'description' => "Extra Electrical Box - (requires standard wiring package)",
            'unit_price' => 50,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Electrical'),
        ]);

        $this->setOptionCatalog(['name' => "Interior wall (1lf)"], [
            'description' => "Interior wall (1 linear foot), framing only.",
            'unit_price' => 15,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Interior'),
        ]);

        $this->setOptionCatalog(['name' => "AC Framing"], [
            'description' => "Framing for in-wall AC unit (requires dimensions from customer, up to 4'x3')",
            'unit_price' => 75,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Exterior'),
        ]);

        $this->setOptionCatalog(['name' => "9' Ramp"], [
            'description' => "9' wide ramp",
            'unit_price' => 300,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Exterior'),
        ]);

        $this->setOptionCatalog(['name' => "15-light French doors (6'-8\")"], [
            'description' => "15-light steel French Walk-in doors, 6'-8\" high",
            'unit_price' => 700,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Door'),
        ]);

        $this->setOptionCatalog(['name' => "Steel French doors (6'-8\")"], [
            'description' => "Steel French doors, 6'-8\" high",
            'unit_price' => 600,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Door'),
        ]);

        $this->setOptionCatalog(['name' => "Flower Box"], [
            'description' => "",
            'unit_price' => 75,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Exterior'),
        ]);

        $this->setOptionCatalog(['name' => "21\" Cupola"], [
            'description' => "21\" Cupola",
            'unit_price' => 495,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Exterior'),
        ]);

        $this->setOptionCatalog(['name' => "14' Wide Deck"], [
            'description' => "",
            'unit_price' => 500,
            'force_quantity' => NULL,
            'is_active' => 'no',
            'category_id' => $this->getOptionCategoryId('Deck'),
        ]);

        $this->setOptionCatalog(['name' => "4' Ramp (VETERAN DISCOUNT ONLY)"], [
            'description' => "",
            'unit_price' => 0,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Exterior'),
        ]);

        $this->setOptionCatalog(['name' => "Double Shed Doors (w/ transom)"], [
            'description' => "",
            'unit_price' => 450,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Door'),
        ]);

        $this->setOptionCatalog(['name' => "29\" Transom Window"], [
            'description' => "29\" Transom Window",
            'unit_price' => 75,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Window'),
        ]);

        $this->setOptionCatalog(['name' => "29\" Transom Gable Window"], [
            'description' => "29\" Transom Gable Window",
            'unit_price' => 75,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Window'),
        ]);

        $this->setOptionCatalog(['name' => "60\" Transom window"], [
            'description' => "60\" Transom window",
            'unit_price' => 105,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Window'),
        ]);

        $this->setOptionCatalog(['name' => "60\" Transom Gable window"], [
            'description' => "60\" Transom Gable window",
            'unit_price' => 105,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Window'),
        ]);

        $this->setOptionCatalog(['name' => "10' Wide Radiant Barrier Insulation ($/lf)"], [
            'description' => "10' Wide Radiant Barrier Insulation ($/lf)",
            'unit_price' => 27,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Insulation'),
        ]);

        $this->setOptionCatalog(['name' => "8' Wide Radiant Barrier Insulation ($/lf)"], [
            'description' => "8' Wide Radiant Barrier Insulation ($/lf)",
            'unit_price' => 24,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Insulation'),
        ]);

        $this->setOptionCatalog(['name' => "8-ft wall height ($/lf)"], [
            'description' => "8-ft wall height ($/lf)",
            'unit_price' => 7,
            'force_quantity' => NULL,
            'is_active' => 'no',
            'category_id' => $this->getOptionCategoryId('Structural'),
        ]);

        $this->setOptionCatalog(['name' => "1'x1' Loft Window"], [
            'description' => "1'x1' Loft Window",
            'unit_price' => 75,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Window'),
        ]);

        $this->setOptionCatalog(['name' => "1'x1' Loft Gable Window"], [
            'description' => "1'x1' Loft Gable Window",
            'unit_price' => 75,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Window'),
        ]);

        $this->setOptionCatalog(['name' => "Interior Door Framing (requires door dimensions)"], [
            'description' => "Interior Door Framing (requires door dimensions)",
            'unit_price' => 75,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Interior'),
        ]);

        $this->setOptionCatalog(['name' => "12-wide Metal (Barn)"], [
            'description' => "12-wide Metal (Barn)",
            'unit_price' => 16,
            'force_quantity' => "building_length",
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Roof'),
        ]);

        $this->setOptionCatalog(['name' => "8-wide Metal (Barn)"], [
            'description' => "8-wide Metal (Barn)",
            'unit_price' => 14,
            'force_quantity' => "building_length",
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Roof'),
        ]);

        $this->setOptionCatalog(['name' => "10-Wide Metal Roof (Barn)"], [
            'description' => "10-Wide Metal Roof (Barn)",
            'unit_price' => 15,
            'force_quantity' => "building_length",
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Roof'),
        ]);

        $this->setOptionCatalog(['name' => "8-Wide Metal (Shack)"], [
            'description' => "8-Wide Metal (Shack)",
            'unit_price' => 10,
            'force_quantity' => "building_length",
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Roof'),
        ]);

        $this->setOptionCatalog(['name' => "10-Wide Metal (Shack)"], [
            'description' => "10-Wide Metal (Shack)",
            'unit_price' => 11,
            'force_quantity' => "building_length",
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Roof'),
        ]);

        $this->setOptionCatalog(['name' => "12-Wide Metal (Shack)"], [
            'description' => "12-Wide Metal (Shack)",
            'unit_price' => 12,
            'force_quantity' => "building_length",
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Roof'),
        ]);

        $this->setOptionCatalog(['name' => "14' Wide Loft"], [
            'description' => "14' Wide Loft",
            'unit_price' => 25,
            'force_quantity' => NULL,
            'is_active' => 'no',
            'category_id' => $this->getOptionCategoryId('Interior'),
        ]);

        $this->setOptionCatalog(['name' => "14-Wide Metal (Shack)"], [
            'description' => "14-Wide Metal (Shack)",
            'unit_price' => 13,
            'force_quantity' => "building_length",
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Roof'),
        ]);

        $this->setOptionCatalog(['name' => "Floor Insulation (Closed Cell, R13) ($/sqft)"], [
            'description' => "Spray foam floor insulation, closed cell 2\" at R13(1 sqft)",
            'unit_price' => 3.6,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Insulation'),
        ]);

        $this->setOptionCatalog(['name' => "Floor Insulation (Closed Cell, R26) ($/sqft)"], [
            'description' => "Spray foam floor insulation, closed cell 4\" at R26(1 sqft)",
            'unit_price' => 7.2,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Insulation'),
        ]);

        $this->setOptionCatalog(['name' => "Electrical - 4 Way Switch (Requires standard wiring package)"], [
            'description' => "Electrical - 4 Way Switch setup, includes 3 switches (Requires standard wiring package)",
            'unit_price' => 125,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Electrical'),
        ]);

        $this->setOptionCatalog(['name' => "14' Extra Strong Floor"], [
            'description' => "14' Extra Strong Floor",
            'unit_price' => 10,
            'force_quantity' => NULL,
            'is_active' => 'no',
            'category_id' => $this->getOptionCategoryId('Structural'),
        ]);

        $this->setOptionCatalog(['name' => "10% Home Show Discount"], [
            'description' => "10% Home Show Discount",
            'unit_price' => -1,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Discount'),
        ]);

        $this->setOptionCatalog(['name' => "4' Shed Door (w/transom)"], [
            'description' => "4' Shed door with 29\" transom window ",
            'unit_price' => 265,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Door'),
        ]);

        $this->setOptionCatalog(['name' => "3' Shed Door (w/transom)"], [
            'description' => "3' Shed door with 29\"  transom window ",
            'unit_price' => 250,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Door'),
        ]);

        $this->setOptionCatalog(['name' => "Extra Solar Blaster Vent"], [
            'description' => "Extra Solar Blaster Vent",
            'unit_price' => 190,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Exterior'),
        ]);

        $this->setOptionCatalog(['name' => "9 ft. Wall Height ($/lf)"], [
            'description' => "9 ft. wall height, 7/16 Smartside, 2x4 framing",
            'unit_price' => 38,
            'force_quantity' => "building_length",
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Structural'),
        ]);

        $this->setOptionCatalog(['name' => "Site Build"], [
            'description' => "Site build $650 or 15% whichever is greater",
            'unit_price' => 1,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Miscellaneous'),
        ]);

        $this->setOptionCatalog(['name' => "4' Dutch Shed Door"], [
            'description' => "4'x6' Dutch Shed Door (Horizontal split)",
            'unit_price' => 250,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Door'),
        ]);

        $this->setOptionCatalog(['name' => "3'x2' Opaque Skylight "], [
            'description' => "3'x2' Opaque Skylight (metal roof only)",
            'unit_price' => 250,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Exterior'),
        ]);

        $this->setOptionCatalog(['name' => "8-Wide Metal (Lean-to)"], [
            'description' => "8-Wide Metal (Lean-to)",
            'unit_price' => 11,
            'force_quantity' => "building_length",
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Roof'),
        ]);

        $this->setOptionCatalog(['name' => "Double Dutch Shed Door (Horizontal split)"], [
            'description' => "Double 4'x6' Dutch Shed Door (Horizontal split)",
            'unit_price' => 500,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Door'),
        ]);

        $this->setOptionCatalog(['name' => "Shingle Roof"], [
            'description' => "Shingle Roof",
            'unit_price' => 0,
            'force_quantity' => "building_length",
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Roof'),
        ]);

        $this->setOptionCatalog(['name' => "LP Smartside Trim"], [
            'description' => "",
            'unit_price' => 0,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Trim'),
        ]);

        $this->setOptionCatalog(['name' => "LP Smartside Siding "], [
            'description' => "",
            'unit_price' => 0,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Siding'),
        ]);

        $this->setOptionCatalog(['name' => "Aluminum corner trim"], [
            'description' => "Aluminum corner trim, Urban Econ only",
            'unit_price' => 0,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Trim'),
        ]);

        $this->setOptionCatalog(['name' => "Econ Shingle Roof"], [
            'description' => "Shingle roof, Urban Econ Black only.",
            'unit_price' => 0,
            'force_quantity' => "building_length",
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Roof'),
        ]);

        $this->setOptionCatalog(['name' => "8'x8' Roll-up Door"], [
            'description' => "8'x8' Roll-up Garage Door",
            'unit_price' => 625,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Door'),
        ]);

        $this->setOptionCatalog(['name' => "Interior finish ($/sqft)"], [
            'description' => "Painted Fiber SmartSide, corrugated galvanized metal ceiling, includes one door and beam wrap",
            'unit_price' => 12,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Interior'),
        ]);

        $this->setOptionCatalog(['name' => "Interior window finish (per window)"], [
            'description' => "Window wrap, painted SmartSide Fiber and trim",
            'unit_price' => 40,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Interior'),
        ]);

        $this->setOptionCatalog(['name' => "Interior door finish (per 3' door)"], [
            'description' => "Interior door finish (per 3' door)",
            'unit_price' => 50,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Interior'),
        ]);

        $this->setOptionCatalog(['name' => "6 Wide Metal (Lean To)"], [
            'description' => "6 Wide Metal (Lean To)",
            'unit_price' => 10,
            'force_quantity' => "building_length",
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Roof'),
        ]);

        $this->setOptionCatalog(['name' => "Engineered Drawings (for permit submittal, >40# snowloads)"], [
            'description' => "Engineered drawings for permit submittal when required snowloads exceed 40#. Includes jurisdictional building modifications (hardware, framing and nailing schedules). ",
            'unit_price' => 1150,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Miscellaneous'),
        ]);

        $this->setOptionCatalog(['name' => "2x6 w/8-ft wall height ($/lf)"], [
            'description' => "2x6 walls, 8' tall wall height. Price is per linear foot of building.",
            'unit_price' => 19,
            'force_quantity' => "building_length",
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Structural'),
        ]);

        $this->setOptionCatalog(['name' => "Engineered Drawings (for permit submittal, no snowload)"], [
            'description' => "Engineered drawings for permit submittal, no snowloads. Includes jurisdictional building modifications (hardware, framing and nailing schedules). ",
            'unit_price' => 250,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Miscellaneous'),
        ]);

        $this->setOptionCatalog(['name' => "Engineered Drawings (for permit submittal, up to 40# snowloads)"], [
            'description' => "Engineered drawings for permit submittal when required snowloads are up to 40#. Includes jurisdictional building modifications (hardware, framing and nailing schedules). ",
            'unit_price' => 750,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Miscellaneous'),
        ]);

        $this->setOptionCatalog(['name' => "Wrap Around Porch (12' x12')"], [
            'description' => "12' by 12' wrap around porch",
            'unit_price' => 875,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Deck'),
        ]);

        $this->setOptionCatalog(['name' => "Dealer Commission Discount"], [
            'description' => "Discount at the dealer's discretion, will be deducted from the dealer's commission. ",
            'unit_price' => -1,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => NULL,
        ]);

        $this->setOptionCatalog(['name' => "Used Building Discount"], [
            'description' => "Discount to apply to used buildings",
            'unit_price' => -1,
            'force_quantity' => NULL,
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Discount'),
        ]);

        $this->setOptionCatalog(['name' => "8' Wall Height (8' Wide Buildings)"], [
            'description' => "8' Wall height, 8' wide buildings",
            'unit_price' => 15,
            'force_quantity' => "building_length",
            'is_active' => 'no',
            'category_id' => $this->getOptionCategoryId('Structural'),
        ]);

        $this->setOptionCatalog(['name' => "8' Wall Height ($/lf)"], [
            'description' => "8' Wall height",
            'unit_price' => 16,
            'force_quantity' => "building_length",
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Structural'),
        ]);

        $this->setOptionCatalog(['name' => "2x6 w/9-ft wall height ($/lf)"], [
            'description' => "2x6 w/9-ft wall height, 7/16 Smartside ",
            'unit_price' => 51,
            'force_quantity' => "building_length",
            'is_active' => 'yes',
            'category_id' => $this->getOptionCategoryId('Structural'),
        ]);

        Log::info(__CLASS__ . ' Update option catalog END');
    }

    private function map3dOptions()
    {
        Log::info(__CLASS__ . ' Map 3d options START');

        $mapping = [
            //doors starts here
            [
                "name" => "4' Shed Door",
                "icon" => "/images/3d-items/4_Shed_Door.png",
                "data_id" => "4' Shed Door",
            ],
            [
                "name" => "3' Shed Door",
                "icon" => "/images/3d-items/3_Shed_Door.png",
                "data_id" => "3' Shed Door",
            ],
            [
                "name" => "Double Shed Door",
                "icon" => "/images/3d-items/Double_Shed_Door.png",
                "data_id" => "Double Shed Door",
            ],
            [
                "name" => "Steel Walk-in Door",
                "icon" => "/images/3d-items/Steel_Walk-in_Door.png",
                "data_id" => "Steel Walk-in Door",
            ],
            [
                "name" => "Steel 9-light Walk-in Door",
                "icon" => "/images/3d-items/Steel_9Light_Walk-in_Door.png",
                "data_id" => "Steel 9' Light Walk-in Door",
            ],
            [
                "name" => "6' x 7' Roll-up Door",
                "icon" => "/images/3d-items/6x7_Roll_Up_Door.png",
                "data_id" => "6'x7' Roll Up Door",
            ],
            [
                "name" => "8'x8' Roll-up Door",
                "icon" => "/images/3d-items/8x8_Roll_Up_Door.png",
                "data_id" => "8'x8' Roll Up Door",
            ],
            [
                "name" => "9' x 7' Roll-up Door",
                "icon" => "/images/3d-items/9x7_Roll_Up_Door.png",
                "data_id" => "9'x7' Roll Up Door",
            ],
            [
                "name" => "Econ Door",
                "icon" => "/images/3d-items/Econ_Door.png",
                "data_id" => "Econ Door",
            ],
            [
                "name" => "9-light French doors (6'-8\")",
                "icon" => "/images/3d-items/15Light_French_Doors.png",
                "data_id" => "15 Light French Doors",
            ],
            [
                "name" => "15-light French doors (6'-8\")",
                "icon" => "/images/3d-items/15Light_French_Doors.png",
                "data_id" => "15 Light French Doors",
            ],
            [
                "name" => "Steel French doors (6'-8\")",
                "icon" => "/images/3d-items/Steel_French_Doors.png",
                "data_id" => "Steel French Doors",
            ],
            [
                "name" => "Double Shed Doors (w/ transom)",
                "icon" => "/images/3d-items/Double_Shed_Doors_w-transom.png",
                "data_id" => "Double Shed Door (w-transom)",
            ],
            [
                "name" => "4' Shed Door (w/transom)",
                "icon" => "/images/3d-items/4_Shed_Door_w-transom.png",
                "data_id" => "4' Shed Door (w-transom)",
            ],
            [
                "name" => "3' Shed Door (w/transom)",
                "icon" => "/images/3d-items/3_Shed_Door_w-transom.png",
                "data_id" => "3' Shed Door (w-transom)",
            ],
            [
                "name" => "4' Dutch Shed Door",
                "icon" => "/images/3d-items/4_Dutch_Shed_Door.png",
                "data_id" => "4' Dutch Shed Door",
            ],
            [
                "name" => "Double Dutch Shed Door (Horizontal split)",
                "icon" => "/images/3d-items/Double_Dutch_Shed_Door.png",
                "data_id" => "Double Dutch Shed Door",
            ],
            //doors ends here
            //windows starts here
            [
                "name" => "2' x 3' Single Pane Window",
                "icon" => "/images/3d-items/2x3_Window.png",
                "data_id" => "2'x3' Single Pane Window",
            ],
            [
                "name" => "3' x'3 Single Pane Window",
                "icon" => "/images/3d-items/3x3_Window.png",
                "data_id" => "3'x3' Single Pane Window",
            ],
            [
                "name" => "2' x 3' Double Pane Window",
                "icon" => "/images/3d-items/2x3_Window.png",
                "data_id" => "2'x3' Single Pane Window",
            ],
            [
                "name" => "3' x 3' Double Pane Window",
                "icon" => "/images/3d-items/3x3_Window.png",
                "data_id" => "3'x3' Single Pane Window",
            ],
            [
                "name" => "29\" Transom Window",
                "icon" => "/images/3d-items/29_Transom_Window.png",
                "data_id" => "29 Transom Window",
            ],
            [
                "name" => "60\" Transom window",
                "icon" => "/images/3d-items/60_Transom_Window.png",
                "data_id" => "60 Transom Window",
            ],
            [
                "name" => "1'x1' Loft Window",
                "icon" => "/images/3d-items/1x1_Loft_Window.png",
                "data_id" => "1'x1' Loft Window",
            ],
            [
                "name" => "1'x1' Loft Gable Window",
                "icon" => "/images/3d-items/1x1_Loft_Window.png",
                "data_id" => "1'x1' Loft Gable Window",
            ],
            [
                "name" => "29\" Transom Gable Window",
                "icon" => "/images/3d-items/29_Transom_Window.png",
                "data_id" => "29 Transom Gable Window",
            ],
            [
                "name" => "60\" Transom Gable Window",
                "icon" => "/images/3d-items/60_Transom_Window.png",
                "data_id" => "60 Transom Gable Window",
            ],
            //windows ends here
            //decks starts here
            [
                "name" => "8' x 4' Deck",
                "icon" => "/images/3d-items/Deck8Item.png",
                "data_id" => "8' x 4' Deck",
            ],
            [
                "name" => "10' x 4' Deck",
                "icon" => "/images/3d-items/Deck10Item.png",
                "data_id" => "10' x 4' Deck",
            ],
            [
                "name" => "12' x 4' Deck",
                "icon" => "/images/3d-items/Deck12Item.png",
                "data_id" => "12' x 4' Deck",
            ],
            [
                "name" => "Wrap Around Porch (12' x12')",
                "icon" => "/images/3d-items/WrapAround.png",
                "data_id" => "wrap-around",
            ],
            [
                "name" => "10' Horse Stall",
                "icon" => "/images/3d-items/HorseStall.png",
                "data_id" => "horse-stall",
            ]
            //decks ends here
        ];

        foreach ($mapping as $key => $map) {
            $optionCatalogs = OptionCatalog::where('name', $map['name'])->get();
            $optionCatalogs->each(function ($catalog) use ($map) {
                $catalog->{'3d_model'} = [
                    'icon' => $map['icon'],
                    'data_id' => $map['data_id'],
                ];
                $catalog->save();
            });
        }

        Log::info(__CLASS__ . ' Map 3d options END');
    }

    /**
     * @param array $params
     * @param $payload
     */
    private function setOptionCatalog($params = [], $payload)
    {
        OptionCatalog::UpdateOrCreate($params, $payload);
        $optionCatalogs = OptionCatalog::where($params)->get();

        $optionCatalogs->each(function ($optionCatalog) {
            $optionCatalog->options()->update([
                'force_quantity' => $optionCatalog['force_quantity'],
                'category_id' => $optionCatalog['category_id'],
                'description' => $optionCatalog['description'],
            ]);
        });
    }
}
