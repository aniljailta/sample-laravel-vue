<?php

namespace App\Http\Requests\BuildingLocations;

use Store;
use Entrust;

use App\Models\Building;
use App\Models\BuildingLocation;

use App\Http\Requests\Request;
use App\Validators\BuildingLocationValidator as Validator;

class UpdateBuildingLocationRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $routed = [
            'building_id' => $this->route('building'),
            'building_location_id' => $this->route('location')
        ];

        $request = $this->all();
        array_walk_recursive($request, function(&$item, $key) {
            if ($item === 'null') $item = null;
        });
        Request::merge($request);

        $this->validator = Validator::make($request + $routed)->addRules(BuildingLocation::$rules);

        $this->rules();
        $this->runValidator();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: here should be checking for ownership too
        if (!Entrust::hasRole('administrator')) return false;
        
        $buildingId = $this->route('building');
        $buildingLocationId = $this->route('location');

        try {
            $building = Building::with(['building_locations', 'last_location'])->findOrFail($buildingId);
            $requestedBuildingLocation = BuildingLocation::findOrFail($buildingLocationId);

            Store::set('building', $building);
            Store::set('requestedBuildingLocation', $requestedBuildingLocation);

            return true;
        } catch (ModelNotFoundException $e) {
            return false;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->validator->addRule('building_location_id', 'numeric|is_not_first_building_location|is_not_last_building_location|is_not_billed_building_location');
        $this->validator->append('location_id', 'exists:locations,id,deleted_at,NULL');
        return $this->rules;
    }
}
