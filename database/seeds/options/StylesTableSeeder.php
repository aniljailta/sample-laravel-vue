<?php

use App\Models\StyleCatalog;
use Illuminate\Database\Seeder;

class StylesTableSeeder extends Seeder
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

            $this->setStyleCatalog(['name' => 'Urban Barn'], ['data_id' => 'Urban Barn']);
            $this->setStyleCatalog(['name' => 'Urban Shack'], ['data_id' => 'Urban Shack']);
            $this->setStyleCatalog(['name' => 'Urban Lean-To'], ['data_id' => 'Urban Lean-to']); 

            DB::commit();
        } catch (Exception $e) {
            $output = new Symfony\Component\Console\Output\ConsoleOutput();
            $output->writeln("<error>{$e->getMessage()}</error>");
            $output->writeln($e->getTraceAsString());
            DB::rollback();
        }

        Log::info(__CLASS__ . ' End');
    }

    /**
     * @param array $params
     * @param $payload
     */
    private function setStyleCatalog($params = [], $payload) {
        StyleCatalog::UpdateOrCreate($params);
        $styleCatalogs = StyleCatalog::where($params)->get();

        $styleCatalogs->each(function($styleCatalog) use($payload) {
            $styleCatalog->{'3d_model'} = [
                'data_id' => $payload['data_id']
            ];
            $styleCatalog->save();
        });
    }
}
