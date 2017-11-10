<?php

namespace App\Http\Requests\Deliveries;

use App\Models\Delivery;
use Store;
use Entrust;

use App\Http\Requests\Request;
use App\Validators\Validator;

class UpdateDeliveryRequest extends Request
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
        $this->validator = Validator::make($request)->addRules(Delivery::$rules);

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

        $deliveryID = $this->route('delivery');

        try {
            $delivery = Delivery::findOrFail($deliveryID);
            Store::set('delivery', $delivery);

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
        $this->validator->append('status_id', 'nullable');
        $this->validator->append('sale_id', 'nullable|exists:sales,id,deleted_at,NULL');
        $this->validator->append('building_id', 'nullable|exists:buildings,id,deleted_at,NULL');
        $this->validator->append('start_location_id', 'exists:locations,id,deleted_at,NULL');
        $this->validator->append('end_location_id', 'exists:locations,id,deleted_at,NULL');
        $this->validator->append('truck_id', 'nullable|exists:trucks,id,deleted_at,NULL');
        $this->validator->append('trailer_id', 'nullable|exists:trailers,id,deleted_at,NULL');
        $this->validator->append('promised_by_date', 'nullable');
        $this->validator->append('setup_duration', 'nullable');
        $this->validator->append('distance', 'nullable');
        $this->validator->append('drive_duration', 'nullable');
        $this->validator->append('cost', 'nullable');
        return $this->rules;
    }
}
