<?php

namespace App\Http\Requests\Dealers;

use Store;
use App\Models\Dealer;
use App\Http\Requests\Request;
use App\Validators\DealerValidator;

class AddDealerRequest extends Request
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
        $this->validator = DealerValidator::make($request)->addRules(Dealer::$rules);

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
        $this->validator->append('business_name', 'required');
        $this->validator->append('phone', 'required');
        $this->validator->append('email', 'required');
        $this->validator->append('tax_rate', 'required');
        $this->validator->append('commission_rate', 'required');
        $this->validator->append('cash_sale_deposit_rate', 'required');
        $this->validator->append('location_id', 'exists:locations,id,deleted_at,NULL');
        return $this->rules;
    }
}
