<?php

namespace App\Services\Deliveries;

use App\Models\Delivery;
use App\Models\Location;

class GeoCodingService
{
    /**
     * Set Distance by calculating start_location and end_location
     *
     * @param Delivery $delivery
     * @return Delivery
     */
    public function setDistance(Delivery $delivery)
    {
        $startlocation = Location::find($delivery->start_location_id);
        $endLocation = Location::find($delivery->end_location_id);

        if (!$startlocation || !$endLocation) {
            return $delivery;
        }

        $distance = $this->distanceByLocation($startlocation, $endLocation);
        if ($distance) {
            $delivery->distance = round($distance, 2);
            $delivery->drive_duration = round(($distance / $delivery->average_drive_speed), 2);
        }
        return $delivery;
    }

    /**
     * Make distance in Mile between two location object
     *
     * @param Location $startLocation
     * @param Location $endLocation
     * @return integer|bool
     */
    public function distanceByLocation(Location $startLocation, Location $endLocation, $unit = "M")
    {
        if ($startLocation->is_geocoded != 'yes' || $endLocation->is_geocoded !== 'yes') {
            return false;
        }
        return static::calculateDistance($startLocation->latitude, $startLocation->longitude, $endLocation->latitude, $endLocation->longitude, $unit);
    }

    /**
     * Calculate distance between by lat and lng
     *
     * @param $lat1
     * @param $lon1
     * @param $lat2
     * @param $lon2
     * @param string $unit
     * @return float|int
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2, $unit = 'M')
    {
        if ($lat1 === $lat2 && $lon1 === $lon2) {
            return 0;
        }

        $radlat1 = pi() * $lat1 / 180;
        $radlat2 = pi() * $lat2 / 180;
        $radlon1 = pi() * $lon1 / 180;
        $radlon2 = pi() * $lon2 / 180;
        $theta = $lon1 - $lon2;
        $radtheta = pi() * $theta / 180;
        $dist = sin($radlat1) * sin($radlat2) + cos($radlat1) * cos($radlat2) * cos($radtheta);
        $dist = acos($dist);
        $dist = $dist * 180 / pi();
        $dist = $dist * 60 * 1.1515;
        if ($unit == "K" || $unit == "k") {
            $dist = $dist * 1.609344;
        }
        if ($unit == "N" || $unit == "n") {
            $dist = $dist * 0.8684;
        }
        return $dist;
    }

}