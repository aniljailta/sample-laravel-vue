<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;
use HipsterJazzbo\Landlord\BelongsToTenants;

class Trailer extends Model
{
    use SoftDeletes, ModelTrait, BelongsToTenants;

    public $tenantColumns = [MANUFACTURER_COMPANY_ID];

    protected $visible = [
        'id',
        'name',
        'delivery_capacity',
        'notes',
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'name',
        'delivery_capacity',
        'notes'
    ];

    public static $rules = [
        'name' => ['string'],
        'delivery_capacity' => ['numeric'],
        'notes' => ['string']
    ];

    /**
     * A trailer belongs to company
     * @return \App\Models\Company
     */
    public function manufacturer()
    {
        return $this->belongsTo(Company::class, MANUFACTURER_COMPANY_ID);
    }
}
