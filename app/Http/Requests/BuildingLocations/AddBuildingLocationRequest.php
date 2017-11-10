<?php

namespace App\Http\Requests\BuildingLocations;

use Store;
use App\Models\Building;
use App\Models\BuildingLocation;
use App\Http\Requests\Request;
use App\Validators\BuildingLocationValidator as Validator;

class AddBuildingLocationRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $request = $this->all();
        array_walk_recursive($request, function(&$item, $key) {
            if ($item === 'null') $item = null;
        });
        Request::merge($request);
        $this->validator = Validator::make($request)->addRules(BuildingLocation::$rules);

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
        $buildingId = $this->route('building');

        try {
            $building = Building::findOrFail($buildingId);

            Store::set('building', $building);
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
        $this->validator->append('location_id', 'required|exists:locations,id');
        return $this->rules;
    }
}
