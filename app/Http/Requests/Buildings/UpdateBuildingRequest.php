<?php

namespace App\Http\Requests\Buildings;

use Store;
use App\Models\Building;
use App\Http\Requests\Request;
use App\Validators\BuildingValidator;

class UpdateBuildingRequest extends Request
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
        $this->validator = BuildingValidator::make($request)->addRules(Building::$rules);

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
        $this->validator->addRule('id', 'is_valid_building');
        $this->validator->append('plant_id', 'exists:plants,id');
        $this->validator->append('building_model_id', 'exists:building_models,id,is_active,yes');
        $this->validator->append('opts', 'array');
        // $this->validator->addRule('options', 'array|is_valid_options');
        
        // multiple files
        $nbr = count($this->input('upload_files')) - 1;
        foreach(range(0, $nbr) as $index) {
            $this->validator->addRule('upload_files.' . $index, 'file');
        }
        
        return $this->rules;
    }
}
