<?php

namespace App\Http\Requests\Dealers;

use Auth;
use App\Models\File;
use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class UploadFileRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();
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
        return [
            'category_id' => ['required', 'in:dealer_application,other,w9'],
            'storable_type' => ['required', 'in:dealer'],
            'storable_id' => ['required', Rule::exists('dealers', 'id')->whereNull('deleted_at')],
            'upload_files.*' => ['required', 'mimes:jpeg,bmp,png,jpg']
        ];
    }

    public function messages()
    {
        return [
            'upload_files.*' => 'Please select valid file.'
        ];
    }

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
