<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;
use HipsterJazzbo\Landlord\BelongsToTenants;

class Dealer extends Model
{
    use SoftDeletes;
    use ModelTrait;
    use Notifiable;
    use BelongsToTenants;

    public $tenantColumns = [MANUFACTURER_COMPANY_ID];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'business_name',
        'deposit_type',
        'phone',
        'email',
        'tax_rate',
        'commission_rate',
        'cash_sale_deposit_rate',
        'location_id',
        'is_active',
        'ein',
        MANUFACTURER_COMPANY_ID,
        // dates
        'created_at',
        'updated_at',
        'deleted_at',
        // relations
        'company',
        'location',
        'buildings',
        'files',
        // custom attributes
        'active'
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_name',
        'phone',
        'email',
        'tax_rate',
        'commission_rate',
        'cash_sale_deposit_rate',
        'location_id',
        'is_active',
        'ein'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    public static $rules = [
        'id' => ['numeric'],
        'business_name' => ['string', 'max:255'],
        'phone' => ['regex:'. REGEX_PHONE],
        'email' => ['email'],
        'tax_rate' => ['numeric'],
        'commission_rate' => ['numeric'],
        'cash_sale_deposit_rate' => ['numeric'],
        'location_id' => ['numeric'],
        'is_active' => ['in:yes,no'],
        'ein' => ['regex:/^\d{2}\-\d{7}$/'],
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
    
    protected $appends = ['active'];
    
    public function getActiveAttribute() {
        if (empty($this->is_active)) return null;

        return collect(self::$isActive[$this->is_active]);
    }

    /**
     * A dealer belongs to company
     * @return \App\Models\Company
     */
    public function manufacturer()
    {
        return $this->belongsTo(ManufacturerCompany::class, MANUFACTURER_COMPANY_ID);
    }

    /**
     * A building has one last location
     * @return \App\Models\Location
     */
    public function location()
    {
        return $this->hasOne('App\Models\Location', 'id', 'location_id');
    }

    /**
     * A building belongs to a building
     * @return \App\Models\Building
     */
    public function buildings()
    {
        // return $this->hasMany('App\Models\Building', 'last_location_id', 'location_id');
        return $this->hasManyThrough('App\Models\Building', 'App\Models\BuildingLocation', 'location_id', 'building_id', 'location_id');
    }

    /**
     * Get all of the dealer's files.
     * @return \App\Models\File
     */
    public function files()
    {
        return $this->morphMany('App\Models\File', 'storable');
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
            $query->where('business_name', 'like', '%' . $filter . '%')
                ->orWhere('phone', 'like', '%' . $filter . '%')
                ->orWhere('email', 'like', '%' . $filter . '%')
                ->orWhere('tax_rate', 'like', '%' . $filter . '%')
                ->orWhere('cash_sale_deposit_rate', 'like', '%' . $filter . '%');
        }

        return $query->paginate($count);
    }
}
