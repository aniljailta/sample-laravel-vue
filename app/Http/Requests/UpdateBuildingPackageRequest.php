<?php

namespace App\Http\Requests;

use Store;
use Exception;
use App\Models\BuildingPackage;
use App\Http\Requests\Request;
use App\Validators\Validator;

class UpdateBuildingPackageRequest extends Request
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
        $this->validator = Validator::make($request)->addRules(BuildingPackage::$rules);

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
        $id = $this->route('building_package');

        try {
            $setting = BuildingPackage::where('id', $id)->firstOrFail();
            Store::set('buildingPackage', $setting);

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
        $this->validator->append('building_model_id', 'exists:building_models,id');
        $this->validator->append('category_id', 'exists:building_package_categories,id');
        // $this->validator->addRule('options', 'exists:options,id');
        
        // multiple files
        $nbr = count($this->input('upload_files')) - 1;
        foreach(range(0, $nbr) as $index) {
            $this->validator->addRule('upload_files.' . $index, 'file');
        }
        
        return $this->rules;
    }
}
