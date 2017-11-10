<?php

namespace App\Models;

use App\Notifications\Users\ResetPassword as ResetPasswordNotification;
use Llama\Database\Eloquent\ModelTrait;
use HipsterJazzbo\Landlord\BelongsToTenants;

use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, /*Authorizable*/ CanResetPassword;
    use EntrustUserTrait {
        roles as rolesNotUsed;
    }

    use ModelTrait;
    use BelongsToTenants;
    use Notifiable;

    public $tenantColumns = [COMPANY_ID];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

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
        'phone',
        'title',
        // custom attributes
        'full_name',
        // relations
        'company',
        'orders',
        'invoices',
        'roles',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        COMPANY_ID,
        'first_name',
        'last_name',
        'title',
        'email',
        'phone',
        'password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $appends = ['full_name'];

    /**
     * @var array
     */
    public static $rules = [
        'id' => ['numeric'],
        'first_name' => ['string', 'max:255'],
        'last_name' => ['string', 'max:255'],
        'title' => ['string', 'max:255'],
        'email' => ['string', 'max:255'],
        'phone' => ['regex:' . REGEX_PHONE],
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
     * A user belongs to company
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, COMPANY_ID);
    }

    /**
     * A customer can have many orders
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
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
