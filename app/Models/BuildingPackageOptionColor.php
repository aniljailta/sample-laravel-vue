<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Llama\Database\Eloquent\ModelTrait;

class BuildingPackageOptionColor extends Model
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
        'bp_option_id',
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
        'bp_option_id',
        'color_id',
        'custom',
        // relations
        'building_package_option',
        'color',
    ];

    public static $rules = [
        'id' => ['numeric'],
        'bp_option_id' => ['numeric'],
        'custom' => ['string', 'max:255', 'nullable'],
    ];

    /**
     * A building option color belongs to a building option
     * @return \App\Models\BuildingOption
     */
    public function building_package_option()
    {
        return $this->belongsTo(BuildingPackageOption::class);
    }

    /**
     * A building package option color belongs to color
     * @return \App\Models\color
     */
    public function color()
    {
        return $this->belongsTo(Color::class)->withTrashed();
    }
}
