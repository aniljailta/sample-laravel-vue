<?php

namespace App\Http\Requests\Trucks;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Request;
use App\Validators\Validator;
use App\Models\Truck;
use Store;
class UpdateTruckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $id = $this->route('truck');

        try {
            $truck = Truck::where('id', $id)->firstOrFail();
            Store::set('truck', $truck);

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
        $rules=Truck::$rules;
        $rules['name'][]='required';
        $rules['delivery_capacity'][]='required';
        return $rules;
    }
}
