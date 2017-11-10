<?php

namespace App\Models;

use App\Validators\ColorValidator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;
use HipsterJazzbo\Landlord\BelongsToTenants;

class Color extends Model
{
    use SoftDeletes;
    use ModelTrait;
    use BelongsToTenants;

    public $tenantColumns = [MANUFACTURER_COMPANY_ID];

    /**
     * The morphed class name
     *
     * @var string
     */
    protected $morphClass = 'color';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'sort_id',
        'type',
        'name',
        'hex',
        'option_id',
        'is_active',
        'color_catalog_id',
        MANUFACTURER_COMPANY_ID,

        'label',

        'created_at',
        'updated_at',
        'deleted_at',
        // appends
        '3d_model',

        // relations (jsonable)
        'catalog',
        'files',
        'image',
        'company',
        'option',
        'allowable_models',
        'allowable_options',
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
        'custom',
        'hex',
        'option_id',
        'is_active',
        'color_catalog_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    protected $appends = array('label', '3d_model', 'usage', 'name as colorsName');

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        '3d_model' => 'array',
    ];

    public static $validator = ColorValidator::class;
    public static $rules = [
        'id' => ['numeric'],
        'sort_id' => ['numeric'],
        'type' => ['required', 'string', 'in:standard,custom'],
        'name' => ['required', 'string', 'max:50'],
        'hex' => ['string', 'color_hex'],
        'option_id' => ['numeric', 'exists:options,id,deleted_at,NULL'],
        'is_active' => ['in:yes,no'],
        'color_catalog_id' => ['numeric']
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
     * Get the label attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getLabelAttribute($value)
    {
        return $this->getOriginal('name');
    }

    /**
     * Get object related to 3d Model (through color catalog)
     * @return null
     */
    public function get3dModelAttribute() {
        return $this->catalog ? json_decode($this->catalog->{'3d_model'}) : null ;
    }

    /**
     * Get the list of allowable models IDs for color.
     *
     * @param  string  $value
     * @return string
     */
    public function getAllowableModelsIdAttribute($value)
    {
        return $this->allowable_models->pluck('id')->toArray();
    }

    public function getColorsNameAttribute()
    {
        if (isset($this->attributes['custom'])) return $this->attributes['custom'];
        if (isset($this->pivot) && isset($this->pivot->custom)) {
            return $this->pivot->custom;
        }

        return $this->attributes['name'];
    }

    /**
     * A color belongs to company
     * @return \App\Models\Company
     */
    public function company()
    {
        return $this->belongsTo(ManufacturerCompany::class, MANUFACTURER_COMPANY_ID);
    }

    /**
     * A color belongs to color catalog
     * @return \App\Models\ColorCatalog
     */
    public function catalog()
    {
        return $this->belongsTo(ColorCatalog::class, 'color_catalog_id', 'id');
    } 

    /**
     * A color belongs to an option
     * @return \App\Models\Option
     */
    public function option()
    {
        return $this->belongsTo('App\Models\Option');
    }

    /**
     * A color has many allowable building models
     * @return \App\Models\BuildingModel
     */
    public function allowable_models()
    {
        return $this->belongsToMany(BuildingModel::class, 'color_allowable_models', 'color_id')->withTimestamps();
    }

    /**
     * A color has many allowable optinos
     * @return \App\Models\Option
     */
    public function allowable_options()
    {
        return $this->belongsToMany(Option::class, 'option_allowable_colors', 'color_id')->withTimestamps();
    }

    /**
     * Get the color's image.
     * @return \App\Models\File
     */
    public function image()
    {
        return $this->morphOne('App\Models\File', 'storable');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 'yes');
    }

    /**
     * Filtered & Paginated scope
     * @param  [type]  $query
     * @param  string  $filter
     * @param  integer $count
     * @return [type]
     */
    public function scopeFilteredPaginate($query, $filter = '', $count = 10)
    {
        if (trim($filter) !== '')
        {
            $query->where('name', 'like', '%' . $filter . '%')
                ->orWhere('type', 'like', '%' . $filter . '%')
                ->orWhere('hex', 'like', '%' . $filter . '%')
                ->orWhere('option_id', '=', $filter);
        }

        return $query->paginate($count);
    }
}
