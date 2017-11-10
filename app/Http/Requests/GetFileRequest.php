<?php

namespace App\Http\Requests;

use Store;
use Auth;
use App\Models\File;
use App\Http\Requests\Request;
use App\Validators\FileValidator;
use Exception;

class GetFileRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $this->validator = FileValidator::make();

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
        $this->validator->addRule('id', 'exists:files');
        return $this->rules;
    }
}
