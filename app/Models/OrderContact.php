<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Llama\Database\Eloquent\ModelTrait;
use HipsterJazzbo\Landlord\BelongsToTenants;

class OrderContact extends Model
{
    use ModelTrait;
    use BelongsToTenants;

    public $tenantColumns = [MANUFACTURER_COMPANY_ID];

    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $table = 'order_contacts';

    protected $visible = [
        MANUFACTURER_COMPANY_ID,
        'id',
        'order_id',
        'customer_id',
        'initial_contact',
        'order_submit',
        'created_at',
        'updated_at',
        'total_price',

        // relations
        'customer',
        'building',
        'order'
    ];

    /**
     * An order contact belongs to company
     * @return \App\Models\Company
     */
    public function manufacturer()
    {
        return $this->belongsTo(ManufacturerCompany::class, MANUFACTURER_COMPANY_ID);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function building()
    {
        return $this->hasOne('App\Models\Building', 'order_id', 'order_id');
    }
}
