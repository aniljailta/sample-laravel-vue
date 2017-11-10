<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;
use GuzzleHttp\Client;
use GooglePlaces;
use HipsterJazzbo\Landlord\BelongsToTenants;

class Location extends Model
{
    use SoftDeletes;
    use ModelTrait;
    use BelongsToTenants;

    public $tenantColumns = [MANUFACTURER_COMPANY_ID];

    const CATEGORY_CUSTOMER = 'customer';
    const CATEGORY_DEALER = 'dealer';
    const CATEGORY_PLANT = 'plant';
    const CATEGORY_OTHER = 'other';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'locations';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $visible = [
        MANUFACTURER_COMPANY_ID,
        'id',
        'name',
        'address',
        'country',
        'city',
        'state',
        'zip',
        'latitude',
        'longitude',
        'category',
        'is_geocoded',
        'is_active',
        'notes',
        // dates
        'created_at',
        'updated_at',
        'deleted_at',
        // relations
        'company',
        'dealer',
        'building_locations'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'country',
        'city',
        'state',
        'zip',
        'latitude',
        'longitude',
        'category',
        'is_geocoded',
        'is_active',
        'notes'
    ];

    public static $validator = 'App\Validators\LocationValidator';
    public static $rules = [
        'id' => ['numeric'],
        'name' => ['string', 'max:255'],
        'address' => ['string', 'max:255'],
        'country' => ['string', 'max:255'],
        'city' => ['string', 'max:255'],
        'state' => ['string', 'max:255'],
        'zip' => ['regex:' . REGEX_ZIP],
        'latitude' => ['string', 'max:255'],
        'longitude' => ['string', 'max:255'],
        'is_geocoded' => ['string', 'max:255'],
        'is_active' => ['string', 'in:yes,no'],
        'notes' => ['string', 'max:255', 'nullable']
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

    public static $categories = [
        'dealer' => [
            'id' => 'dealer',
            'name' => 'Dealer',
        ],
        'customer' => [
            'id' => 'customer',
            'name' => 'Customer',
        ],
        'other' => [
            'id' => 'other',
            'name' => 'Other',
        ],
        'plant' => [
            'id' => 'plant',
            'name' => 'Plant',
        ],
    ];
    public function getActiveAttribute() {
        if (empty($this->is_active)) return null;

        return collect(self::$isActive[$this->is_active]);
    }
    /**
     * A location belongs to company
     * @return \App\Models\Company
     */
    public function manufacturer()
    {
        return $this->belongsTo(ManufacturerCompany::class, MANUFACTURER_COMPANY_ID);
    }

    /**
     * A location has one dealer
     * @return \App\Models\Dealer
     */
    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'id', 'location_id');
    }

    /**
     * A location belongs to many building locations
     * @return \App\Models\BuildingLocation
     */
    public function building_locations()
    {
        return $this->belongsToMany('App\Models\BuildingLocation', 'id', 'location_id');
    }

    /**
     * Filtered & Paginated scope
     * @param  [type]  $query
     * @param  string $filter
     * @param  integer $count
     * @return [type]
     */
    public function scopeFilteredPaginate($query, $filter = '', $count = 10)
    {
        if (trim($filter) !== '') {
            $query->where('name', 'like', '%' . $filter . '%')
                ->orWhere('address', 'like', '%' . $filter . '%')
                ->orWhere('city', 'like', '%' . $filter . '%')
                ->orWhere('state', 'like', '%' . $filter . '%')
                ->orWhere('zip', 'like', '%' . $filter . '%')
                ->orWhere('latitude', 'like', '%' . $filter . '%')
                ->orWhere('longtitude', 'like', '%' . $filter . '%')
                ->orWhere('is_geocoded', $filter);
        }

        return $query->paginate($count);
    }

    /**
     * Active scope
     * @param  [type]  $query
     * @return [type]
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 'yes');
    }

    /**
     * Inactive scope
     * @param  [type]  $query
     * @return [type]
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', 'no');
    }

    /**
     * All the rows that missing Lat and Lng data
     * @param $query
     */
    public function scopeNeedGeoCode($query)
    {
        return $query->where('is_geocoded', 'no');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function qrcode()
    {
        return $this->where('type', '=', 'location')
            ->hasOne(Qrcode::class, 'building_id', 'id');
    }
}
