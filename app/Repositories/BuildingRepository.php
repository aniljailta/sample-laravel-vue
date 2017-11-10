<?php

namespace App\Repositories;

use DB;
use App\Models\Building;
use App\Models\BuildingStatus;
use App\Models\BuildingHistory;

use Illuminate\Config;
use Entrust;

class BuildingRepository
{
    protected $model;

    public function __construct(Building $model)
    {
        $this->model = $model;
    }

    protected function aggregateAttributes(Building $buildingEntity, $inputs)
    {
        if (Entrust::hasRole('administrator')) {
        }

        if (array_key_exists('order_type', $inputs)) $buildingEntity->order_type = $inputs['order_type'];
        if (array_key_exists('plant_id', $inputs)) $buildingEntity->plant_id = $inputs['plant_id'];
        if (array_key_exists('last_location_id', $inputs)) $buildingEntity->last_location_id = $inputs['last_location_id'];
        if (array_key_exists('building_model_id', $inputs)) $buildingEntity->building_model_id = $inputs['building_model_id'];
        if (array_key_exists('serial_number', $inputs)) $buildingEntity->serial_number = $inputs['serial_number'];
        if (array_key_exists('width', $inputs)) $buildingEntity->width = $inputs['width'];
        if (array_key_exists('height', $inputs)) $buildingEntity->height = $inputs['height'];
        if (array_key_exists('length', $inputs)) $buildingEntity->length = $inputs['length'];
        if (array_key_exists('shell_price', $inputs)) $buildingEntity->shell_price = $inputs['shell_price'];
        if (array_key_exists('total_options', $inputs)) $buildingEntity->total_options = $inputs['total_options'];
        if (array_key_exists('total_price', $inputs)) $buildingEntity->total_price = $inputs['total_price'];
        if (array_key_exists('notes', $inputs)) $buildingEntity->notes = $inputs['notes'];

        return $buildingEntity;
    }

    public function save(Building $entry, $inputs)
    {
        $entry = $this->aggregateAttributes($entry, $inputs);
        $entry->save();
        return $entry;
    }

    /**
     * @param integer input
     * @return mixed
     */
    public function getToPrioritize(int $buildingId)
    {
        $relation = "building_history";
        $building = $this->model->with(['building_model', $relation, $relation . '.building_status',])->findOrFail($buildingId);

        $lastStatus = $building->{$relation}->last();
        $lastPriority = (is_null($lastStatus)) ? 0 : $lastStatus->building_status->priority;

        // Get all building statuses
        $allBuildingStatuses = BuildingStatus::active()
            ->select('id', 'type', 'name', 'priority')
            ->orderBy('priority', 'asc')
            ->get();

        // Get all building statuses which should be disabled
        $buildingStatusesDisabled = BuildingStatus::getDisabledBuildingStatusByPriority($lastPriority, $allBuildingStatuses)
            ->pluck('id')
            ->toArray();

        $allBuildingStatuses->each(function ($status) use (&$buildingStatusesSelect, $buildingStatusesDisabled) {
            $status_id = $status->id;
            $buildingStatusSelectItem = [
                'value' => $status->id,
                'display' => $status->name,
                'priority' => $status->priority,
            ];

            if (in_array($status_id, $buildingStatusesDisabled))
                $buildingStatusSelectItem['options']['disabled'] = true;

            $buildingStatusesSelect[] = $buildingStatusSelectItem;
        });

        return $buildingStatusesSelect;
    }

    /**
     * @param array input
     */
    public function saveStatus($data)
    {
        DB::transaction(function () use ($data) {
            $status = BuildingHistory::create([
                'user_id' => \Auth::user()->id,
                'status_id' => $data['status_id'],
                'building_id' => $data['building_id'],
            ]);
            $model = $this->model->find($status->building_id);
            $model->status_id = $status->id;
            if ($model->save()) {
                return true;
            }
            throw new GeneralException('Something went wrong');
        });
    }
}