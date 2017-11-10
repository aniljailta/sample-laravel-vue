<?php

namespace App\Http\Requests\Sales;

use App\Http\Requests\Request;
use App\Models\Sale;
use App\Validators\Validator;

use Entrust;

class SendEmailRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $route = [ 
            'id' => $this->route('id') 
        ];
        
        $this->validator = Validator::make(Request::all() + $route);
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
        if (!Entrust::hasRole('administrator')) 
            return false;
        
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $model = new Sale();
        $this->validator->addRule('id', "numeric|exists:{$model->getTable()},id,deleted_at,NULL");
        return $this->rules;
    }
}
