<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;
use Hemp\Presenter\Presentable;
use HipsterJazzbo\Landlord\BelongsToTenants;

class Order extends Model
{
    use ModelTrait;
    use SoftDeletes;
    use Presentable;
    use BelongsToTenants;

    public $tenantColumns = [MANUFACTURER_COMPANY_ID, RTO_COMPANY_ID];

    const INITIAL_STATUS_ID = 'draft';

    const REGEX_NAME = '/^[a-zA-Z%\s\.\-,]+$/';

    protected $morphClass = 'order';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'deleted_at',
        'updated_at',
        'created_at',

        'note_admin',
        'note_dealer',

        'type',
        'uuid',
        'status_id',
        'original_order_id',
        'change_order_fee',
        'dealer_notes', // dealer notes/descriptions
        'customer_id', // foreign
        'dealer_id', // foreign
        'reference_id', // foreign
        'sales_person',
        'sale_type',
        'payment_type',
        'building_condition',
        'building_id', // foreign
        'rto_term',
        'gross_buydown',
        'amount_received',
        'payment_method',
        'transaction_id',
        // 'delivery_charge', TODO: deprecated in #335
        'dr_level_pad',
        'dr_soft_when_wet',
        'dr_width_restrictions',
        'dr_height_restrictions',
        'dr_requires_site_visit',
        'dr_must_cross_neighboring_prop',
        'dr_notes',
        'order_date',
        'date_submitted',
        'ced_start',
        'ced_end',
        'signature_method_id',
        'threedoptions',
        MANUFACTURER_COMPANY_ID,
        RTO_COMPANY_ID,

        // calculations
        'total_sales_price',
        'total_options',
        'total_taxable_options', // options.taxable = true
        'total_non_taxable_options', // options.taxable = true
        'total_rto_options', // options.rto = true
        'total_non_rto_options', // options.rto = false
        'total_rto_deposit_options', // options.rto_deposit = true
        'total_order', // options.rto = false

        'mf_deposit_amount_due',
        'rto_deposit_amount_due',
        'total_deposit_amount_due',
        'amount_received',
        'mf_deposit_received',
        'rto_deposit_received',

        'dealer_tax_rate',
        'dealer_commission_rate',
        'dealer_commission',

        'po_deposit_amount',
        'security_deposit',
        'rto_deposit',
        'net_buydown',
        'buydown_tax',
        'balance_due',
        'rto_amount',
        'rto_advance_monthly_renewal_payment',
        'rto_sales_tax',
        'rto_total_advanceMonthly_renewal_payment',
        'rto_factor',
        'sales_tax',
        'sales_tax_rate',
        'total_amount',
        
        // relations
        'company',
        'sale',
        'order_reference',
        'dealer',
        'customer',
        'building',
        'files',

        // custom attributes
        'status',
        'payment_type_data',
        'order_type',
        'delivery_remarks_level_pad',
        'delivery_remarks_soft_when_wet',
        'delivery_remarks_width_restrictions',
        'delivery_remarks_height_restrictions',
        'delivery_remarks_must_cross_neighboring_property',
        'delivery_remarks_requires_site_visit',
        'delivery_remarks_notes',
        'original_order'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'note_admin',
        'note_dealer',
        'type', 
        'uuid',
        'status_id',
        // 'original_order_id',
        'change_order_fee',
        'dealer_notes', // dealer notes/descriptions
        'dealer_id', // foreign
        'customer_id', // foreign
        'reference_id', // foreign
        'sales_person',
        'sale_type', 
        'payment_type',
        'building_condition',
        'building_id', // foreign
        'rto_term',
        'gross_buydown',
        'amount_received',
        'payment_method',
        'transaction_id',
        'dr_level_pad',
        'dr_soft_when_wet',
        'dr_width_restrictions',
        'dr_height_restrictions',
        'dr_requires_site_visit',
        'dr_must_cross_neighboring_prop',
        'dr_notes',
        'order_date',
        'date_submitted',
        'ced_start',
        'ced_end',
        'signature_method_id',
        'threedoptions',

