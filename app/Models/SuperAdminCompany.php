<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;

class SuperAdminCompany extends Model
{
    use CompanyTrait;
    use SoftDeletes;
    use Notifiable;
    use ModelTrait;

    const SIGNER_ROLE = 'super_admin_company';

    protected $primaryKey = COMPANY_ID;
    public $incrementing = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'super_admin_companies';

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

        'time_zone',
        'per_page',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_from',
        'mail_password',
        'mail_encryption',
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

        'time_zone',
        'per_page',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_from',
        'mail_password',
        'mail_encryption',
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

        'mail_host' => ['string'],
        'mail_port' => ['numeric'],
        'mail_username' => ['string'],
        'mail_from' => ['email'],
        'mail_password' => ['string'],
        'mail_encryption' => ['in:TLS,tls,SSL,ssl'],
    ];

    /**
     * A company has many users
     * @return \App\Models\User
     */
    public function users()
    {
        return $this->hasMany(User::class, COMPANY_ID);
    }
}
