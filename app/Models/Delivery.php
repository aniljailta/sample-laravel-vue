<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;
use HipsterJazzbo\Landlord\BelongsToTenants;

class Delivery extends Model
{
    use SoftDeletes;
    use ModelTrait;
    use BelongsToTenants;

    public $tenantColumns = [MANUFACTURER_COMPANY_ID];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'promised_by_date',
        'scheduled_date',
        'completed_date',
        'sale_id',
        'building_id',
        'start_location_id',
        'end_location_id',

        'start_time',
        'end_time',
        'cost',
        'driver_id',
        'truck_id',
        'trailer_id',

        'status_id',
        'category_id',
        'priority_id',

        'distance',
        'confirmed_distance',
        'setup_duration',
        'drive_duration',
        'average_drive_speed',
        'notes',
        'created_at',
        'updated_at',
        'deleted_at',

        MANUFACTURER_COMPANY_ID,

        // custom attributes
        'status',
        'priority',
        'category',

        // relations (jsonable)
        'company',
        'driver',
        'user',
        'building',
        'sale',
        'start_location',
        'end_location',
        'truck',
        'trailer'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'promised_by_date',
        'scheduled_date',
        'completed_date',
        'sale_id',
        'building_id',
        'start_location_id',
        'end_location_id',

        'start_time',
        'end_time',
        'cost',
        'driver_id',
        'truck_id',
        'trailer_id',

        'status_id',
        'category_id',
        'priority_id',

        'confirmed_distance',
        'setup_duration',
        'average_drive_speed',
        'notes',
    ];
    protected $appends = array('status','priority','category');

    protected $dates = [
        'deleted_at'
    ];

    public static $statuses = [
        'draft' => [
            'id' => 'draft',
            'name' => 'Draft',
        ],
        'pending' => [
            'id' => 'pending',
            'name' => 'Pending',
        ],
        'estimated' => [
            'id' => 'estimated',
            'name' => 'Estimated',
        ],
        'confirmed' => [
            'id' => 'confirmed',
            'name' => 'Confirmed',
        ],
        'in_process' => [
            'id' => 'in_process',
            'name' => 'In Process',
        ],
        'completed' => [
            'id' => 'completed',
            'name' => 'Completed',
        ]
    ];

    public static $categories = [

        'customer_delivery' => [
            'id' => 'customer_delivery',
            'name' => 'Customer Delivery'
        ],
        'inventory_move' => [
            'id' => 'inventory_move',
            'name' => 'Inventory Move'
        ],
        'customer_pickup' => [
            'id' => 'customer_pickup',
            'name' => 'Customer Pickup'
        ],
        'repo' => [
            'id' => 'repo',
            'name' => 'Repo'
        ],
    ];

    public static $priorities = [
        'normal' => [
            'id' => 'normal',
            'name' => 'Normal'
        ],
        'urgent' => [
            'id' => 'urgent',
            'name' => 'Urgent'
        ],
        'critical' => [
            'id' => 'critical',
            'name' => 'Critical'
        ]
    ];

    public static $rules = [
        'id' => ['numeric'],
        'promised_by_date' => ['date_format:Y-m-d'],
        'scheduled_date' => ['date_format:Y-m-d'],
        'completed_date' => ['date_format:Y-m-d'],

        'sale_id' => ['numeric'],
        'building_id' => ['numeric'],
        'start_location_id' => ['numeric'],
        'end_location_id' => ['numeric'],

        'start_time' => ['date_format:Y-m-d'],
        'end_time' => ['date_format:Y-m-d'],
        'cost' => ['numeric'],

        'driver_id' => ['numeric'],
        'truck_id' => ['numeric'],
        'trailer_id' => ['numeric'],

        'status_id' => ['string', 'in:draft,pending,estimated,confirmed,in_process,complete'],
        'category_id' => ['string', 'in:customer_delivery,inventory_move,customer_pickup,repo'],
        'priority_id' => ['string', 'in:normal,urgent,critical'],

        'distance' => ['numeric'],
        'confirmed_distance' => ['numeric'],
        'setup_duration' => ['numeric'],
        'drive_duration' => ['numeric'],
        'average_drive_speed' => ['numeric'],
        'dispatcher_id' => ['numeric'],

        'notes' => ['string'],
    ];

    //protected $appends = array('status');

    /**
     * Get the Status attrs.
     *
     * @param  string $value
     * @return string
     */
    public function getStatusAttribute($value)
    {
        if ($this->status_id && array_key_exists($this->status_id, static::$statuses)) {
            return static::$statuses[$this->status_id];
        }
        return null;
    }

    /**
     * Get the catogory attrs.
     *
     * @param  string $value
     * @return string
     */
    public function getCategoryAttribute($value)
    {
        if ($this->category_id && array_key_exists($this->category_id, static::$categories)) {
            return static::$categories[$this->category_id];
        }
        return null;
    }

    /**
     * Get the catogory attrs.
     *
     * @param  string $value
     * @return string
     */
    public function getPriorityAttribute($value)
    {
        if ($this->priority_id && array_key_exists($this->priority_id, static::$priorities)) {
            return static::$priorities[$this->priority_id];
        }
        return null;
    }


    /**
     * A delivery belongs to company
     * @return \App\Models\Company
     */
    public function manufacturer()
    {
        return $this->belongsTo(ManufacturerCompany::class, MANUFACTURER_COMPANY_ID);
    }

    /**
     * A delivery belongs to user
     * @return \App\Models\User
     */
    public function driver()
    {
        return $this->belongsTo('App\Models\User', 'driver_id', 'id');
    }

    /**
     * A delivery belongs to a building
     * @return \App\Models\Building
     */
    public function building()
    {
        return $this->belongsTo('App\Models\Building', 'building_id', 'id');
    }

    /**
     * A delivery belongs to a sale
     * @return \App\Models\Building
     */
    public function sale()
    {
        return $this->belongsTo('App\Models\Sale', 'sale_id', 'id');
    }

    /**
     * A delivery has one to a start location
     * @return \App\Models\Location
     */
    public function start_location()
    {
        return $this->hasOne('App\Models\Location', 'id', 'start_location_id');
    }

    /**
     * A delivery has one to a end location
     * @return \App\Models\Location
     */
    public function end_location()
    {
        return $this->hasOne('App\Models\Location', 'id', 'end_location_id');
    }

    /**
     * Truck
     * @return Truck
     */
    public function truck()
    {
        return $this->belongsTo(Truck::class, 'truck_id');
    }

    /**
     * Trailer
     *
     * @return Trailer
     */
    public function trailer()
    {
        return $this->belongsTo(Trailer::class, 'trailer_id');
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
        if ($filter !== '') {
            $query->where('status_id', 'like', '%' . $filter . '%')
                ->orWhere('user_id', 'like', '%' . $filter . '%');
        }
        return $query->paginate($count);
    }
}
