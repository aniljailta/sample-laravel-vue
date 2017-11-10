<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Sale;

//use Illuminate\Support\Facades\Validator;
use App\Validators\Validator;
use Illuminate\Validation\Rule;

class SaveSaleRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        /* $this->validator = Validator::make(Request::all(), [
            'id' => [
                'uuid', Rule::exists('orders')->where(function($query) {
                    $query->whereIn('status_id', ['draft', 'review_needed']);
                })
            ]
        ]);*/
        $this->validator = Validator::make(Request::all(), Sale::$rules);

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
        $this->validator->addRule('id', "required|numeric|exists:sales,id,deleted_at,NULL");
        $this->validator->addRule('note_admin', "nullable|string|max:255");
        return $this->rules;
    }
}
