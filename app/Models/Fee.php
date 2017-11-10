<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    const TYPES = [
        'monthly_subscription' => 'Monthly subscription',
        'per_transaction' => 'Per transaction',
        'setup' => 'Setup',
    ];

    /**
     * @var string
     */
    protected $table = 'fee_types';

    /**
     * @var array
     */
    protected $fillable = [
        COMPANY_ID,
        'type',
        'slug',
        'name',
        'description'
    ];

    /**
     * @param $value
     *
     * @return string
     */
    public function getTypeAttribute($value)
    {
        return static::TYPES[$value] ?? 'n/a';
    }
}
