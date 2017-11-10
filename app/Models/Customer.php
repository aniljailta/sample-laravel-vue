<?php

namespace App\Models;

use Llama\Database\Eloquent\ModelTrait;
use HipsterJazzbo\Landlord\BelongsToTenants;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use ModelTrait;
    use BelongsToTenants;
    use Notifiable;

    public $tenantColumns = [MANUFACTURER_COMPANY_ID];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are visible.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'created_at',
        'updated_at',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        // custom attributes
        'full_name',
        // relations
        'manufacturer',
        'orders',
        'invoices',
        'payments',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        MANUFACTURER_COMPANY_ID,
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip',
    ];

    protected $appends = array('full_name');

    public static $rules = [
        'id' => ['numeric'],
        'first_name' => ['string', 'max:255'],
        'last_name' => ['string', 'max:255'],
        'email' => ['string', 'email'],
        'phone' => ['regex:'.REGEX_PHONE],
        'address' => ['regex:'.REGEX_ADDRESS],
        'city' => ['regex:'.REGEX_GEO],
        'state' => ['regex:'.REGEX_GEO],
        'zip' => ['regex:'.REGEX_ZIP],
    ];

    /**
     * Get the full name attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getFullNameAttribute($value)
    {
        return $this->first_name .' '. $this->last_name;
    }

    /**
     * A customer belongs to user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A user belongs to company
     * @return \App\Models\Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class, COMPANY_ID);
    }

    /**
     * A customer can have many orders
     * @return \App\Models\Order
     */
    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'customer_id', 'id');
    }

    /**
     * Get all of the users's invoices.
     */
    public function invoices()
    {
        return $this->morphMany('App\Models\Invoice', 'invoiceable');
    }

    /**
     * Get all of the users's payments.
     */
    public function payments()
    {
        return $this->morphMany('App\Models\Payment', 'paymentable');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
