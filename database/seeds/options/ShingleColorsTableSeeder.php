<?php

use App\Models\Color;
use Illuminate\Database\Seeder;

class ShingleColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Log::info(__CLASS__ . ' Start');

        $this->setColor(['name' => "Black Walnut"], [
		    'data_type' => "Heritage Black Walnut"
		]);

		$this->setColor(['name' => "Rustic Black"], [
		    'data_type' => "Heritage Rustic Black"
		]);

		$this->setColor(['name' => "Rustic Cedar"], [
		    'data_type' => "Heritage Rustic Cedar"
		]);

		$this->setColor(['name' => "Mountain Slate"], [
		    'data_type' => "Heritage Mountain Slate"
		]);

		$this->setColor(['name' => "Galvanized"], [
		    'data_type' => "Galvalume"
		]);

		$this->setColor(['name' => "Vintage White"], [
		    'data_type' => "Vintage White"
		]);

		$this->setColor(['name' => "Rustic Red"], [
		    'data_type' => "Rustic Red"
		]);

		$this->setColor(['name' => "Koko Brown"], [
		    'data_type' => NULL
		]);

		$this->setColor(['name' => "Gray"], [
		    'data_type' => "Gray"
		]);

		$this->setColor(['name' => "Evergreen"], [
		    'data_type' => NULL
		]);

		$this->setColor(['name' => "Desert Sand"], [
		    'data_type' => "Desert Sand"
		]);

		$this->setColor(['name' => "Coal Black"], [
		    'data_type' => "Coal Black"
		]);

		$this->setColor(['name' => "Galvalume"], [
		    'data_type' => "Galvalume"
		]);

		$this->setColor(['name' => "Vintage White"], [
		    'data_type' => "Vintage White"
		]);

		$this->setColor(['name' => "Rustic Red"], [
		    'data_type' => "Rustic Red"
		]);

		$this->setColor(['name' => "Koko Brown"], [
		    'data_type' => NULL
		]);

		$this->setColor(['name' => "Gray"], [
		    'data_type' => "Gray"
		]);

		$this->setColor(['name' => "Evergreen"], [
		    'data_type' => NULL
		]);

		$this->setColor(['name' => "Desert Sand"], [
		    'data_type' => "Desert Sand"
		]);

		$this->setColor(['name' => "Coal Black"], [
		    'data_type' => "Coal Black"
		]);

		Log::info(__CLASS__ . ' End');
    }

    /**
     * @param array $params
     * @param $model3d
     */
    private function setColor(array $params = [], array $model3d)
    {
        $manufacturers = \App\Models\ManufacturerCompany::all();
        $manufacturers->each(function($manufacturer) use ($params, $model3d) {
            Color::UpdateOrCreate($params, [
                '3d_model' => $model3d,
                MANUFACTURER_COMPANY_ID => $manufacturer->company_id
            ]);
        });
    }
}
