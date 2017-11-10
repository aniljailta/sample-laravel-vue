<?php

namespace App\Http\Requests\OptionCatalog;

use Store;

use App\Models\OptionCatalog;
use App\Http\Requests\Request;
use App\Validators\OptionValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateOptionCatalogRequest extends Request
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
        $this->validator = OptionValidator::make($request)->addRules(OptionCatalog::$rules);

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
        $id = $this->route('option_catalog');

        try {
            $option = OptionCatalog::where('id', $id)->firstOrFail();
            Store::set('optionCatalog', $option);

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
        $this->validator->append('category_id', 'exists:option_categories,id');

        // multiple files
        if (!empty($this->input('force_quantity')) || !empty($this->input('constraint_type'))) {
            $this->validator->addRule('force_quantity', 'required');
            $this->validator->addRule('constraint_type', 'required');
        }
        $nbr = count($this->input('upload_files')) - 1;
        foreach(range(0, $nbr) as $index) {
            $this->validator->addRule('upload_files.' . $index, 'file');
        }

        return $this->rules;
    }
}
