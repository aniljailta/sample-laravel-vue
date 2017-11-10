<?php

namespace App\Services\Locations;

use App\Models\Location;
use App\Models\File;

class LocationService
{
    /**
     * LocationService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param array $locationParams
     * @return Location
     */
    public function create(array $locationParams): Location {
        $location = new Location($locationParams);
        $location = (new GeoLocationService)->fetchLatLng($location);
        $location->save();
        return $location;
    }

    /**
     * @param Location $location
     * @param array    $locationParams
     * @return Location
     */
    public function update(Location $location, $locationParams): Location {
        $location = $location->fill($locationParams);
        $location = (new GeoLocationService)->fetchLatLng($location);
        $location->save();
        return $location;
    }

    /**
     * @param int $location_id
     * @return mixed
     */
    public function getFiles(int $location_id) {
        return File::with('location')->getbyIdType($location_id , 'location')->get();
    }
}