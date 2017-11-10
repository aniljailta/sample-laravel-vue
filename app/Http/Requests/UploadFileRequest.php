<?php

namespace App\Http\Requests;

use Auth;
use App\Models\File;
use App\Http\Requests\Request;
use App\Validators\FileValidator;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class UploadFileRequest extends Request
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
        $user = Auth::user();
        if ($this->input('storable_type') === 'order') return true;
        if (!$user || !$user->hasRole('administrator')) return false;

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->validator->addRule('storable_type', 'required|string|in:option,building,order,building-package,building-package-category');
        $this->validator->addRule('storable_id', 'required|is_valid_storable_id');
        $this->validator->addRule('category_id', 'required_if:storable_type,order|string|is_valid_category_id');

        if (is_array($this->file('upload_files')) ) {
            // multiple files
            $nbr = count($this->input('upload_files')) - 1;
            foreach(range(0, $nbr) as $index) {
                $this->validator->addRule['upload_files.' . $index] = 'file';
            }
        } else {
            $this->validator->addRule('upload_files', 'required|file');
        }
        
        return $this->rules;
    }

    // Bootstrap file input error format so ugly = (
    // It is just understand one simple string
    /*protected function formatErrors(Validator $validator)
    {
        $validator->getMessageBag()->add('error', true);
        return $validator->getMessageBag()->toArray();
    }*/

    /**
     * Format the errors from the given Validator instance.
     *
     * @param Validator|\Illuminate\Contracts\Validation\Validator $validator
     * @return array
     */

    protected function formatErrors(Validator $validator)
    {
        $arrayMessages = array_flatten($validator->getMessageBag()->getMessages());
        $stringMessage = '';
        array_walk($arrayMessages, function($val, $key) use(&$stringMessage) {
            $stringMessage .= "{$val} ";
        });
        return [$stringMessage];
    }

    public function response(array $errors)
    {
        if ($this->expectsJson()) {
            return new JsonResponse(array_first($errors), 422);
        }

        return $this->redirector->to($this->getRedirectUrl())
            ->withInput($this->except($this->dontFlash))
            ->withErrors($errors, $this->errorBag);
    }
}
