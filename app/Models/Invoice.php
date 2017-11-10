<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;

class Invoice extends Model
{
    use ModelTrait;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invoices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'invoiceable_id',
        'invoiceable_type',
        'amount',
        'invoice_number',
        'status',

        'created_at',
        'updated_at',
        'deleted_at',

        // relations (jsonable)
        'invoiceable',
        'payment'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'invoice_number',
        'status'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'updated_at', 'created_at'];

    public static $rules = [
        'invoiceable_id' => ['numeric'],
        'amount' => ['numeric'],
        'invoice_number' => ['string'],
        'status' => ['string', 'in:open,closed']
    ];


    /**
     * Get all of the owning invoiceable models.
     */
    public function invoiceable()
    {
        return $this->morphTo();
    }

    /**
     * An invoice has one payment
     * @return \App\Models\Payment
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
