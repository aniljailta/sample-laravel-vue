<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Llama\Database\Eloquent\ModelTrait;

class ManufacturerCompany extends Model
{
    use ModelTrait; // ability to use JOIN with relations
    use CompanyTrait;

    protected $primaryKey = COMPANY_ID;
    public $incrementing = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'manufacturer_companies';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'created_at', 'updated_at' ];

    /**
     * The attributes that are visible.
     *
     * @var array
     */
    protected $visible = [
        COMPANY_ID,
        'name',
        'address',
        'city',
        'state',
        'zip',
        'phone',
        'email',
        'website',
        'facebook',
        'instagram',
        'pinterest',
        'gplus',

        'time_zone',
        'per_page',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_from',
        'mail_password',
        'mail_encryption',
        'hellosign_client_id',

        'rto_is_used',

        'estimated_delivery_period',
        'lead_time',
        'initial_contact_eligibility',
        'change_order_fee',
        'invoice_generated_email',
        'footnote',
        'include3d',

        'delivery_dispatch',
        'delivery_contact_name',
        'delivery_contact_phone',
        'delivery_contact_email',

        'created_at',
        'updated_at',

        'logo',
        'bills',
        'buildings',
        'users',
        'sales',
        'orders',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        COMPANY_ID,
        'name',
        'address',
        'city',
        'state',
        'zip',
        'phone',
        'email',
        'website',
        'facebook',
        'instagram',
        'pinterest',
        'gplus',

        'time_zone',
        'per_page',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_from',
        'mail_password',
        'mail_encryption',
        'hellosign_client_id',

        'rto_is_used',

        'estimated_delivery_period',
        'lead_time',
        'initial_contact_eligibility',
        'change_order_fee',
        'invoice_generated_email',
        'footnote',
        'include3d',

        'delivery_dispatch',
        'delivery_contact_name',
        'delivery_contact_phone',
        'delivery_contact_email',

        'created_at',
        'updated_at'
    ];

    public static $rules = [
        COMPANY_ID => ['numeric'],
        'id' => ['numeric'],
        'name' => ['regex:' . REGEX_BUSINESS_NAME],
        'address' => ['regex:' . REGEX_ADDRESS],
        'city' => ['regex:' . REGEX_GEO],
        'state' => ['regex:' . REGEX_GEO],
        'zip' => ['regex:' . REGEX_ZIP],
        'phone' => ['regex:' . REGEX_PHONE],
        'email' => ['email'],
        'website' => ['url', 'nullable'],
        'facebook' => ['string', 'nullable'],
        'instagram' => ['string', 'nullable'],
        'pinterest' => ['string', 'nullable'],
        'gplus' => ['string', 'nullable'],

        'time_zone' => ['timezone'],
        'per_page' => ['numeric'],

        'mail_host' => ['string'],
        'mail_port' => ['numeric'],
        'mail_username' => ['string'],
        'mail_from' => ['email'],
        'mail_password' => ['string'],
        'mail_encryption' => ['in:TLS,tls,SSL,ssl', 'nullable'],
        'hellosign_client_id' => ['string', 'min:32', 'max:32'],

        'rto_is_used' => ['boolean'],
        // 'rto_company_id' => ['numeric'],

        'estimated_delivery_period' => ['numeric'],
        'lead_time' => ['numeric'],
        'initial_contact_eligibility' => ['numeric'],
        'change_order_fee' => ['numeric'],
        // 'invoice_generated_email' => ['numeric'],
        'footnote' => ['string', 'nullable'],
        'include3d' => ['boolean'],

        'delivery_dispatch' => ['in:dispatch,driver'],
        'delivery_contact_name' => ['regex:' . REGEX_NAME],
        'delivery_contact_phone' => ['regex:' . REGEX_PHONE],
        'delivery_contact_email' => ['email'],
    ];

    /**
     * Get website domain (used in pdf forms)
     * @return mixed
     */
    public function getWebsiteDomainAttribute() {
        return parse_url($this->website, PHP_URL_HOST);
    }

    /**
     * A company has many bills
     * @return \App\Models\Bill
     */
    public function bills()
    {
        return $this->hasMany(Bill::class, COMPANY_ID);
    }

    /**
     * A company has many buildings
     * @return \App\Models\Building
     */
    public function buildings()
    {
        return $this->hasMany(Building::class, MANUFACTURER_COMPANY_ID, COMPANY_ID);
    }

    /**
     * A company has many expenses
     * @return \App\Models\Expense
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class, COMPANY_ID);
    }

    /**
     * A company has many users
     * @return \App\Models\User
     */
    public function users()
    {
        return $this->hasMany(User::class, COMPANY_ID, COMPANY_ID);
    }

    /**
     * A company has many sales
     * @return \App\Models\Sale
     */
    public function sales()
    {
        return $this->hasMany(Sale::class, MANUFACTURER_COMPANY_ID, COMPANY_ID);
    }

    /**
     * A company has many orders
     * @return \App\Models\Order
     */
    public function orders()
    {
        return $this->hasMany(Order::class, MANUFACTURER_COMPANY_ID, COMPANY_ID);
    }

    /**
     * A company has many styles
     * @return \App\Models\Style
     */
    public function styles()
    {
        return $this->hasMany(Style::class, MANUFACTURER_COMPANY_ID, COMPANY_ID);
    }

    /**
     * A company has many options
     * @return \App\Models\Option
     */
    public function options()
    {
        return $this->hasMany(Option::class, MANUFACTURER_COMPANY_ID, COMPANY_ID);
    }

    /**
     * A company has many colors
     * @return \App\Models\Color
     */
    public function colors()
    {
        return $this->hasMany(Color::class, MANUFACTURER_COMPANY_ID, COMPANY_ID);
    }

    /**
     * A company has many allowable styles
     * @return \App\Models\StyleCatalog
     */
    public function allowable_styles()
    {
        return $this->belongsToMany(StyleCatalog::class, 'styles_allowable_companies', MANUFACTURER_COMPANY_ID, 'style_catalog_id')->withTimestamps();
    }

    /**
     * An company has many allowable options
     * @return \App\Models\OptionCatalog
     */
    public function allowable_options()
    {
        return $this->belongsToMany(OptionCatalog::class, 'option_allowable_companies', MANUFACTURER_COMPANY_ID, 'option_catalog_id')->withTimestamps();
    }

    /**
     * A company has many allowable colors
     * @return \App\Models\ColorCatalog
     */
    public function allowable_colors()
    {
        return $this->belongsToMany(ColorCatalog::class, 'color_allowable_companies', MANUFACTURER_COMPANY_ID, 'color_catalog_id')->withTimestamps();
    }
}
