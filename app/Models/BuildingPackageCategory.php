<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;
use HipsterJazzbo\Landlord\BelongsToTenants;

class BuildingPackageCategory extends Model
{
    use SoftDeletes;
    use ModelTrait;
    use BelongsToTenants;

    public $tenantColumns = [MANUFACTURER_COMPANY_ID];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'building_package_categories';

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
     * The attributes that are visible.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'description',
        'is_active',
        MANUFACTURER_COMPANY_ID,
        // custom attrs
        'active',
        // relations
        'company',
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
        'is_active'
    ];

    protected $appends = ['active'];

    public static $rules = [
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
        if (!$this->is_active) return [];

        return collect(self::$isActive[$this->is_active]);
    }

    /**
     * A building package category belongs to company
     * @return \App\Models\Company
     */
    public function company()
    {
        return $this->belongsTo(ManufacturerCompany::class, MANUFACTURER_COMPANY_ID);
    }

    /**
     * Get all of the building package's files.
     * @return \App\Models\File
     */
    public function files()
    {
        return $this->morphMany(File::class, 'storable');
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