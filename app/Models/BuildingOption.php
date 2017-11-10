<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Llama\Database\Eloquent\ModelTrait;

class BuildingOption extends Model
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
        'building_id',
        'option_id',
        'quantity',
        'unit_price',
        'total_price',
        ];

    /**
     * The attributes that are visible.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'building_id',
        'option_id',
        'quantity',
        'unit_price',
        'total_price',
        // relations
        'building',
        'option',
        'color',
        'category',
    ];

    public static $rules = [
        'id' => ['numeric'],
        'building_id' => ['numeric'],
        'option_id' => ['numeric'],
        'quantity' => ['numeric'],
        'unit_price' => ['numeric'],
        'total_price' => ['numeric'],
    ];

    protected $appends = array('color', 'category');

    /**
     * A building option belongs to a building
     * @return \App\Models\Building
     */
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * A building option belongs to an option
     * @return \App\Models\Option
     */
    public function option()
    {
        return $this->belongsTo(Option::class)->withTrashed();
    }

    /**
     * A building option has one an option
     * @return \App\Models\BuildingOptionColor
     */
    public function colors()
    {
        return $this->belongsToMany(Color::class, 'building_option_colors', 'building_option_id')->withTimestamps()->withPivot('custom');
    }

    /**
     * A building option has one an option color
     * @return \App\Models\BuildingOptionColor
     */
    public function option_color()
    {
        return $this->hasOne(BuildingOptionColor::class, 'building_option_id', 'id');
    }

    public function getColorAttribute()
    {
        if (isset($this->attributes['color'])) {
            return $this->attributes['color'];
        }

        if ($this->exists && $this->option_color) {
            if ($this->option_color->custom) {
                $this->option_color->color->name = $this->option_color->custom;
            }
            return $this->option_color->color;
        }

        if (!$this->exists && $this->relationLoaded('option_color') && $this->option_color) {
            if ($this->option_color->custom) {
                $this->option_color->color->name = $this->option_color->custom;
            }
            return $this->option_color->color;
        }

        return null;
    }

    public function getCategoryAttribute()
    {
        if (isset($this->attributes['category'])) {
            return $this->attributes['category'];
        }
        if ($this->option && $this->option->category) {
            return $this->option->category;
        }
        return null;
    }
}
