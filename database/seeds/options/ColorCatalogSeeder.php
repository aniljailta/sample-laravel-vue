<?php

use App\Models\Color;
use App\Models\ColorCatalog;

use Illuminate\Database\Seeder;

class ColorCatalogSeeder extends Seeder
{
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

            ColorCatalog::truncate();

            $this->updateColorCatalog();
            $this->fetchColorCatalogs();
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

    private function fetchColorCatalogs()
    {
        $this->colorCatalogs = ColorCatalog::all();
    }

    /**
     * @param string $name
     * @return int|null
     */
    private function getColorCatalogId(string $name): ?int
    {
        $catalog = $this->colorCatalogs->first(function ($catalog) use ($name) {
            return $catalog->name === $name;
        });

        if ($catalog) return $catalog->id;
        return null;
    }

    /**
     * @param string $name
     * @return int|null
     */
    private function getColorDataId(string $name)
    {
        $catalog = $this->colorCatalogs->first(function ($catalog) use ($name) {
            return $catalog->name === $name;
        });

        if ($catalog) return $catalog->{'3d_model'};
        return null;
    }
    

    private function updateColorCatalog()
    {
        Log::info(__CLASS__ . ' Update color catalog START');

        $this->setColorCatalog(['name' => "Black Walnut",
            'sort_id' => 20,
            'type' => "standard",
            'hex' => null,
            //'url' => "/dealer-form/img/shingle-colors/Black-Walnut.png",
            '3d_model' => json_encode(["data_type" => "Heritage Black Walnut"]),
            'is_active' => "yes",
            ]);

        $this->setColorCatalog(['name' => "Coal Black",
            'sort_id' => 35,
            'type' => "standard",
            'hex' => "#121111",
            //'url' => null,
            '3d_model' => json_encode(["data_type" => "Coal Black"]),
            'is_active' => "yes",
            ]);

        $this->setColorCatalog(['name' => "Desert Sand",
            'sort_id' => 34,
            'type' => "standard",
            'hex' => "#afa48c",
            //'url' => null,
            '3d_model' => json_encode(["data_type" => "Desert Sand"]),
            'is_active' => "yes",
            ]);

        $this->setColorCatalog(['name' => "Evergreen",
            'sort_id' => 33,
            'type' => "standard",
            'hex' => "#1f594a",
            //'url' => null,
            '3d_model' => json_encode(["data_type" => null]),
            'is_active' => "yes",
            ]);

        $this->setColorCatalog(['name' => "Galvalume",
            'sort_id' => 28,
            'type' => "standard",
            'hex' => null,
            //'url' => null,
            '3d_model' => json_encode(["data_type" => "Galvalume"]),
            'is_active' => "yes",
            ]);

        $this->setColorCatalog(['name' => "Galvanized",
            'sort_id' => 33,
            'type' => "standard",
            'hex' => null,
            //'url' => null,
            '3d_model' => json_encode(["data_type" => "Galvalume"]),
            'is_active' => "yes",
            ]);

        $this->setColorCatalog(['name' => "Gray",
            'sort_id' => 32,
            'type' => "standard",
            'hex' => "#afaeae",
            //'url' => null,
            '3d_model' => json_encode(["data_type" => "Gray"]),
            'is_active' => "yes",
            ]);

        $this->setColorCatalog(['name' => "Koko Brown",
            'sort_id' => 31,
            'type' => "standard",
            'hex' => "#4d3530",
            //'url' => null,
            '3d_model' => json_encode(["data_type" => null]),
            'is_active' => "yes",
            ]);

        $this->setColorCatalog(['name' => "Mountain Slate",
            'sort_id' => 23,
            'type' => "standard",
            'hex' => null,
            //'url' => "/dealer-form/img/shingle-colors/Mountain-Slate.png",
            '3d_model' => json_encode(["data_type" => "Heritage Mountain Slate"]),
            'is_active' => "yes",
            ]);

        $this->setColorCatalog(['name' => "Rustic Black",
            'sort_id' => 21,
            'type' => "standard",
            'hex' => null,
            //'url' => "/dealer-form/img/shingle-colors/Rustic-Black.png",
            '3d_model' => json_encode(["data_type" => "Heritage Rustic Black"]),
            'is_active' => "yes",
            ]);

        $this->setColorCatalog(['name' => "Rustic Cedar",
            'sort_id' => 22,
            'type' => "standard",
            'hex' => null,
            //'url' => "/dealer-form/img/shingle-colors/Rustic-Cedar.png",
            '3d_model' => json_encode(["data_type" => "Heritage Rustic Cedar"]),
            'is_active' => "yes",
            ]);

        $this->setColorCatalog(['name' => "Rustic Red",
            'sort_id' => 30,
            'type' => "standard",
            'hex' => "#92322b",
            //'url' => null,
            '3d_model' => json_encode(["data_type" => "Rustic Red"]),
            'is_active' => "yes",
            ]);

        $this->setColorCatalog(['name' => "Vintage White",
            'sort_id' => 29,
            'type' => "standard",
            'hex' => "#f9f8f7",
            //'url' => null,
            '3d_model' => json_encode(["data_type" => "Vintage White"]),
            'is_active' => "yes",
            ]);

        

        Log::info(__CLASS__ . 'color catalog END');
    }
    
/**
 * @param array $params
 * @param $payload
 */
private function setColorCatalog($payload)
{
    ColorCatalog::create($payload);
}

private function updateCatalogId(){
    $colors = Color::all();
    $colors->each(function ($color) {
        $color->color_catalog_id = $this->getColorCatalogId($color->name);
        $color->save();
    });
}
}
