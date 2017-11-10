<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;

class OptionCatalog extends Model
{
    use ModelTrait; // ability to use JOIN with relations
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'option_catalog';

    /**
     * The attributes that are visible.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'category_id',
        'force_quantity',
        'name',
        'description',
        '3d_model',
        'sort_id',
        'unit_price',
        'is_active',
        'is_required',
        'created_at',
        'updated_at',
        'deleted_at',

        // appends
        'unit_price',
        'quantity',
        'total',
        'active',
        'force_quantity_flag',

        // relations
        'allowable_companies',
        'category',
        'files',
        'options',
        // building related
        'taxable',
        'rto',
        'rto_deposit',
        'delivery_charge',
        'constraint_type',
        'constraint_type_flag'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'force_quantity',
        'name',
        'description',
        '3d_model',
        'sort_id',
        'unit_price',
        'is_active',
        'taxable',
        'rto',
        'rto_deposit',
        'delivery_charge',
        'constraint_type',
        'is_required'
    ];
    protected $appends = [
        'unit_price',
        'quantity',
        'total',
        'active',
        'force_quantity_flag',
        'constraint_type_flag'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        '3d_model' => 'array',
        'delivery_charge' => 'boolean',
        'rto_deposit' => 'boolean',
    ];

    public static $rules = [
        'id' => ['numeric'],
        'category_id' => ['numeric', 'nullable'],
        'sort_id' => ['numeric'],
        '3d_model' => ['json'],
        'force_quantity' => ['string', 'in:building_length,wall_area,floor_area', 'nullable'],
        'name' => ['max:255'],
        'description' => ['string', 'max:255', 'nullable'],
        'unit_price' => ['numeric'],
        'is_active' => ['string', 'in:yes,no'],
        'rto_deposit' => ['boolean'],
        'delivery_charge' => ['boolean'],
        'is_required' => ['boolean']
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

    public static $forceQuantity = [
        'building_length' => [
            'id' => 'building_length',
            'name' => 'Building length',
        ],
        'wall_area' => [
            'id' => 'wall_area',
            'name' => 'Wall area'
        ],
        'floor_area' => [
            'id' => 'floor_area',
            'name' => 'Floor area'
        ]
    ];

    public static $constraintType = [
        'less_than' => [
            'id' => 'less_than',
            'name' => 'Less than'
        ],
        'equal_to' => [
            'id' => 'equal_to',
            'name' => 'Equal to'
        ]
    ];

    public function getActiveAttribute() {
        if (!isset($this->is_active)) return null;
        return collect(self::$isActive[$this->is_active]);
    }

    public function getForceQuantityFlagAttribute() {
        if (!isset($this->force_quantity)) return null;
        return collect(self::$forceQuantity[$this->force_quantity]);
    }

    public function getUnitPriceAttribute()
    {
        if (isset($this->pivot) && isset($this->pivot->unit_price)) 
            return $this->pivot->unit_price;
        
        return $this->getOriginal('unit_price');
    }

    public function getQuantityAttribute()
    {
        if (isset($this->pivot) && isset($this->pivot->quantity)) 
            return $this->pivot->quantity;
        
        return 1;
    }

    public function getTotalAttribute()
    {
        if (isset($this->pivot) && isset($this->pivot->quantity))
            return $this->pivot->quantity * $this->unit_price;

        return $this->unit_price;
    }

    public function getConstraintTypeFlagAttribute() {
        if (!isset($this->constraint_type)) return null;
        return collect(self::$constraintType[$this->constraint_type]);
    }

    /**
     * Scope a query to only include active option.
     *
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 'yes');
    }

    /**
     * An option has one category
     * @return \App\Models\OptionCategory
     */
    public function category()
    {
        return $this->hasOne('App\Models\OptionCategory', 'id', 'category_id');
    }

    /**
     * Get all of the option's files.
     * @return \App\Models\File
     */
    public function files()
    {
        return $this->morphMany('App\Models\File', 'storable');
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
     * A style has many allowable manufacturer companies
     * @return \App\Models\ManufacturerCompany
     */
    public function allowable_companies()
    {
        return $this->belongsToMany(ManufacturerCompany::class, 'option_allowable_companies', 'option_catalog_id', MANUFACTURER_COMPANY_ID)->withTimestamps();
    }

    /**
     * A option catalog has many options
     * @return Relation
     */
    public function options()
    {
        return $this->hasMany(Option::class, 'option_catalog_id', 'id');
    }
}
