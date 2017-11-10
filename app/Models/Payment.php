<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;

class Payment extends Model
{
    use ModelTrait;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'invoice_id',
        'paymentable_id',
        'paymentable_type',
        'amount',
        'payment_method',
        'transaction_id',
        'status',

        'created_at',
        'updated_at',
        'deleted_at',

        // relations (jsonable)
        'invoice'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'updated_at', 'created_at'];

    public static $rules = [
        'invoice_id' => ['numeric'],
        'paymentable_id' => ['numeric'],
        'amount' => ['numeric'],
        'payment_method' => ['in:cash,check,credit_card,ach,wire_transfer'],
        'transaction_id' => ['numeric'],
        'status' => ['in:pending,complete,cancelled']
    ];


    /**
     * An invoice belongs to sale
     * @return \App\Models\Sale
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    /**
     * Get all of the owning invoiceable models.
     */
    public function paymentable()
    {
        return $this->morphTo();
    }
}
