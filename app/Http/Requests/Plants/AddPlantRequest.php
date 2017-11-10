<?php

namespace App\Http\Requests\Plants;

use App\Models\Plant;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class AddPlantRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: here should be checking for ownership too

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = Plant::$rules;
        $rules = array_merge_recursive($rules, [
            'name' => 'required',
            'description' => 'required',
            'location_id' => [Rule::exists('locations', 'id')->where('category', 'plant')],
            'plant_id' => 'nullable'
        ]);

        return $rules;
    }
}
