<?php

namespace App\Services\Locations;

use App\Models\Location;
use GooglePlaces;
use GuzzleHttp\Client;
use Log;

class GeoLocationService
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var String
     */
    protected $google_places_api_key;

    /**
     * LocationService constructor.
     * @param Location|null $location
     */
    public function __construct($location = null)
    {
        $this->client = new Client();
        $this->google_places_api_key = env('GOOGLE_PLACES_API_KEY');
    }

    /**
     * Fetch and save Lat lng from google
     * @param Location $location
     * @return LocationService
     */
    public function fetchLatLng(Location $location)
    {
        try {
            $response = $this->searchGooglePlaceDetailsByLocation($location);
            if ($response) {
                $geoLocation = isset($response['results'][0]['geometry']['location']) ? $response['results'][0]['geometry']['location'] : [];
                $location->latitude = isset($geoLocation['lat']) ? $geoLocation['lat'] : null;
                $location->longitude = isset($geoLocation['lng']) ? $geoLocation['lng'] : null;
                $location->is_geocoded = 'yes';
            }
            return $location;
        } catch (\Exception $ex) {
            Log::error($ex);
        }

        return $location;
    }

    /**
     * Google Place Details object based on Address. Or Lat Lng if any
     * @param Location $location
     * @return bool
     */
    public function searchGooglePlaceDetailsByLocation(Location $location)
    {
        $where = [];
        if (!empty($location->address)) {
            $where[] = $location->address;
        }
        if (!empty($location->city)) {
            $where[] = $location->city;
        }
        if (!empty($location->state)) {
            $where[] = $location->state;
        }
        if (!empty($location->country)) {
            $where[] = $location->country;
        }
        if (!empty($where)) {
            $locationName = implode(",", $where);
            return GooglePlaces::textSearch($locationName);
        }

        return false;
    }

    /**
     * @param        $lat
     * @param        $lng
     * @param string $resultType
     * @return mixed
     */
    public function getGeoCodeData($lat, $lng, $resultType = 'sublocality|locality|administrative_area_level_1|administrative_area_level_2')
    {
        $result = $this->client->get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $lng . '&result_type=' . $resultType . '&key=' . $this->google_places_api_key, false);
        return json_decode($result->getBody());
    }
}