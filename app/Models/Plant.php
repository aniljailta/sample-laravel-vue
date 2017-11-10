<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Llama\Database\Eloquent\ModelTrait;
use HipsterJazzbo\Landlord\BelongsToTenants;

class Plant extends Model
{
    use ModelTrait; // ability to use JOIN with relations
    use BelongsToTenants;

    public $tenantColumns = [MANUFACTURER_COMPANY_ID];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'plant_id', // company scoped id
        'name',
        'description',
        'location_id',
        MANUFACTURER_COMPANY_ID,
        // dates
        'created_at',
        'updated_at',
        'deleted_at',
        // relations
        'location',
        'company',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'location_id'
    ];

    public static $rules = [
        'id' => ['numeric','nullable'],
        'plant_id' => ['numeric'],
        'location_id' => ['numeric', 'nullable'],
        'name' => ['string', 'max:255', 'nullable'],
        'description' => ['string', 'max:255', 'nullable'],
    ];

    /**
     * Used for entry ID per company
     * @param Company $company
     * @return string
     */
    public static function getScopedKeyName(Company $company) {
        if ($company->role_id === 'manufacturer') return 'plant_id';
        return 'id';
    }

    /**
     * A plant belongs to company
     * @return \App\Models\Company
     */
    public function manufacturer()
    {
        return $this->belongsTo(ManufacturerCompany::class, MANUFACTURER_COMPANY_ID);
    }

    /**
     * A plant has one location
     * @return \App\Models\Location
     */
    public function location()
    {
        return $this->hasOne('App\Models\Location', 'id', 'location_id');
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
                  ->orWhere('description', 'like', '%' . $filter . '%');
        }

        return $query->paginate($count);
    }
}
