<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Llama\Database\Eloquent\ModelTrait;

class BuildingHistory extends Model
{

    use ModelTrait;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'building_history';

    /**
     * The morphed class name
     *
     * @var string
     */
    protected $morphClass = 'status';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'user_id',
        'building_id',
        'status_id',

        'created_at',
        'updated_at',
        'deleted_at',
        
        // custom attrs

        // relations (jsonable)
        'building',
        'building_status',
        'user',
        'expense',
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 
        'building_id', 
        'status_id', 
    ];

    public static $rules = [
        'id' => ['numeric'],
        'user_id' => ['numeric'],
        'building_id' => ['numeric'],
        'status_id' => ['numeric'],
    ];

    /*
     * Re-define boot for deliting children relations (soft)
     */
    protected static function boot() {
        parent::boot();

        static::deleting(function($buildingHistory) {
            $buildingHistory->expense()->delete();
        });
    }

    /**
     * A building history belongs to a building
     * @return \App\Models\Building
     */
    public function building()
    {
        return $this->belongsTo('App\Models\Building', 'building_id', 'id');
    }

    /**
     * A building history has one building status
     * @return \App\Models\BuildingStatus
     */
    public function building_status()
    {
        return $this->hasOne('App\Models\BuildingStatus', 'id', 'status_id');
    }

    /**
     * A building history belongs to user
     * @return \App\Models\User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * A building history belongs to expense
     * @return \App\Models\Expense
     */
    public function expense()
    {
        return $this->morphOne('App\Models\Expense', 'expense');
    }
}