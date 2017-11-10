<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenants;

class Bill extends Model
{
    use SoftDeletes;
    use BelongsToTenants;

    public $tenantColumns = [MANUFACTURER_COMPANY_ID];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bills';

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
    protected $fillable = ['user_id', 'date', 'amount'];

    protected $appends = ['number'];

    /**
     * A bill belongs to company
     * @return \App\Models\Company
     */
    public function company()
    {
        return $this->belongsTo(ManufacturerCompany::class, MANUFACTURER_COMPANY_ID);
    }

    /**
     * A bill has many expenses
     * @return \App\Models\BuildingLocation
     */
    public function expenses()
    {
        return $this->hasMany('App\Models\Expense', 'bill_id', 'id');
    }

    /**
     * A bill belongs to user (contractor)
     * @return \App\Models\User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id'); 
    }

    /**
     * Get the bill number attribute (temp format).
     *
     * @param  string  $value
     * @return string
     */
    public function getNumberAttribute($value)
    {
        return str_replace('-', '', $this->date) . '-' . $this->user_id;
    }

    /**
     * Get all of the building history that are assigned this bill.
     */
    public function building_history()
    {
        return $this->morphedByMany('App\Models\BuildingHistory', 'expense')->withPivot(['expense_type', 'cost']);
    }

    /**
     * Get all of the building locations that are assigned this bill.
     */
    public function building_locations()
    {
        return $this->morphedByMany('App\Models\BuildingLocation', 'expense')->withPivot(['expense_type', 'cost']);
    }

    /**
     * Get all of the owning billable models.
     */
    public function getBillableAttribute($value)
    {
        // There two calls return collections
        // as defined in relations.
        $stack = collect ([
            $this->getRelation('building_history'),
            $this->getRelation('building_locations')
        ]);

        // Merge collections and return single collection.
        return $stack->collapse();
    }
}
