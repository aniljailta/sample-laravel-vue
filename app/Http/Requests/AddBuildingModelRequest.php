<?php

namespace App\Http\Requests;

use App\Models\BuildingModel;

use App\Http\Requests\Request;
use App\Validators\BuildingModelValidator;

class AddBuildingModelRequest extends Request
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
        $this->validator = BuildingModelValidator::make($request)->addRules(BuildingModel::$rules);

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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->validator->addRule('name', 'required');
        $this->validator->addRule('style_id', 'required');
        $this->validator->addRule('shell_price', 'required');
        $this->validator->addRule('width', 'required');
        $this->validator->addRule('length', 'required');
        $this->validator->addRule('wall_height', 'required');
        $this->validator->append('description', 'nullable');
        $this->validator->addRule('model_status_cost', 'array|is_valid_model_status_cost');
        $this->validator->addRule('allowable_options_id', 'array|nullable|is_valid_allowable_options');
        $this->validator->addRule('allowable_colors_id', 'array|nullable|is_valid_allowable_colors');

        return $this->rules;
    }
}
