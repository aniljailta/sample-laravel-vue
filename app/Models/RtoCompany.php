<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;

class RtoCompany extends Model
{
    use CompanyTrait;
    use SoftDeletes;
    use Notifiable;
    use ModelTrait;

    const SIGNER_ROLE = 'rto_company';

    protected $primaryKey = COMPANY_ID;
    public $incrementing = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rto_companies';

    /**
     * The attributes that are mass assignable.
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
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'created_at', 'updated_at', 'deleted_at' ];

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
    ];

    public static $rules = [
        COMPANY_ID => ['numeric'],
        'name' => ['regex:' . REGEX_BUSINESS_NAME],
        'address' => ['regex:' . REGEX_ADDRESS],
        'city' => ['regex:' . REGEX_GEO],
        'state' => ['regex:' . REGEX_GEO],
        'zip' => ['regex:' . REGEX_ZIP],
        'phone' => ['regex:' . REGEX_PHONE],
        'email' => ['email'],
    ];

    protected $appends = ['id'];

    /**
     * Get all of the rto company's invoices.
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
     * A company has many users
     * @return \App\Models\User
     */
    public function users()
    {
        return $this->hasMany(User::class, COMPANY_ID);
    }

    /**
     * A company has many sales
     * @return \App\Models\Sale
     */
    public function sales()
    {
        return $this->hasMany(Sale::class, RTO_COMPANY_ID);
    }

    /**
     * A company has many orders
     * @return \App\Models\Order
     */
    public function orders()
    {
        return $this->hasMany(Order::class, RTO_COMPANY_ID);
    }

    /**
     * A company has many orders
     * @return \App\Models\OrderReference
     */
    public function order_references()
    {
        return $this->hasMany(OrderReference::class, RTO_COMPANY_ID);
    }

    /**
     * @return mixed
     */
    public function getIdAttribute() {
        return $this->{COMPANY_ID};
    }
}
