<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

// TODO: deprecate (ready to be deleted)
class Coloring extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coloring';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'deleted_at' ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'colorable_id', // id = 1
        'colorable_type', // building/order
        'color_id', // 3
        'type', // body / trim / roof
        'custom' // if it is custom
    ];

    public function colorable()
    {
        return $this->morphTo();
    }

    /**
     * A color belongs to a building
     * @return \App\Models\Building
     */
    public function building()
    {
        return $this->belongsTo('App\Models\Building');
    }

    /**
     * A color belongs to a order
     * @return \App\Models\Order
     */
    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    /**
     * @param $item
     * @param $color
     * @param $type
     */
    public static function colorify($item, $color, $type) {
        if ($color instanceof Collection) {
            $color = $color->toArray();
        }
        
        if (!isset($color['id'])) return;

        if ($color['type'] === 'custom') {
            $color['custom'] = $color['name'] !== '' ? $color['name'] : 'custom';
        } else {
            $color['custom'] = null;
        }
        
        Coloring::updateOrCreate(
            [
                'colorable_id' => $item->id,
                'colorable_type' => $item->getMorphClass(),
                'type' => $type
            ],
            [
                'color_id' => $color['id'],
                'custom' => $color['custom'],
            ]);
    }
}
