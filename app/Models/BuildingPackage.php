<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;
use HipsterJazzbo\Landlord\BelongsToTenants;

class BuildingPackage extends Model
{
    use SoftDeletes;
    use ModelTrait;
    use BelongsToTenants;

    public $tenantColumns = [MANUFACTURER_COMPANY_ID];

    protected $morphClass = 'building-package';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'building_packages';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'description',
        'building_model_id',
        'category_id',
        'total_price',
        'is_active',
        MANUFACTURER_COMPANY_ID,
        
        // custom atts
        'active',
        // relations
        'company',
        'building_model',
        'category',
        'options',
        'files',
        // dates
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'description', 
        'building_model_id', 
        'category_id', 
        'is_active'
    ];

    protected $appends = ['active'];

    public static $rules = [
        'id' => ['numeric'],
        'building_model_id' => ['numeric'],
        'category_id' => ['numeric'],
        'name' => ['string'],
        'description' => ['string', 'nullable'],
        'is_active' => ['string', 'in:yes,no,update_required'],
    ];

    public static $isActive = [
        'yes' => [
            'id' => 'yes',
            'name' => 'Yes',
        ],
        'no' => [
            'id' => 'no',
            'name' => 'No',
        ],
        'update_required' => [
            'id' => 'update_required',
            'name' => 'Update Required',
        ],
    ];

    public function getActiveAttribute() {
        return collect(self::$isActive[$this->is_active]);
    }

    /**
     * A building package belongs to company
     * @return \App\Models\Company
     */
    public function company()
    {
        return $this->belongsTo(ManufacturerCompany::class, MANUFACTURER_COMPANY_ID);
    }
    
    /**
     * A building package belongs to a building model
     * @return \App\Models\BuildingModel
     */
    public function building_model()
    {
        return $this->belongsTo(BuildingModel::class)->withTrashed();
    }

    /**
     * A building package belongs to a building package category
     * @return \App\Models\BuildingPackageCategory
     */
    public function category()
    {
        return $this->hasOne(BuildingPackageCategory::class, 'id', 'category_id')->withTrashed();
    }

    /**
     * A building package has many building package options
     * @return \App\Models\BuildingOption
     */
    public function options()
    {
        return $this->hasMany(BuildingPackageOption::class);
    }

    /**
     * Get all of the building package's files.
     * @return \App\Models\File
     */
    public function files()
    {
        return $this->morphMany('App\Models\File', 'storable');
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
        if ($filter !== '')
        {
            $query->whereNull('deleted_at')
                ->where(function ($query) use($filter) {
                    $query->where('name', 'like', '%' . $filter . '%')
                        ->orWhere('description', 'like', '%' . $filter . '%');
                });
        }

        return $query->paginate($count);
    }
}