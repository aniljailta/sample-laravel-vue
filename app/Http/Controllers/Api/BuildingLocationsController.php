<?php

namespace App\Http\Controllers\Api;

use DB;
use Auth;
use Event;
use Store;

use App\Models\BuildingLocation;
use App\Events\BuildingLocationWasAdded;
use App\Events\BuildingLocationWasRemoved;

use App\Http\Requests\BuildingLocations\AddBuildingLocationRequest;
use App\Http\Requests\BuildingLocations\UpdateBuildingLocationRequest;
use App\Http\Requests\BuildingLocations\DeleteBuildingLocationRequest;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BuildingLocationsController extends Controller
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
     * @param AddBuildingLocationRequest $request
     * @return Response
     */
    public function store($buildingId, AddBuildingLocationRequest $request)
    {
        try
        {
            DB::transaction(function() use(&$buildingLocation, $request, $buildingId) {

                $buildingLocationData = $request->all();
                $buildingLocationData['building_id'] = $buildingId;
                $buildingLocationData['user_id'] = Auth::user()->id;
                $buildingLocation = BuildingLocation::create($buildingLocationData);

                // Fire building location was added
                Event::fire(new BuildingLocationWasAdded($buildingLocation));
            });

            return response()->json([
                'payload' => $buildingLocation,
                'msg' => 'Building location successfully changed.'
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
     * @param $buildingLocationId
     * @param UpdateBuildingLocationRequest|Request $request
     * @return Response
     * @internal param int $id
     */
    public function update($buildingId, $buildingLocationId, UpdateBuildingLocationRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $buildingLocationData = $request->all();

            // get data which has got through validator
            $buildingLocation = Store::get('requestedBuildingLocation');
            $buildingLocation->location_id = $buildingLocationData['location_id'];
            $buildingLocation->save();
            DB::commit();

            return response()->json(['payload' => $buildingLocation, 'msg' => 'Building Location successfully updated.']);
        } catch (QueryException $e) {
            DB::rollback();
            Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * @param $buildingId
     * @param $buildingLocationId
     * @param DeleteBuildingLocationRequest $request
     * @return mixed
     */
    public function destroy($buildingId, $buildingLocationId, DeleteBuildingLocationRequest $request)
    {
        DB::beginTransaction();
        try
        {
            // get data which has got through validator
            $building = Store::get('building');

            $currentBuildingLocation = $building->last_location;
            $currentBuildingLocation->delete();
            // Fire building history was added
            Event::fire(new BuildingLocationWasRemoved($currentBuildingLocation));
            DB::commit();

            return response()->json(['msg' => 'Building Location successfully deleted.']);
        } catch (QueryException $e) {
            DB::rollback();
            Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }
}
