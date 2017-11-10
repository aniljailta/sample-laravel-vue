<?php

use App\Models\Color;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bodyColors = json_decode(Storage::disk('public')->get('body_colors.json'), true);

        foreach($bodyColors as $bodyColor) {
            DB::table('colors')->insert([
                'type' => $bodyColor['id'] === 'custom' ? 'custom' : 'standard',
                'name' => $bodyColor['name'],
                'hex' => $bodyColor['hex'] ?? null,
                'url' => $bodyColor['url'] ?? null,
                'option_id' => $bodyColor['id'] === 'custom' ? 23 : null,
                'use_body' => 1,
                'use_trim' => 1,
                'use_shingle' => 0
            ]);
        }

        $shingleColors = json_decode(Storage::disk('public')->get('shingle_colors.json'), true);
        foreach($shingleColors as $shingleColor) {
            DB::table('colors')->insert([
                'type' => $shingleColor['id'] === 'custom' ? 'custom' : 'standard',
                'name' => $shingleColor['name'],
                'hex' => $shingleColor['hex'] ?? null,
                'url' => $shingleColor['url'] ?? null,
                'option_id' => $shingleColor['id'] === 'custom' ? 39 : null,
                'use_body' => 0,
                'use_trim' => 0,
                'use_shingle' => 1
            ]);
        }
    }
}
