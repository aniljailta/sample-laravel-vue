<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;

class Company extends Model
{
    use SoftDeletes;
    use Notifiable;
    use ModelTrait;

    public $incrementing = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $visible = [
        COMPANY_ID,
        'domain',
        'is_active',
        'role_id',
    ];

    protected $casts = [
        'is_active' => 'boolean'
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
        'domain',
        'is_active',
        'role_id',
        'service_agreement_accepted',
    ];

    public static $rules = [
        COMPANY_ID => ['id'],
        'domain' => ['string'],
        'is_active' => ['boolean'],
        'role_id' => ['0'], // set null
        'service_agreement_accepted' => ['boolean'],
    ];

    /**
     * A company has one company
     * @return hasOne
     */
    public function company()
    {
        if ($this->role_id === 'manufacturer') {
            return $this->hasOne(ManufacturerCompany::class, COMPANY_ID);
        }

        if ($this->role_id === 'rto') {
            return $this->hasOne(RtoCompany::class, COMPANY_ID);
        }

        if ($this->role_id === 'super_admin') {
            return $this->hasOne(SuperAdminCompany::class, COMPANY_ID);
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fees()
    {
        return $this->belongsToMany(Fee::class, 'company_fees', 'company_id', 'fee_id')
            ->withPivot(['fee_amount'])->withTimestamps();
    }
}
