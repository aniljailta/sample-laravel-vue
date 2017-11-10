<?php

namespace App\Http\Requests;

use App\Models\Option;

use App\Http\Requests\Request;
use App\Validators\OptionValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EditOptionRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $this->validator = OptionValidator::make();

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

        $optionID = $this->route('option');

        try {
            $option = Option::findOrFail($optionID);
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
        return $this->rules;
    }
}
