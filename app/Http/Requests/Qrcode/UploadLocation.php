<?php

namespace App\Http\Requests\Qrcode;

use App\Models\LocationFiles;
use App\Http\Requests\Request;
use App\Validators\LocationValidator as Validator;

/**
 * Class UploadFile.
 */
class UploadLocation extends Request
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
        $this->validator = Validator::make($request)->addRules(LocationFiles::$rules);

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
        $this->validator->append('location_id', 'required|integer');
        $this->validator->append('building_id', 'required|integer');
        $this->validator->append('latitude', 'integer|string|is_valid_latitude');
        $this->validator->append('longitude', 'integer|string|is_valid_longitude');
        return $this->rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [ ];
    }
}