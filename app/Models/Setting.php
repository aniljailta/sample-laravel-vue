<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Llama\Database\Eloquent\ModelTrait;

class Setting extends Model
{
    use ModelTrait; // ability to use JOIN with relations

    protected $primaryKey = 'id'; // or null

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'description',
        'value'
    ];

    public static $validator = 'App\Validators\SettingValidator';
    public static $rules = [
        'id' => ['string'],
        'title' => ['string', 'max:255'],
        'description' => ['string', 'max:255'],
        'value' => ['string'],
    ];

    /**
     * Filtered & Paginated scope
     * @param  [type]  $query
     * @param  string  $filter
     * @param  integer $count
     * @return [type]
     */
    public function scopeFilteredPaginate($query, $filter = '', $count = 10)
    {
        if ($filter !== '')
        {
            $query->where('id', 'like', '%' . $filter . '%')
                  ->orWhere('title', 'like', '%' . $filter . '%')
                  ->orWhere('description', 'like', '%' . $filter . '%');
        }

        return $query->paginate($count);
    }
}
