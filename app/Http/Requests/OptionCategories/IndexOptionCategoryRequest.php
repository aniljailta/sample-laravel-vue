<?php

namespace App\Http\Requests\OptionCategories;

use App\Models\OptionCategory;
use Store;

use App\Http\Requests\Request;
use App\Validators\Validator;

class IndexOptionCategoryRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $this->validator = Validator::make(Request::all());

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
        $this->validator->addRule('per_page', "numeric|min:1");
        $this->validator->addRule('role', "in:customer,dealer");
        return $this->rules;
    }
}
