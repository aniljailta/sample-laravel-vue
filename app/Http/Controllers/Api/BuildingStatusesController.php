<?php

namespace App\Http\Controllers\Api;

use DB;
use Event;
use Store;
use Validator;

use App\Models\Building;
use App\Http\Requests;
use App\Models\BuildingStatus;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\IndexBuildingStatusRequest;
use App\Http\Requests\AddBuildingStatusRequest;
use App\Http\Requests\DeleteBuildingStatusRequest;
use App\Http\Requests\UpdateBuildingStatusRequest;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;
use App\Repositories\BuildingRepository; 

class BuildingStatusesController extends Controller
{
    
    protected $building;

    public function __construct(BuildingRepository $building)
    {
        $this->building =  $building;
    }

    /**
     * Get all options
     * @param IndexBuildingStatusRequest $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function index(IndexBuildingStatusRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new BuildingStatus());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) return response()->json($abAssistant->getMessages(), 400);

        $result = $abAssistant
            ->apply()
            ->paginate(
                request('page'),
                request('per_page') ? request('per_page') : $this->getPerPageSetting()
            );
        usort($result['data'], function($a, $b)
        {
            return $a['priority'] <=> $b['priority'];
        });
        return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddBuildingStatusRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddBuildingStatusRequest $request)
    {
        try
        {
            $buildingStatusParams = $request->all();
            $buildingStatusParams['type'] = 'build';
            $lastBuildingStatus = BuildingStatus::orderBy('priority', 'desc')->first();

            if($lastBuildingStatus) {
                $buildingStatusParams['priority'] = $lastBuildingStatus->priority;
                $lastBuildingStatus->update(['priority' => $lastBuildingStatus->priority + 1]);
            }

            $buildingStatus = BuildingStatus::create($buildingStatusParams);

            return response()->json(['Building Status successfully created.']);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request, ArrayBuilderAssistant $abAssistant) {
        $abAssistant->setModel(new BuildingStatus());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) return response()->json($abAssistant->getMessages(), 400);

        $query = $abAssistant->apply()->getQuery();

        try {
            $item = $query->findOrFail($id);
            return response()->json($item);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['Building Status is not found.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @param UpdateBuildingStatusRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateBuildingStatusRequest $request)
    {
        try
        {
            DB::transaction(function() use($request, $id) {
                $buildingStatus = $request->data['requestedBuildingStatus'];
                if ($buildingStatus->is_system) return;

                $buildingStatusParams = $request->all();
                $buildingStatus->update($buildingStatusParams);
            });

            return response()->json(['msg' => 'Building Status successfully updated.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteBuildingStatusRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteBuildingStatusRequest $request)
    {
        try
        {
            // get data which has got through validator
            $buildingStatus = $request->data['requestedBuildingStatus'];
            if ($buildingStatus->is_system) return;

            $buildingStatuses = BuildingStatus::where('priority', '>', $buildingStatus->priority)->get();
            $buildingStatus->delete();
            foreach($buildingStatuses as $buildingStatus) {
                $buildingStatus->update(['priority' => $buildingStatus->priority - 1]);
            }
            return response()->json(['Building Status successfully deleted.']);
        } catch (Exception $e)
        {
            return response()->json(['Something went wrong.'], 422);
        }
    }
    
    /**
     * Get building statuses by type
     * @param Request $request
     * @return array
     */
    public function getByType(Request $request)
    {
        $statusType = $request->route('type');
        $validator = Validator::make(['type' => $statusType], ['type' => 'required|in:build,sale']);

        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }

        try
        {
            $buildingStatuses = BuildingStatus::active()
                ->select('id', 'type', 'name', 'priority')
                ->where('type', $statusType)
                ->orderBy('priority', 'asc')
                ->get();

            $buildingStatuses->keyBy('id');

            $response = $buildingStatuses;
        } catch (ModelNotFoundException $e)
        {
            $response = ['status' => 'error', 'message' => 'No building status matches the provided type'];
        }

        return response()->json($response);
    }

    /**
     * Get building statuses by type
     * @param Request $request
     * @return array
     */
    public function getToPrioritize(Request $request)
    {
        $buildingId = $request->route('building_id');
        $validator = Validator::make([
            'building' => $buildingId,
        ], [
            'building' => 'required|numeric|exists:buildings,id,deleted_at,NULL',
        ]);

        if ($validator->fails()) return response()->json($validator->errors()->all());

        $buildingStatusesSelect =  $this->building->getToPrioritize($buildingId);
        
        return response()->json($buildingStatusesSelect);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function activeFlags(Request $request) {
        $isActiveFlags = BuildingStatus::$isActive;

        return response()->json($isActiveFlags);
    }

    /**
     * Update building statuses priorities
     * @param Request $request
     * @return array
     */
    public function updatePriorities(Request $request)
    {
        $data = $request->all();
        $arr = BuildingStatus::where('is_system', 1)->pluck('priority')->toArray();

        if(in_array($data['old_priority'], $arr) || in_array($data['new_priority'], $arr)) {
            return response()->json('You can`t change priority of that status!');
        }
        $priorities = [];
        if($data['old_priority'] > $data['new_priority']) {
            for ($i = $data['new_priority']; $i <= $data['old_priority']; $i++) {
                array_push($priorities, $i);
            }
        } else {
            for ($i = $data['old_priority']; $i <= $data['new_priority']; $i++) {
                array_push($priorities, $i);
            }
        }

        $buildingStatuses = BuildingStatus::whereIn('priority', $priorities)->get();
        foreach ($buildingStatuses as $buildingStatus) {
            if ($buildingStatus->priority == $data['old_priority']) {
                $buildingStatus->update(['priority' => $data['new_priority']]);
            } else {
                $data['old_priority'] > $data['new_priority'] ? $buildingStatus->update(['priority' => $buildingStatus->priority + 1]) : $buildingStatus->update(['priority' => $buildingStatus->priority - 1]);
            }
        }
        return response()->json('Building Status priority successfully updated.');
    }
}
