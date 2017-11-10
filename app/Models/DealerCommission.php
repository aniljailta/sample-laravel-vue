<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Llama\Database\Eloquent\ModelTrait;

class DealerCommission extends Model
{
    use ModelTrait;
    use BelongsToTenants;
    public $tenantColumns = [MANUFACTURER_COMPANY_ID];

    protected $table = 'dealer_commission';

    const INITIAL_STATUS_ID = 'pending';

    protected $casts = [
        'commission_rate' => 'double',
        'amount_due' => 'double',
        'dealer_discount' => 'double',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $visible = [
        'id',
        MANUFACTURER_COMPANY_ID,
        'created_at',
        'updated_at',
        'order_id',
        'dealer_id',
        'status',
        'commission_rate',
        'dealer_discount',
        'amount_due',
        'user_id',
        'notes',

        'manufacturer',
        'order',
        'dealer'
    ];

    protected $fillable = [
        'created_at',
        'updated_at',
        'order_id',
        'dealer_id',
        'status',
        'commission_rate',
        'dealer_discount',
        'amount_due',
        'user_id',
        'notes'
    ];

    public static $rules = [
        'id' => ['numeric'],
        'order_id' => ['numeric'],
        'dealer_id' => ['numeric'],
        'user_id' => ['numeric'],
        'status' => ['in:pending,processed,cancelled'],
        'commission_rate' => ['numeric'],
        'dealer_discount' => ['numeric'],
        'amount_due' => ['numeric'],
        'notes' => ['string', 'nullable'],
    ];

    /**
     * A building belongs to manufacturer
     * @return \App\Models\ManufacturerCompany
     */
    public function manufacturer()
    {
        return $this->belongsTo(ManufacturerCompany::class, MANUFACTURER_COMPANY_ID);
    }

    /**
     * A dealer commission has one current order
     * @return \App\Models\Order
     */
    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    /**
     * A dealer commission has one current order
     * @return \App\Models\Dealer
     */
    public function dealer()
    {
        return $this->hasOne(Dealer::class, 'id', 'dealer_id');
    }
}
