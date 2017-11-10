<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Llama\Database\Eloquent\ModelTrait;

class BuildingPackageOption extends Model
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
        'building_package_id',
        'option_id',
        'quantity',
        //'unit_price',
        //'total_price',
        ];

    /**
     * The attributes that are visible.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'building_package_id',
        'option_id',
        'quantity',
        'unit_price',
        //'total_price',
        // relations
        'building_package',
        'option'
    ];

    public static $rules = [
        'id' => ['numeric'],
        'building_package_id' => ['numeric'],
        'option_id' => ['numeric'],
        'quantity' => ['numeric'],
        //'unit_price' => ['numeric'],
        //'total_price' => ['numeric'],
    ];

    protected $appends = array('unit_price');

    public function getUnitPriceAttribute()
    {
        if (isset($this->option) && isset($this->option->unit_price))
            return $this->option->unit_price;

        return null;
    }

    /**
     * A building package option belongs to a building package
     * @return \App\Models\BuildingPackage
     */
    public function building_package()
    {
        return $this->belongsTo(BuildingPackage::class);
    }

    /**
     * A building option option belongs to an option
     * @return \App\Models\Option
     */
    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
