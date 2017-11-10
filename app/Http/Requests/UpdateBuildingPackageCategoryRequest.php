<?php

namespace App\Http\Requests;

use Store;
use Exception;
use App\Models\BuildingPackageCategory;
use App\Http\Requests\Request;
use App\Validators\Validator;

class UpdateBuildingPackageCategoryRequest extends Request
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
        $this->validator = Validator::make($request)->addRules(BuildingPackageCategory::$rules);

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
        $id = $this->route('building_package_category');

        try {
            $setting = BuildingPackageCategory::where('id', $id)->firstOrFail();
            Store::set('buildingPackageCategory', $setting);

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
        // multiple files
        $nbr = count($this->input('upload_files')) - 1;
        foreach(range(0, $nbr) as $index) {
            $this->validator->addRule('upload_files.' . $index, 'file');
        }
        
        return $this->rules;
    }
}
