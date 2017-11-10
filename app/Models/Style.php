<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Llama\Database\Eloquent\ModelTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenants;

class Style extends Model
{
    use ModelTrait; // ability to use JOIN with relations
    use SoftDeletes;
    use BelongsToTenants;

    public $tenantColumns = [MANUFACTURER_COMPANY_ID];

    /**
     * The morphed class name
     *
     * @var string
     */
    protected $morphClass = 'style';

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
        'style_catalog_id',
        'sort_id',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',

        // relations
        'building_models',
        'company',
        'catalog',

        // custom attributes
        '3d_model',
        'files',
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
        'style_catalog_id',
        'sort_id',
        'is_active'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public static $validator = 'App\Validators\StyleValidator';
    public static $rules = [
        'id' => ['numeric'],
        'name' => ['string', 'max:255'],
        'description' => ['string', 'max:255'],
        'short_code' => ['string'],
        'style_catalog_id' => ['numeric'],
        'sort_id' => ['numeric'],
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

    protected $appends = ['3d_model'];

    public function getActiveAttribute() {
        return collect(self::$isActive[$this->is_active]);
    }

    /**
     * Get object related to 3d Model (through style catalog)
     * @return null
     */
    public function get3dModelAttribute() {
        if ($this->relationLoaded('catalog')) return $this->catalog->{'3d_model'};
        return null;
    }

    /**
     * A style belongs to company
     * @return \App\Models\Company
     */
    public function manufacturer()
    {
        return $this->belongsTo(Company::class, MANUFACTURER_COMPANY_ID);
    }

    /**
     * A style belongs to style catalog
     * @return \App\Models\StyleCatalog
     */
    public function catalog()
    {
        return $this->belongsTo(StyleCatalog::class, 'style_catalog_id', 'id');
    }

    /**
     * Style can have more than one building model
     * @return \App\Models\BuildingModel
     */
    public function building_models()
    {
        return $this->hasMany('App\Models\BuildingModel');
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
     * Filtered & Paginated scope
     * @param  [type]  $query
     * @param  string  $filter
     * @param  integer $count
     * @return [type]
     */
    public function scopeFilteredPaginate($query, $filter = '', $count = 10)
    {
        if ($filter !== '')
        {
            $query->where('name', 'like', '%' . $filter . '%')
                  ->orWhere('description', 'like', '%' . $filter . '%')
                  ->orWhere('short_code', 'like', '%' . $filter . '%');
        }

        return $query->paginate($count);
    }
}
