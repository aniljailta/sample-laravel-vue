<?php

namespace App\Http\Requests\Users;

use App\Models\User;
use Store;
use Entrust;

use App\Http\Requests\Request;
use App\Validators\Validator;

class IndexUserRequest extends Request
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
        if (!Entrust::hasRole('administrator')) return false;
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
        return $this->rules;
    }
}
