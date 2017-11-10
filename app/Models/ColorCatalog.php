<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;

class ColorCatalog extends Model
{
    use ModelTrait; // ability to use JOIN with relations
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'color_catalog';

    /**
     * The morphed class name
     *
     * @var string
     */
    protected $morphClass = 'color-catalog';

    /**
     * The attributes that are visible.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'sort_id',
        'type',
        'name',
        'hex',
        'is_active',
        '3d_model',

        'created_at',
        'updated_at',
        'deleted_at',

        // relations
        'allowable_companies',
        'image'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sort_id',
        'type',
        'name',
        'hex',
        'is_active',
        '3d_model'
    ];

    public static $rules = [
        'id' => ['numeric'],
        'sort_id' => ['numeric'],
        'type' => ['required', 'string', 'in:standard,custom'],
        'name' => ['required', 'string', 'max:50'],
        'hex' => ['string', 'color_hex'],
        'url' => ['string'],
        'is_active' => ['in:yes,no'],
        '3d_model' => ['json']
    ];

    public static $types = [
        'standard' => 'Standard',
        'custom' => 'Custom'
    ];

    public static $isActive = [
        'yes' => [
            'id' => 'yes',
            'name' => 'Yes',
        ],
        'no' => [
            'id' => 'no',
            'name' => 'No',
        ]
    ];

    /**
     * Scope a query to only include active color.
     *
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 'yes');
    }

    /**
     * A color has many allowable manufacturer companies
     * @return \App\Models\ManufacturerCompany
     */
    public function allowable_companies()
    {
        return $this->belongsToMany(ManufacturerCompany::class, 'color_allowable_companies', 'color_catalog_id', MANUFACTURER_COMPANY_ID)->withTimestamps();
    }

    /**
     * Get all of the color's files.
     * @return \App\Models\File
     */
    public function image()
    {
        return $this->morphOne('App\Models\File', 'storable');
    }
}
