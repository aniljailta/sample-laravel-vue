<?php

namespace App\Http\Requests;

use Store;
use App\Models\Setting;

use App\Http\Requests\Request;
use App\Validators\SettingValidator;

class UpdateSettingRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $this->validator = SettingValidator::make();

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

        $id = $this->route('setting');

        try {
            $setting = Setting::where('id', $id)->firstOrFail();
            Store::set('setting', $setting);

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
        $this->validator->addRule('id', 'required|string|max:255');
        $this->validator->addRule('title', 'string|max:255');
        $this->validator->addRule('description', 'string');
        return $this->rules;
    }
}
