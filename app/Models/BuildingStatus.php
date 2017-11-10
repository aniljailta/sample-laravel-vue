<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Llama\Database\Eloquent\ModelTrait;

class BuildingStatus extends Model
{
    use ModelTrait;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'building_statuses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $visible = [
        'id',
        'name',
        'type',
        'priority',
        'is_active',
        'is_system',

        'created_at',
        'updated_at',
        'deleted_at',

        // relations (jsonable)
        'company',
        'building_history',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'type', 'priority', 'is_active', 'is_system'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public static $rules = [
        'id' => ['numeric'],
        'name' => ['string'],
        'type' => ['in:build,sale,delivery'],
        'is_active' => ['in:yes,no'],
    ];

    public static $isActive = [
        'yes' => [
            'id' => 'yes',
            'name' => 'Yes',
        ],
        'no' => [
            'id' => 'no',
            'name' => 'No',
        ]
    ];

    /**
     * A building status belongs to many building history
     * @return \App\Models\BuildingHistory
     */
    public function building_history()
    {
        return $this->belongsToMany('App\Models\BuildingHistory', 'id', 'status_id');
    }

    /**
     * Scope a query to only include active building statuses.
     *
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 'yes');
    }

    /**
     * Filtered & Paginated scope
     * @param  [type]  $query
     * @param  string  $filter
     * @param  integer $count
     * @return [type]
     */
    public function scopeFilteredPaginate($query, $filter = '', $count = 10)
    {
        if (trim($filter) !== '')
        {
            $query->where('name', 'like', '%' . $filter . '%')
                ->orWhere('type', 'like', '%' . $filter . '%')
                ->orWhere('priority', $filter);
        }

        return $query->paginate($count);
    }

    public static function getDisabledBuildingStatusByPriority($priority, Collection $all_building_statuses = null, $type = null)
    {

        if ( !$all_building_statuses && $type)
        {
            $all_building_statuses = BuildingStatus::select('id', 'name', 'priority')
                ->where('is_active', 'yes')
                ->where('type', $type)
                ->orderBy('priority', 'asc')
                ->get();
        }

        $disallowed_statuses = $all_building_statuses->filter(function($item) use ($priority) {
            if ( $item->priority < $priority ) {
                return true;
            }
        });

        return $disallowed_statuses;
    }

}
