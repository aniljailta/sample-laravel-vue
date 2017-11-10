<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;

class StyleCatalog extends Model
{
    use ModelTrait; // ability to use JOIN with relations
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'style_catalog';

    /**
     * The morphed class name
     *
     * @var string
     */
    protected $morphClass = 'style-catalog';

    /**
     * The attributes that are visible.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'description',
        'short_code',
        'sort_id',
        '3d_model',
        'is_active',
        'created_at',
        'updated_at',

        // relations
        'building_models',
        'allowable_companies',
        'image'
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'short_code',
        'sort_id',
        '3d_model',
        'is_active'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        '3d_model' => 'array',
    ];

    public static $validator = 'App\Validators\StyleValidator';
    public static $rules = [
        'id' => ['numeric'],
        'name' => ['string', 'max:255'],
        'description' => ['string', 'max:255'],
        'short_code' => ['string'],
        'sort_id' => ['numeric'],
		'3d_model' => ['json'],
        'is_active' => ['string', 'in:yes,no'],
        'updated_at' => ['date:Y-m-d'],
        'created_at' => ['date:Y-m-d'],
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
     * Scope a query to only include active style.
     *
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 'yes');
    }

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function get3dModelAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Get all of the style's image.
     * @return \App\Models\File
     */
    public function image()
    {
        return $this->morphOne('App\Models\File', 'storable');
    }

    /**
     * A style has many allowable manufacturer companies
     * @return \App\Models\ManufacturerCompany
     */
    public function allowable_companies()
    {
        return $this->belongsToMany(ManufacturerCompany::class, 'styles_allowable_companies', 'style_catalog_id', MANUFACTURER_COMPANY_ID)->withTimestamps();
    }
}
