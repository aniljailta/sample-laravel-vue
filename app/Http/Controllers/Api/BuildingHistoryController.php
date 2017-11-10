<?php

namespace App\Http\Controllers\Api;

use DB;
use Auth;
use Store;
use Event;

use App\Models\Expense;
use App\Models\BuildingHistory;
use App\Http\Requests\BuildingHistories\AddBuildingHistoryRequest;
use App\Http\Requests\BuildingHistories\UpdateBuildingHistoryRequest;
use App\Http\Requests\BuildingHistories\DeleteBuildingHistoryRequest;

use App\Events\BuildingHistoryWasAdded;
use App\Events\BuildingHistoryWasRemoved;

use App\Http\Controllers\Controller;
use App\Validators\BuildingHistoryValidation as Validator;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BuildingHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $buildingId
     * @param AddBuildingHistoryRequest $request
     * @return Response
     */
    public function store($buildingId, AddBuildingHistoryRequest $request)
    {
        try {
            DB::transaction(function() use(&$buildingHistory, $request, $buildingId) {
                $buildingHistoryData = $request->all();
                $buildingHistoryData['building_id'] = $buildingId;
                $buildingHistoryData['user_id'] = Auth::user()->id;
                $buildingHistory = BuildingHistory::create($buildingHistoryData);


                // Fire building history was added
                Event::fire(new BuildingHistoryWasAdded($buildingHistory));
            });

            return response()->json([
                'payload' => $buildingHistory,
                'msg' => 'Building status successfully changed.'
            ]);
        } catch (QueryException $e) {
            Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $buildingId
     * @param $buildingHistoryId
     * @param UpdateBuildingHistoryRequest|Request $request
     * @return Response
     * @internal param int $id
     */
    public function update($buildingId, $buildingHistoryId, UpdateBuildingHistoryRequest $request)
    {
        DB::beginTransaction();

        try {
            $buildingHistoryData = $request->all();

            // get data which has got through validator
            $buildingHistory = Store::get('requestedBuildingHistory');
            $buildingHistory->status_id = $buildingHistoryData['status_id'];
            $buildingHistory->save();

            DB::commit();
            return response()->json([
                'payload' => $buildingHistory,
                'msg' => 'Building History successfully updated.'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * @param $buildingId
     * @param $buildingHistoryId
     * @param DeleteBuildingHistoryRequest $request
     * @return mixed
     */
    public function destroy($buildingId, $buildingHistoryId, DeleteBuildingHistoryRequest $request)
    {
        DB::beginTransaction();
        try {
            // get data which has got through validator
            $requestedBuildingHistory = Store::get('requestedBuildingHistory');
            $requestedBuildingHistory->expense()->delete();
            $requestedBuildingHistory->delete();

            // Fire building history was added
            Event::fire(new BuildingHistoryWasRemoved($requestedBuildingHistory));
            DB::commit();

            return response()->json([
                'msg' => 'Building History successfully deleted.'
            ]);
        } catch (Exception $e) {
            DB::rollback();

            Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }
}
