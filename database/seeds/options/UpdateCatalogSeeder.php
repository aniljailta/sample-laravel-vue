<?php

use App\Models\Option;
use App\Models\OptionCategory;
use App\Models\OptionCatalog;

use Illuminate\Database\Seeder;

class UpdateCatalogSeeder extends Seeder
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
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            OptionCatalog::truncate();

            $this->fetchOptionCategories();
            $this->updateOptions();
            $this->map3dOptions();
            $this->fetchOptionCatalogs();
            $this->updateCatalogId();

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

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

        $this->setOptionCatalog(['name' => "8' Wide Loft ($/lf)",
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "8' wide loft (1 lf)",
            'unit_price' => 20,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 1,
            ]);

        $this->setOptionCatalog(['name' => "10' Wide Loft ($/lf)",
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "10' wide loft (1 lf)",
            'unit_price' => 29,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 2,
            ]);

        $this->setOptionCatalog(['name' => "12' Wide Loft (($/lf))",
            'category_id' => $this->getOptionCategoryId("Interior"),
            'force_quantity' => NULL,
            'description' => "12' wide loft (1 lf)",
            'unit_price' => 29,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 3,
            ]);
        
        $this->setOptionCatalog(['name' => "1'x1' Gable Window",
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "1'x1' Window, placed in gable",
            'unit_price' => 75,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 1,
            ]);

        $this->setOptionCatalog(['name' => "1'x1' Loft Window",
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 75,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 11,
            ]);

        $this->setOptionCatalog(['name' => "10' x 4' Deck",
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "10'x4' deck and posts",
            'unit_price' => 380,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 2,
            ]);

        $this->setOptionCatalog(['name' => "12' x 4' Deck",
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "12'x4' deck and posts",
            'unit_price' => 440,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 3,
            ]);

        $this->setOptionCatalog(['name' => "14' Wide Deck",
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "",
            'unit_price' => 500,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 4,
            ]);

        $this->setOptionCatalog(['name' => "14'x4' Deck",
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "14'x4' deck and posts",
            'unit_price' => 885,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 6,
            ]);

        $this->setOptionCatalog(['name' => "15-light French doors (6'-8\") ",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "15-light steel French Walk-in doors, 6'-8\" high",
            'unit_price' => 700,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 20,
            ]);

        $this->setOptionCatalog(['name' => "2' x 3' Double Pane Window",
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "2'x3' Double pane gridded aluminum window",
            'unit_price' => 225,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 4,
            ]);

        $this->setOptionCatalog(['name' => "2' x 3' Single Pane Window",
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "2'x3' Single pane gridded aluminum window",
            'unit_price' => 110,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 2,
            ]);

        $this->setOptionCatalog(['name' => "2'x2' Tinted Skylight (Shingle only)",
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "2'x2' Tinted Skylight (shingle roof only)",
            'unit_price' => 250,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 10,
            ]);

        $this->setOptionCatalog(['name' => "29\" Transom Gable Window",
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "29\" transom window, installed in gable.",
            'unit_price' => 85,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 7,
            ]);

        $this->setOptionCatalog(['name' => "29\" Transom Window",
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "29\" transom window, installed in wall.",
            'unit_price' => 75,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 6,
            ]);

        $this->setOptionCatalog(['name' => "2x3 Double Pane Window",
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "Aluminum",
            'unit_price' => 160,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 2,
            ]);

        $this->setOptionCatalog(['name' => "2x3 Single Pane Window",
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "Aluminum",
            'unit_price' => 100,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 1,
            ]);

        $this->setOptionCatalog(['name' => "3-0 6-0 LH Steel Paneled 9-light Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3-0 6-0 door. Hinges on left, facing door from the outside of building. 9 light.",
            'unit_price' => 350,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 13,
            ]);

        $this->setOptionCatalog(['name' => "3-0 6-0 LH Steel Paneled Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3-0 6-0 door. Hinges on left, facing door from the outside of building",
            'unit_price' => 300,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 9,
            ]);

        $this->setOptionCatalog(['name' => "3-0 6-0 RH Steel Paneled 9-light Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3-0 6-0 door. Hinges on right, facing door from the outside of building. 9 light.",
            'unit_price' => 350,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 12,
            ]);

        $this->setOptionCatalog(['name' => "3-0 6-0 RH Steel Paneled Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3-0 6-0 door. Hinges on right, facing door from the outside of building",
            'unit_price' => 300,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 8,
            ]);

        $this->setOptionCatalog(['name' => "3-0 6-8 LH Steel Paneled Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3-0 6-8 door. Hinges on left, facing door from the outside of building",
            'unit_price' => 300,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 11,
            ]);

        $this->setOptionCatalog(['name' => "3-0 6-8 LH Steel Paneled Door 9-light",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3-0 6-8 door. Hinges on left, facing door from the outside of building",
            'unit_price' => 350,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 15,
            ]);

        $this->setOptionCatalog(['name' => "3-0 6-8 RH Steel Paneled Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3-0 6-8 door. Hinges on right, facing door from the outside of building",
            'unit_price' => 300,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 10,
            ]);

        $this->setOptionCatalog(['name' => "3-0 6-8 RH Steel Paneled Door 9-light",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3-0 6-8 door. Hinges on right, facing door from the outside of building",
            'unit_price' => 350,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 14,
            ]);

        $this->setOptionCatalog(['name' => "3' Shed Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3'x6' Heavy-duty shed door",
            'unit_price' => 175,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 13,
            ]);

        $this->setOptionCatalog(['name' => "3' Shed Door (w/transom)",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3' Shed door with 29\" transom window",
            'unit_price' => 250,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 24,
            ]);

        $this->setOptionCatalog(['name' => "3' x 3' Double Pane Window",
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "3'x3' Double pane gridded aluminum window",
            'unit_price' => 260,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 5,
            ]);

        $this->setOptionCatalog(['name' => "3' x'3 Single Pane Window",
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "3'x3' Single pane gridded aluminum window",
            'unit_price' => 125,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 3,
            ]);

        $this->setOptionCatalog(['name' => "3'x2' Opaque Skylight (metal roof only)",
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "3'x2' Opaque Skylight (metal roof only)",
            'unit_price' => 250,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 11,
            ]);

        $this->setOptionCatalog(['name' => "3'x2' Opaque Skylight (metal roof only)",
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "3'x2' Opaque Skylight (metal roof only)",
            'unit_price' => 250,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 11,
            ]);

        $this->setOptionCatalog(['name' => "3060 House Door 9-lite",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Inswing",
            'unit_price' => 375,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 2,
            ]);

        $this->setOptionCatalog(['name' => "3060 House Door No Window",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Inswing",
            'unit_price' => 295,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 1,
            ]);

        $this->setOptionCatalog(['name' => "3068 House Door 9-lite",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Inswing",
            'unit_price' => 375,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 4,
            ]);

        $this->setOptionCatalog(['name' => "3068 House Door No Window",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Inswing",
            'unit_price' => 295,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 3,
            ]);

        $this->setOptionCatalog(['name' => "3x3 Double Pane Window",
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "Aluminum",
            'unit_price' => 175,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 4,
            ]);

        $this->setOptionCatalog(['name' => "3x3 Single Pane Window",
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "Aluminum",
            'unit_price' => 115,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 3,
            ]);

        $this->setOptionCatalog(['name' => "4' Dutch Shed Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "4'x6' Dutch Shed Door (Horizontal split)",
            'unit_price' => 375,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 4,
            ]);

        $this->setOptionCatalog(['name' => "4' Shed Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "4'x6' Heavy-duty shed door",
            'unit_price' => 270,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 2,
            ]);

        $this->setOptionCatalog(['name' => "4' Shed Door (w/transom)",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "4' Shed door with 29\" transom window",
            'unit_price' => 340,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 3,
            ]);

        $this->setOptionCatalog(['name' => "6-0 6-8 Steel Paneled 15-light French Doors ",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "15-light steel French Walk-in doors, 6'-8\" high",
            'unit_price' => 700,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 17,
            ]);

        $this->setOptionCatalog(['name' => "6-0 6-8 Steel Paneled French Doors ",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "6-0 6-8 Steel Paneled French doors",
            'unit_price' => 600,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 16,
            ]);

        $this->setOptionCatalog(['name' => "6' x 7' Roll-up Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "6'x7' Roll-up door",
            'unit_price' => 690,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 18,
            ]);

        $this->setOptionCatalog(['name' => "60\" Transom Gable Window",
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "60\" transom window, installed in gable.",
            'unit_price' => 115,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 9,
            ]);

        $this->setOptionCatalog(['name' => "60\" Transom window",
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "60\" transom window, installed in wall.",
            'unit_price' => 105,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 8,
            ]);

        $this->setOptionCatalog(['name' => "8' x 4' Deck",
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "8' x 4' deck and posts",
            'unit_price' => 340,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 1,
            ]);

        $this->setOptionCatalog(['name' => "8'x8' Roll-up Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "8'x8' Roll-up Garage Door",
            'unit_price' => 675,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 20,
            ]);

        $this->setOptionCatalog(['name' => "9' x 7' Roll-up Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "9'x7' Roll-up door",
            'unit_price' => 780,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 19,
            ]);

        $this->setOptionCatalog(['name' => "Decorative Hinges 17\" ",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Price per door panel",
            'unit_price' => 60,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 11,
            ]);

        $this->setOptionCatalog(['name' => "Double Dutch Shed Door (Horizontal split)",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Double 4'x6' Dutch Shed Door (Horizontal split)",
            'unit_price' => 750,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 7,
            ]);

        $this->setOptionCatalog(['name' => "Double Shed Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Double 3'x6' Heavy-duty shed doors",
            'unit_price' => 500,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 5,
            ]);

        $this->setOptionCatalog(['name' => "Double Shed Doors (w/ transom)",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Double 3'x6' Heavy-duty shed doors with 29\" transoms",
            'unit_price' => 575,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 6,
            ]);

        $this->setOptionCatalog(['name' => "Double Wood Barn Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "6' Barn Door",
            'unit_price' => 160,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 8,
            ]);

        $this->setOptionCatalog(['name' => "Double Wood Basic Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "6' Basic Door",
            'unit_price' => 160,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 9,
            ]);

        $this->setOptionCatalog(['name' => "Double Wood Heritage Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "6' Heritage Door",
            'unit_price' => 160,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 10,
            ]);

        $this->setOptionCatalog(['name' => "Econ Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Economy door",
            'unit_price' => 90,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 1,
            ]);

        $this->setOptionCatalog(['name' => "Flower Box",
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "Wooden flower box with insert",
            'unit_price' => 75,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 13,
            ]);

        $this->setOptionCatalog(['name' => "No Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "No Door",
            'unit_price' => 0,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 28,
            ]);

        $this->setOptionCatalog(['name' => "Shutters",
            'category_id' => $this->getOptionCategoryId("Window"),
            'force_quantity' => NULL,
            'description' => "3' Shutters",
            'unit_price' => 55,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 12,
            ]);

        $this->setOptionCatalog(['name' => "Single Wood Barn Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Barn Style",
            'unit_price' => 80,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 5,
            ]);

        $this->setOptionCatalog(['name' => "Single Wood Basic Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Basic Door",
            'unit_price' => 80,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 6,
            ]);

        $this->setOptionCatalog(['name' => "Single Wood Heritage Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Heritage Door",
            'unit_price' => 80,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 7,
            ]);

        $this->setOptionCatalog(['name' => "Steel 9-light Walk-in Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3' 9-light steel walk-in door",
            'unit_price' => 350,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 16,
            ]);

        $this->setOptionCatalog(['name' => "Steel French doors (6'-8\")",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "Steel French doors, 6'-8\" high",
            'unit_price' => 600,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 21,
            ]);

        $this->setOptionCatalog(['name' => "Steel handrail",
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "Steel handrail panel (up to 5')",
            'unit_price' => 92,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 5,
            ]);

        $this->setOptionCatalog(['name' => "Steel Walk-in Door",
            'category_id' => $this->getOptionCategoryId("Door"),
            'force_quantity' => NULL,
            'description' => "3-0 Steel Door",
            'unit_price' => 300,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 15,
            ]);

        $this->setOptionCatalog(['name' => "Wrap Around Porch (12' x12')",
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "12' by 12' wrap around porch",
            'unit_price' => 875,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 4,
            ]);

        $this->setOptionCatalog(['name' => "Wrap Around Porch (14' x14')",
            'category_id' => $this->getOptionCategoryId("Deck"),
            'force_quantity' => NULL,
            'description' => "14' x 14' Wrap Around Porch ",
            'unit_price' => 1020,
            'is_active' => "yes",
            'rto' => NULL,
            'taxable' => NULL,
            'constraint_type' => NULL,
            'default_color_id' => NULL,
            'sort_id' => 7,
            ]);


        Log::info(__CLASS__ . ' Update option END');
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
        ],
        //decks ends here
        //loft starts here
        [
            "name" => "8' Wide Loft ($/lf)",
            "icon" => "/images/3d-items/Loft.jpg",
            "data_id" => "loft",
        ],
        [
            "name" => "10' Wide Loft ($/lf)",
            "icon" => "/images/3d-items/Loft.jpg",
            "data_id" => "loft",
        ],
        [
            "name" => "12' Wide Loft (($/lf))",
            "icon" => "/images/3d-items/Loft.jpg",
            "data_id" => "loft",
        ]
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
private function setOptionCatalog($payload)
{
    OptionCatalog::create($payload);
}

private function updateCatalogId(){
    $options = Option::all();
    $options->each(function ($option) {
        $option->option_catalog_id = $this->getOptionCatalogId($option->name);
        $option->save();
    });
}
}
