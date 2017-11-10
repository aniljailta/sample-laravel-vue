<?php

namespace App\Http\Requests\Trucks;

use App\Models\Truck;
use Illuminate\Foundation\Http\FormRequest;
use Store;
use Exception;
use Entrust;

class DestroyTruckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!Entrust::hasRole('administrator')) return false;

        $id = $this->route('truck');

        try {
            $item = Truck::findOrFail($id);
            Store::set('truck', $item);

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
        return [
            //
        ];
    }
}
