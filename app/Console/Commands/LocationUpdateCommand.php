<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Services\Locations\GeoLocationService;
use Illuminate\Console\Command;

class LocationUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'location:geocode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use the Google Map API function to find and add latitude and longitude to all current Locations that are missing this data
';

    /**
     * Create a new command instance
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param GeoLocationService $geoLocationService
     * @return mixed
     */
    public function handle(GeoLocationService $geoLocationService)
    {
        $locations = Location::needGeoCode()->get();
        $this->warn($locations->count() . ' locations are missing lat lng. Updating...');

        foreach ($locations as $location) {
            $geoLocationService->fetchLatLng($location);
            $this->info("Fetch #{$location->id} ({$location->name}) Lat: {$location->latitude}, Lang: {$location->longitude}");

            if ($location->category == Location::CATEGORY_OTHER) {
                $location->category = Location::CATEGORY_CUSTOMER;
            }

            $location->save();
        }

        $this->info('Location geocoding completed');
    }
}
