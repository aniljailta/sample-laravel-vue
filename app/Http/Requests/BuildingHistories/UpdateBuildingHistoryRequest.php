<?php

namespace App\Http\Requests\BuildingHistories;

use Store;
use Entrust;

use App\Models\Building;
use App\Models\BuildingHistory;

use App\Http\Requests\Request;
use App\Validators\BuildingHistoryValidator as Validator;

class UpdateBuildingHistoryRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $routed = [
            'building_id' => $this->route('building'),
            'building_history_id' => $this->route('history')
        ];

        $request = $this->all();
        array_walk_recursive($request, function(&$item, $key) {
            if ($item === 'null') $item = null;
        });
        Request::merge($request);

        $this->validator = Validator::make($request + $routed)->addRules(BuildingHistory::$rules);

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

        $buildingId = $this->route('building');
        $buildingHistoryId = $this->route('history');

        try {
            $building = Building::with(['building_history'])->findOrFail($buildingId);
            $requestedBuildingHistory = BuildingHistory::findOrFail($buildingHistoryId);

            Store::set('building', $building);
            Store::set('requestedBuildingHistory', $requestedBuildingHistory);

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
        $this->validator->addRule('building_history_id', 'required|numeric|is_not_first_building_history|is_last_building_history|is_not_billed_building_history');
        $this->validator->append('status_id', 'is_valid_building_status_priority|exists:building_statuses,id,is_active,yes');
        return $this->rules;
    }
}
