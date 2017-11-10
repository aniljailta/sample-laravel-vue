<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenants;

use Llama\Database\Eloquent\ModelTrait;

class OrderReference extends Model
{
    use SoftDeletes;
    use ModelTrait;
    use BelongsToTenants;

    public $tenantColumns = [MANUFACTURER_COMPANY_ID, RTO_COMPANY_ID];

    protected $casts = [
        'building_in_same_address' => 'boolean'
    ];

    /**
     * The attributes that are visible.
     *
     * @var array
     */
    protected $visible = [
        'id',

        'first_name',
        'last_name',
        'email',
        'phone_number',
        'address',
        'city',
        'state',
        'zip',
        'building_in_same_address',
        'building_location_address',
        'building_location_city',
        'building_location_state',
        'building_location_zip',

        'learning_about_us',
        'learning_about_us_other',

        'property_ownership',
        'landlord_full_name',
        'landlord_phone_number',
        'text_allowed1',
        'cell_phone_number2',
        'text_allowed2',
        'home_phone_number',
        'email_instead_of_mail',

        'renter_employer',
        'renter_employer_phone_number',
        'renter_employer_phone_extension',
        'renter_supervisor',
        'renter_supervisor_occupation',

        'co_renter_first_name',
        'co_renter_last_name',
        'co_renter_employer',
        'co_renter_employer_phone_number',
        'co_renter_employer_phone_extension',
        'co_renter_supervisor',
        'co_renter_supervisor_occupation',

        'reference1_name',
        'reference1_relationship',
        'reference1_phone_number',
        'reference1_address',
        'reference1_city',
        'reference1_state',
        'reference1_zip',

        'reference2_name',
        'reference2_relationship',
        'reference2_phone_number',
        'reference2_address',
        'reference2_city',
        'reference2_state',
        'reference2_zip',

        MANUFACTURER_COMPANY_ID,
        RTO_COMPANY_ID,

        // relations
        'company',

        // custom attributes
        'customer_name',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'address',
        'city',
        'state',
        'zip',
        'building_in_same_address',
        'building_location_address',
        'building_location_city',
        'building_location_state',
        'building_location_zip',

        'learning_about_us',
        'learning_about_us_other',

        'property_ownership',
        'landlord_full_name',
        'landlord_phone_number',
        'text_allowed1',
        'cell_phone_number2',
        'text_allowed2',
        'home_phone_number',
        'email_instead_of_mail',

        'renter_employer',
        'renter_employer_phone_number',
        'renter_employer_phone_extension',
        'renter_supervisor',
        'renter_supervisor_occupation',

        'co_renter_first_name',
        'co_renter_last_name',
        'co_renter_employer',
        'co_renter_employer_phone_number',
        'co_renter_employer_phone_extension',
        'co_renter_supervisor',
        'co_renter_supervisor_occupation',

        'reference1_name',
        'reference1_relationship',
        'reference1_phone_number',
        'reference1_address',
        'reference1_city',
        'reference1_state',
        'reference1_zip',

        'reference2_name',
        'reference2_relationship',
        'reference2_phone_number',
        'reference2_address',
        'reference2_city',
        'reference2_state',
        'reference2_zip',
    ];

    protected $appends = ['customer_name'];

