<?php

namespace App\Http\Requests\Dealers;

use Store;
use App\Models\Dealer;

use App\Http\Requests\Request;
use App\Validators\DealerValidator;

class DeleteDealerRequest extends Request
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
        // TODO: here should be checking for ownership too

        $id = $this->route('dealer');

        try {
            $model = Dealer::findOrFail($id);
            Store::set('dealer', $model);

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
