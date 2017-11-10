<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use HipsterJazzbo\Landlord\BelongsToTenants;

class Expense extends Model
{
    use SoftDeletes;
    use BelongsToTenants;

    public $tenantColumns = [MANUFACTURER_COMPANY_ID];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'expenses';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    public function expense()
    {
        return $this->morphTo();
    }

    /**
     * Get all of the building history that are assigned this expense item.
     */
    public function building_history()
    {
        return $this->morphedByMany('App\Models\BuildingHistory', 'expense', 'expenses', 'id')->withPivot(['expense_type']);
    }

    /**
     * Get all of the building locations that are assigned this expense item.
     */
    public function building_locations()
    {
        return $this->morphedByMany('App\Models\BuildingLocation', 'expense', 'expenses', 'id')->withPivot(['expense_type']);
    }

    /**
     * An expense belongs to company
     * @return \App\Models\Company
     */
    public function manufacturer()
    {
        return $this->belongsTo(ManufacturerCompany::class, MANUFACTURER_COMPANY_ID);
    }

    /**
     * An expense has one bill
     * @return \App\Models\Bill
     */
    public function bill()
    {
        return $this->hasOne('App\Models\Bill', 'id', 'bill_id');
    }
}