        // calculations
        'total_sales_price',

        'dealer_tax_rate',
        'dealer_commission_rate',
        'dealer_commission',

        'security_deposit',
        'rto_deposit',
        'net_buydown',
        'buydown_tax',
        'balance_due',
        'rto_amount',
        'rto_advance_monthly_renewal_payment',
        'rto_sales_tax',
        'rto_total_advance_monthly_renewal_payment',
        'rto_factor',
        'sales_tax',
        'sales_tax_rate',
        'total_amount'
    ];

    /**
     * @var array
     */
    protected $appends = ['status', 'order_type', 'payment_type_data', 'signature_method'];

    /**
     * @var array
     */
    protected $casts = [
        'change_order_fee' => 'float',
        'rto_term' => 'int',
        'promo99' => 'boolean'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'updated_at', 'created_at', 'esigned_on'];

    /**
     * @var array
     */
    public static $paymentTypes = [
        'rto' => [
            'id' => 'rto',
            'title' => 'Rent To Own'
        ],
        'cash' => [
            'id' => 'cash',
            'title' => 'Cash'
        ]
    ];

    /**
     * @var array
     */
    public static $orderTypes = [
        'custom-order' => [
            'id' => 'custom-order',
            'title' => 'Custom Order'
        ],
        'dealer-inventory' => [
            'id' => 'dealer-inventory',
            'title' => 'Dealer Inventory'
        ]
    ];

    /**
     * @var array
     */
    public static $rtoTerms = [
        24 => [
            'value' => 24,
            'name' => '24 months',
            'rto_factor' => 16.8,
            'remaining_percentage' => 70
        ],
        36 => [
            'value' => 36,
            'name' => '36 months',
            'rto_factor' => 21.6,
            'remaining_percentage' => 60
        ],
        48 => [
            'value' => 48,
            'name' => '48 months',
            'rto_factor' => 24.6,
            'remaining_percentage' => 51
        ],
        60 => [
            'value' => 60,
            'name' => '60 months',
            'rto_factor' => 27.2,
            'remaining_percentage' => 45
        ],
    ];

    /**
     * @var array
     */
    public static $statuses = [
        'draft' => [
            'id' => 'draft',
            'title' => 'Draft'
        ],
        'signature_pending' => [
            'id' => 'signature_pending',
            'title' => 'Signature Pending'
        ],
        'signed' => [
            'id' => 'signed',
            'title' => 'Signed'
        ],
        'submitted' => [
            'id' => 'submitted',
            'title' => 'Submitted'
        ],
        'review_needed' => [
            'id' => 'review_needed',
            'title' => 'Review Needed'
        ],
        'sale_generated' => [
            'id' => 'sale_generated',
            'title' => 'Sale generated'
        ],
        'cancellation_requested' => [
            'id' => 'cancellation_requested',
            'title' => 'Cancellation Requested'
        ],
        'cancelled' => [
            'id' => 'cancelled',
            'title' => 'Cancelled'
        ]
    ];

    /**
     * @var array
     */
    public static $signature_methods = [
        'manual' => [
            'id' => 'manual',
            'title' => 'Manual'
        ],
        'e_signature' => [
            'id' => 'e_signature',
            'title' => 'E-Signature'
        ],
    ];

    /**
     * @var string
     */
    public static $validator = 'App\Validators\OrderValidator';

    /**
     * @var array
     */
    public static $rules = [
        'id' => ['numeric'],
        'status_id' => ['in:draft,signature_pending,signed,submitted,review_needed,sale_generated,cancelled,cancellation_requested'],
        'type' => ['in:order,quote'], // TODO: deprecate
        'uuid' => ['uuid'], // nullable?
        'dealer_notes' => ['string'],
        'dealer_tax_rate' => ['numeric'],
        'dealer_id' => ['numeric'],
        'original_order_id' => ['numeric'], // id
        'change_order_fee' => ['numeric'],
        'note_dealer' => ['string', 'nullable'],
        'note_admin' => ['string', 'nullable'],
        'reference_id' => ['numeric'],
        'customer_id' => ['numeric'],
        'sales_person' => ['string', 'regex:' . self::REGEX_NAME],
        'sale_type' => ['in:dealer-inventory,custom-order'],
        'building_condition' => ['in:new,used'],
        'building_id' => ['numeric'],
        'serial' => ['string', 'nullable'],
        'rto_term' => ['in:24,36,48,60'],
        'promo99' => ['boolean'],
        'gross_buydown' => ['numeric'],
        'amount_received' => ['numeric'],
        'mf_deposit_received' => ['numeric'],
        'rto_deposit_received' => ['numeric'],
        'payment_type' => ['in:cash,rto'],
        'payment_method' => ['in:cash,check,credit_card'],
        'transaction_id' => ['alpha_dash'],
        'dr_level_pad' => ['boolean'],
        'dr_soft_when_wet' => ['boolean'],
        'dr_width_restrictions' => ['boolean'],
        'dr_height_restrictions' => ['boolean'],
        'dr_requires_site_visit' => ['boolean'],
        'dr_must_cross_neighboring_prop' => ['boolean'],
        'dr_notes' => ['string', 'nullable'],
        'order_date' => ['date_format:Y-m-d'],
        'order_date' => ['date_format:Y-m-d H:i:s'],
        'ced_start' => ['date_format:Y-m-d'],
        'ced_end' => ['date_format:Y-m-d'],
        'signature_method_id' => ['in:manual,e_signature'],
        'sales_tax_rate' => ['numeric'],
        'threedoptions' => ['json', 'nullable']
    ];

    public $tax_rate; // actual tax rate (alias for sales_tax_rate, not save to db)
    public $tax_factor; // actual tax factor (not save to db)

    // avoid saving to db (no fields)
    public $rto_net_buydown;
    public $rto_total_days_advance_monthly_renewal_payment;
    public $min_amount_apply;

    // avoid saving to db (no fields)
    public $total_options;
    public $total_taxable_options;
    public $total_non_taxable_options;
    public $total_rto_options;
    public $total_non_rto_options;
    public $total_rto_deposit_options;
    public $total_order;

    /**
     * Get rto term params
     *
     * @param  string  $value
     * @return string
     */
    public function getRtoTermParamsAttribute($value)
    {
        if ($this->rto_term && array_key_exists($this->rto_term, self::$rtoTerms)) {
            return self::$rtoTerms[$this->rto_term];
        }
        return null;
    }

    /**
     * Get status
     *
     * @param  string  $value
     * @return string
     */
    public function getStatusAttribute($value)
    {
        if ($this->status_id && array_key_exists($this->status_id, self::$statuses)) {
            return collect(self::$statuses[$this->status_id]);
        }
        return null;
    }

    /**
     * Get payment type
     *
     * @param  string  $value
     * @return string
     */
    public function getPaymentTypeDataAttribute($value)
    {
        if ($this->payment_type && array_key_exists($this->payment_type, self::$paymentTypes)) {
            return collect(self::$paymentTypes[$this->payment_type]);
        }
        return null;
    }

    /**
     * Get order type
     *
     * @param  string  $value
     * @return string
     */
    public function getOrderTypeAttribute($value)
    {
        if ($this->sale_type && array_key_exists($this->sale_type, self::$orderTypes)) {
            return collect(self::$orderTypes[$this->sale_type]);
        }
        return null;
    }

    /**
     * Get signature method
     *
     * @param  string  $value
     * @return string
     */
    public function getSignatureMethodAttribute($value)
    {
        if ($this->signature_method && array_key_exists($this->signature_method, self::$signature_methods)) {
            return collect(self::$signature_methods[$this->signature_method]);
        }
        return null;
    }
    
    /**
     * Get the delivery remarks - level pad
     *
     * @return string
     */
    public function getDeliveryRemarksLevelPadAttribute()
    {
        return $this->dr_level_pad;
    }

    /**
     * Get the delivery remarks - soft when wet
     *
     * @return string
     */
    public function getDeliveryRemarksSoftWhenWetAttribute()
    {
        return $this->dr_soft_when_wet;
    }

    /**
     * Get the delivery remarks - width restictions
     *
     * @return string
     */
    public function getDeliveryRemarksWidthRestrictionsAttribute()
    {
        return $this->dr_width_restrictions;
    }

    /**
     * Get the delivery remarks - height restrictions
     *
     * @return string
     */
    public function getDeliveryRemarksHeightRestrictionsAttribute()
    {
        return $this->dr_height_restrictions;
    }

    /**
     * Get the udelivery remarks - requires site visit
     *
     * @return string
     */
    public function getDeliveryRemarksRequiresSiteVisitAttribute()
    {
        return $this->dr_requires_site_visit;
    }

    /**
     * Get the delivery remarks - must cross neighboring property
     *
     * @return string
     */
    public function getDeliveryRemarksMustCrossNeighboringPropAttribute()
    {
        return $this->must_cross_neighboring_prop;
    }

    /**
     * Get the delivery remarks - notes
     *
     * @return string
     */
    public function getDeliveryRemarksNotesAttribute()
    {
        return $this->dr_notes;
    }

    /**
     * Get the delivery remarks
     *
     * @return array
     */
    public function getDeliveryRemarksAttribute()
    {
        return [
            'level_pad' => $this->delivery_remarks_level_pad,
            'dr_soft_when_wet' => $this->delivery_remarks_soft_when_wet,
            'width_restrictions' => $this->delivery_remarks_width_restrictions,
            'height_restrictions' => $this->delivery_remarks_height_restrictions,
            'requires_site_visit' => $this->delivery_remarks_requires_site_visit,
            'must_cross_neighboring_property' => $this->delivery_remarks_must_cross_neighboring_property,
            'notes' => $this->delivery_remarks_notes
        ];
    }

    /**
     * An order belongs to company
     * @return \App\Models\Company
     */
    public function manufacturer()
    {
        return $this->belongsTo(ManufacturerCompany::class, MANUFACTURER_COMPANY_ID);
    }

    /**
     * An order belongs to company
     * @return \App\Models\Company
     */
    public function rto_company()
    {
        return $this->belongsTo(RtoCompany::class, RTO_COMPANY_ID);
    }

    /**
     * An order has one order reference
     * @return HasOne
     */
    public function order_reference()
    {
        return $this->hasOne(OrderReference::class, 'id', 'reference_id');
    }

    /**
     * An order belongs to customer
     * @return BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * An order has one dealer
     * @return HasOne
     */
    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'dealer_id', 'id');
    }

    /**
     * An order has one building
     * @return HasOne
     */
    public function building()
    {
        return $this->hasOne(Building::class, 'id', 'building_id');
    }

    /**
     * Get all of the orders's files.
     * @return MorphMany
     */
    public function files()
    {
        return $this->morphMany('App\Models\File', 'storable');
    }

    /**
     * An order has many building options
     * @return HasOne
     */
    public function sale()
    {
        return $this->hasOne(Sale::class);
    }

    /**
     * An changed order has one original order
     * @return \App\Models\Order
     */
    public function original_order() {
        return $this->hasOne(Order::class,'id','original_order_id');
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
            $query->where('status', 'like', '%' . $filter . '%')
                ->orWhere('user_id', 'like', '%' . $filter . '%');
        }

        return $query->paginate($count);
    }

    /**
     * An changed order has one original order
     * @return \App\Models\Order
     */
    public function parent_order(){
        return $this->hasOne(Order::class,'id','original_order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany(OrderOption::class);
    }
}
