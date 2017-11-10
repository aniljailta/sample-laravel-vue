<?php

namespace App\Http\Requests;

use App\Models\BuildingModel;
use Store;
use Exception;
use Entrust;

use App\Http\Requests\Request;
use App\Validators\BuildingModelValidator;

class DeleteBuildingModelRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $this->validator = BuildingModelValidator::make();

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
        
        $id = $this->route('building_model');

        try {
            $item = BuildingModel::findOrFail($id);
            Store::set('buildingModel', $item);

            return true;
        } catch (Exception $e) {
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
        return $this->rules;
    }
}
