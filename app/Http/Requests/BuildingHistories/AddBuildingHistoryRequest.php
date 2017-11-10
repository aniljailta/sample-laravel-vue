<?php

namespace App\Http\Requests\BuildingHistories;

use Store;
use Entrust;

use App\Models\Building;
use App\Models\BuildingHistory;

use App\Http\Requests\Request;
use App\Validators\BuildingHistoryValidator as Validator;

class AddBuildingHistoryRequest extends Request
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
        $this->validator = Validator::make($request)->addRules(BuildingHistory::$rules);

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
        if (!Entrust::hasRole('administrator')) return false;
        
        $itemId = $this->route('building');
        try {
            $item = Building::findOrFail($itemId);
            Store::set('building', $item);
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
        $this->validator->append('status_id', 'required|is_valid_building_status_priority|exists:building_statuses,id,is_active,yes');
        return $this->rules;
    }
}
