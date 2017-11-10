<?php

use App\Models\Building;
use App\Models\Color;
use App\Models\Coloring;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ColoringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $buildings = Building::all();
        $orders = Order::all();
        $this->colorableItem($buildings);
        $this->colorableItem($orders);


    }

    private function colorableItem($items) {
        $bodyColors = Color::body()->get()->keyBy('id');
        $trimColors = Color::trim()->get()->keyBy('id');
        $shingleColors = Color::shingle()->get()->keyBy('id');

        $oldBodyColors = collect(json_decode(Storage::disk('public')->get('body_colors.json'), true));
        $oldTrimColors = collect(json_decode(Storage::disk('public')->get('trim_colors.json'), true));
        $oldShingleColors = collect(json_decode(Storage::disk('public')->get('shingle_colors.json'), true));

        foreach ($items as $item) {
            $bodyColor = $this->searchColor($bodyColors, $item->body_color);
            $trimColor = $this->searchColor($trimColors, $item->trim_color);
            $shingleColor = $this->searchColor($shingleColors, $item->roof_color);

            $oldBodyColor = $this->searchColor($oldBodyColors, $item->body_color);
            $oldTrimColor = $this->searchColor($oldTrimColors, $item->trim_color);
            $oldShingleColor = $this->searchColor($oldShingleColors, $item->roof_color);

            if ($bodyColor) {
                $this->colorable($item, 'body', $bodyColor);
            } elseif($oldBodyColor) {
                $bodyColor = $this->searchColor($bodyColors, $oldBodyColor['name']);
                $this->colorable($item, 'body', $bodyColor);
            } else {
                $custom = $bodyColors[2];
                $custom['custom'] = $item->body_color;
                $this->colorable($item, 'body', $custom);
            }

            if ($trimColor) {
                $this->colorable($item, 'trim', $trimColor);
            } elseif($oldTrimColor) {
                $trimColor = $this->searchColor($trimColors, $oldTrimColor['name']);
                $this->colorable($item, 'trim', $trimColor);
            } else {
                $custom = $trimColors[2];
                $custom['custom'] = $item->trim_color;
                $this->colorable($item, 'trim', $custom);
            }

            if ($shingleColor) {
                $this->colorable($item, 'shingle', $shingleColor);
            } elseif($oldShingleColor) {
                $shingleColor = $this->searchColor($shingleColors, $oldShingleColor['name']);
                $this->colorable($item, 'shingle', $shingleColor);
            } else {
                $custom = $shingleColors[19];
                $custom['custom'] = $item->roof_color;
                $this->colorable($item, 'shingle', $custom);
            }
        }
    }
    
    private function colorable(&$item, $type, $color) {
        if (!$color) return false;
        //\DB::enableQueryLog();
        /*Coloring::where('colorable_id', $building->id)
            ->where('colorable_type', '=',$building->getMorphClass())
            ->where('type', '=', $type)
            ->delete();*/
        
        Coloring::updateOrCreate(
            [
                'colorable_id' => $item->id,
                'colorable_type' => $item->getMorphClass(),
                'type' => $type
            ],
            [
                'color_id' => (is_array($color)) ? $color['id'] : $color->id,
                'custom' => $color['custom'] ?? null,
            ]);
        
        /*dd(\DB::getQueryLog());
        $building->coloring()->save(new Coloring([
                    'colorable_id' => $building->id,
                    'colorable_type' => $building->getMorphClass(),
                    'color_id' => (is_array($color)) ? $color['id'] : $color->id,
                    'type' => $type
                ]));*/
    }

    private function searchColor($colors, $colorName) {
        $foundID = $colors->search(function ($value, $key) use($colorName) {
            return $value['name'] === $colorName || $value['id'] === $colorName;
        });
        
        if ($foundID !== false) {
            return $colors[$foundID];
        }
        
        return false;
    }
}