    public static $rules = [
        'id' => ['numeric'],
        // customer
        'first_name' => ['regex:'.REGEX_NAME],
        'last_name' => ['regex:'.REGEX_NAME],
        'email' => ['email'],
        'phone_number' => ['regex:'.REGEX_PHONE],
        'address' => ['regex:'.REGEX_ADDRESS],
        'city' => ['regex:'.REGEX_GEO],
        'state' => ['regex:'.REGEX_GEO],
        'zip' => ['regex:'.REGEX_ZIP],
        'building_in_same_address' => ['boolean'],
        'building_location_address' => ['regex:'.REGEX_ADDRESS],
        'building_location_city' => ['regex:'.REGEX_GEO],
        'building_location_state' => ['regex:'.REGEX_GEO],
        'building_location_zip' => ['regex:'.REGEX_ZIP],

        'learning_about_us' => ['in:drive_by,billboard,online_ad,website,instagram,facebook,yelp,search_engine,home_show,radio,television,other'],
        'learning_about_us_other' => [],

        // renter
        'co_renter_first_name' => ['regex:' . REGEX_NAME],
        'co_renter_last_name' => ['regex:' . REGEX_NAME],

        'property_ownership' => ['in:own,rent'],
        'landlord_full_name' => ['regex:' . REGEX_NAME],
        'landlord_phone_number' => ['regex:' . REGEX_PHONE], //phone regex
        'text_allowed1' => ['in:yes,no'], //phone regex
        'cell_phone_number2' => ['regex:' . REGEX_PHONE], //phone regex
        'text_allowed2' => ['in:yes,no'], //phone regex
        'home_phone_number' => ['regex:' . REGEX_PHONE], //phone regex
        'email_instead_of_mail' => ['boolean'],

        'renter_employer' => ['regex:' . REGEX_NAME], //name
        'renter_employer_phone_number' => ['regex:' . REGEX_PHONE], //phone regex
        'renter_employer_phone_extension' => ['alpha_dash'],
        'renter_supervisor' => ['regex:' . REGEX_NAME],
        'renter_supervisor_occupation' => ['regex:' . REGEX_NAME],

        'co_renter_employer' => ['regex:' . REGEX_NAME], //name
        'co_renter_employer_phone_number' => ['regex:' . REGEX_PHONE], //phone regex
        'co_renter_employer_phone_extension' => ['alpha_dash'],
        'co_renter_supervisor' => ['regex:' . REGEX_NAME],
        'co_renter_supervisor_occupation' => ['regex:' . REGEX_NAME],

        'reference1_name' => ['regex:' . REGEX_NAME],
        'reference1_relationship' => ['regex:' . REGEX_NAME],
        'reference1_phone_number' => ['regex:' . REGEX_PHONE], //phone regex
        'reference1_address' => ['regex:' . REGEX_ADDRESS], // address regex
        'reference1_city' => ['regex:' . REGEX_GEO], //geo regex
        'reference1_state' => ['regex:' . REGEX_GEO], //geo regex
        'reference1_zip' => ['regex:' . REGEX_ZIP], // zip regex

        'reference2_name' => ['regex:' . REGEX_NAME],
        'reference2_relationship' => ['regex:' . REGEX_NAME],
        'reference2_phone_number' => ['regex:' . REGEX_PHONE], //phone regex
        'reference2_address' => ['regex:' . REGEX_ADDRESS], // address regex
        'reference2_city' => ['regex:' . REGEX_GEO], //geo regex
        'reference2_state' => ['regex:' . REGEX_GEO], //geo regex
        'reference2_zip' => ['regex:' . REGEX_ZIP], // zip regex
    ];

    public static $learningAboutUs = [
        'drive_by' => ['id' => 'drive_by', 'title' => 'Drive by'],
        'billboard' => ['id' => 'billboard', 'title' => 'Billboard'],
        'online_ad' => ['id' => 'online_ad', 'title' => 'Online Ad'],
        'website' => ['id' => 'website', 'title' => 'Website'],
        'instagram' => ['id' => 'instagram', 'title' => 'Instagram'],
        'facebook' => ['id' => 'facebook', 'title' => 'Facebook'],
        'yelp' => ['id' => 'yelp', 'title' => 'Yelp'],
        'search_engine' => ['id' => 'search_engine', 'title' => 'Search engine'],
        'home_show' => ['id' => 'home_show', 'title' => 'Home Show'],
        'radio' => ['id' => 'radio', 'title' => 'Radio'],
        'television' => ['id' => 'television', 'title' => 'Television'],
        'other' => ['id' => 'other', 'title' => 'Other'],
    ];

    /**
     * Get the full name attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getCustomerNameAttribute($value)
    {
        return $this->first_name .' '. $this->last_name;
    }

    /**
     * An order reference belongs to company
     * @return \App\Models\Company
     */
    public function manufacturer()
    {
        return $this->belongsTo(ManufacturerCompany::class, MANUFACTURER_COMPANY_ID);
    }

    /**
     * An order reference belongs to company
     * @return \App\Models\Company
     */
    public function rto_company()
    {
        return $this->belongsTo(RtoCompany::class, RTO_COMPANY_ID);
    }

    /**
     * An order reference belongs to order
     * @return \App\Models\Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'id', 'reference_id');
    }
}
