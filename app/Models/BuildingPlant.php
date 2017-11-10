<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use HipsterJazzbo\Landlord\BelongsToTenants;

class BuildingPlant extends Model
{
    use BelongsToTenants;
    public $tenantColumns = [MANUFACTURER_COMPANY_ID];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plant_id',
        'year',
        'building_count',
    ];

    public static function getNextBuildingCount($plantID, $year)
    {
        $row = BuildingPlant::where('plant_id', $plantID)
            ->where('year', $year)
            ->get()->first();

        if (is_null($row)) {
            // nothing for this combo in DB, insert one
            BuildingPlant::create([
                'plant_id' => $plantID,
                'year' => $year,
                'building_count' => 1000,
            ]);
            return 1000;
        } else {
            // there is something, get it and increment it
            $newCount = $row->building_count = $row->building_count + 1;
            $row->save();
            return $newCount;
        }
    }
}
