<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Llama\Database\Eloquent\ModelTrait;

class BuildingOptionColor extends Model
{
    use ModelTrait;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'building_option_id',
        'color_id',
        'custom',
    ];

    /**
     * The attributes that are visible.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'building_option_id',
        'color_id',
        'custom',
        // relations
        'building_option',
        'color',
    ];

    public static $rules = [
        'id' => ['numeric'],
        'building_option_id' => ['numeric'],
        'custom' => ['string', 'max:255', 'nullable'],
    ];
    
    /**
     * A building option color belongs to a building option
     * @return \App\Models\BuildingOption
     */
    public function building_option()
    {
        return $this->belongsTo(BuildingOption::class);
    }
    
    /**
     * A building option color belongs to color
     * @return \App\Models\color
     */
    public function color()
    {
        return $this->belongsTo(Color::class)->withTrashed();
    }
}
