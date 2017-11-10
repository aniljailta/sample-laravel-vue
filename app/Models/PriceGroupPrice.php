<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Llama\Database\Eloquent\ModelTrait;
use HipsterJazzbo\Landlord\BelongsToTenants;

class PriceGroupPrice extends Model
{
    use ModelTrait;
    use BelongsToTenants;

    public $tenantColumns = [MANUFACTURER_COMPANY_ID];

    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    protected $visible = [
        'id',
        'price_group_id',
        'category',
        'item_id',
        'item_name',
        'item_description',
        'price',
        'created_at',
        'updated_at'
    ];

    /**
     * A price group belongs to company
     * @return \App\Models\Company
     */
    public function manufacturer()
    {
        return $this->belongsTo(ManufacturerCompany::class, MANUFACTURER_COMPANY_ID);
    }
}
