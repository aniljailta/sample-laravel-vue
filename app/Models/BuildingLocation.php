<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;

class BuildingLocation extends Model
{
    use SoftDeletes;
    use ModelTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'building_locations';

    /**
     * The morphed class name
     *
     * @var string
     */
    protected $morphClass = 'location';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        // dates
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
        'location_id',
        // dates
        'created_at',
        'updated_at',
        'deleted_at',
        // relations
        'dealer',
        'location',
        'user',
        'building',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'building_id',
        'location_id'
    ];

    public static $rules = [
        'id' => ['numeric'],
        'user_id' => ['numeric'],
        'building_id' => ['numeric'],
        'location_id' => ['numeric'],
    ];

    /*
     * Re-define boot for deliting children relations (soft)
     */
    /* protected static function boot() {
        parent::boot();

        static::deleting(function($buildingLocation) {
            $buildingLocation->expense()->delete();
        });
    }*/

    /**
     * A building location belongs to location
     * @return \App\Models\BuildingStatus
     */
    public function location()
    {
        return $this->belongsTo('App\Models\Location', 'location_id', 'id');
    }

    /**
     * A building location belongs to a building
     * @return \App\Models\Building
     */
    public function building()
    {
        return $this->belongsTo('App\Models\Building', 'building_id', 'id');
    }

    /**
     * A building location belongs to user
     * @return \App\Models\User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    
    /**
     * A building location belongs to dealer
     * @return \App\Models\Dealer
     */
    public function dealer()
    {
        return $this->belongsTo('App\Models\Dealer', 'location_id', 'location_id');
    }
}
