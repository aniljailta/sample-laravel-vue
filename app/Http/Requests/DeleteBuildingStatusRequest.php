<?php

namespace App\Http\Requests;

use App\Models\BuildingStatus;
use App\Http\Requests\Request;

use Illuminate\Contracts\Validation\Validator;

class DeleteBuildingStatusRequest extends Request
{
    public $data;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: here should be checking for ownership too
        $buildingStatusId = $this->route('building_status');

        try {
            $buildingStatus = BuildingStatus::findOrFail($buildingStatusId);
            $this->data['requestedBuildingStatus'] = $buildingStatus;

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
        return [

        ];
    }
}
