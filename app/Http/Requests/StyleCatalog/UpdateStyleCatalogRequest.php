<?php

namespace App\Http\Requests\StyleCatalog;

use Store;

use App\Models\StyleCatalog;
use App\Http\Requests\Request;
use App\Validators\StyleValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateStyleCatalogRequest extends Request
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
        $this->validator = StyleValidator::make($request)->addRules(StyleCatalog::$rules);

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
        $id = $this->route('style_catalog');

        try {
            $style = StyleCatalog::where('id', $id)->firstOrFail();
            Store::set('style', $style);

            return true;
        } catch (Exception $e) {
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
        $this->validator->append('name', 'required');
        $this->validator->append('description', 'nullable');
        
        return $this->rules;
    }
}
