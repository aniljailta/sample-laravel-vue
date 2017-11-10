<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderOption extends Model
{
    /**
     * @var string
     */
    public $table = 'order_options';

    /**
     * @var array
     */
    protected $guarded = ['created_at'];

    protected $casts = [
        'unit_price' => 'double',
        'total_price' => 'double',
        'quantity' => 'int',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function order()
    {
        return $this->hasOne(Order::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function option()
    {
        return $this->belongsTo(Option::class);
    }

    /**
     * @return array
     */

    public function toArray()
    {
        return [
            'id' => $this->id,
            'option_id' => $this->option->id,
            'category_id' => $this->option->category_id,
            'description' => $this->option->description,
            'force_quantity' => $this->option->force_quantity,
            'force_quantity_flag' => null,
            'is_active' => $this->option->is_active,
            'material_id' => $this->option->material_id,
            'name' => $this->option->name,
            'quantity' => (int) $this->quantity,
            'total' => (double) $this->total_price,
            'total_price' => (double) $this->total_price,
            'unit_price' => (double) $this->unit_price,
            'taxable' => (double) $this->option->taxable,
            'rto' => (double) $this->option->rto,
            'rto_deposit' => $this->option->rto_deposit,
            'delivery_charge' => $this->option->delivery_charge,
        ];
    }
}