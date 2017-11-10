<?php

namespace App\Http\Controllers\Api;

use Validator;
use Event;
use DB;
use Log;
use Auth;
use Store;
use Exception;

use App\Models\Location;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\Locations\AddLocationRequest;
use App\Http\Requests\Locations\UpdateLocationRequest;
use App\Http\Requests\Locations\DeleteLocationRequest;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;
use App\Services\Locations\LocationService;

class LocationsController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Location());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }

        $result = $abAssistant
            ->apply()
            ->paginate(
                request('page'),
                request('per_page') ? request('per_page') : $this->getPerPageSetting()
            );
        return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddLocationRequest $request
     * @param LocationService $locationService
     * @return \Illuminate\Http\Response
     */
    public function store(AddLocationRequest $request, LocationService $locationService)
    {
        try
        {
            $locationParams = $request->all();
            $location = $locationService->create($locationParams);
            return response()->json([
                'payload' => $location,
                'msg' => 'Location successfully created.'
            ]);
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
        $abAssistant->setModel(new Location());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) return response()->json($abAssistant->getMessages(), 400);

        $query = $abAssistant->apply()->getQuery();

        try {
            $item = $query->findOrFail($id);
            return response()->json($item);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['Location is not found.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @param UpdateLocationRequest $request
     * @param LocationService $locationService
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateLocationRequest $request, LocationService $locationService)
    {
        try
        {
            DB::transaction(function() use($request, $id, $locationService) {
                $location = Store::get('requestedLocation');
                $locationParams = $request->all();

                $locationService->update($location, $locationParams);
            });

            return response()->json(['msg' => 'Location successfully updated.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteLocationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteLocationRequest $request)
    {
        try
        {
            // get data which has got through validator
            $location = Store::get('location');
            $location->delete();
            return response()->json(['Location successfully deleted.']);
        } catch (Exception $e)
        {
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories(Request $request) {
        $categories = Location::$categories;

        return response()->json($categories);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function activeFlags(Request $request) {
        $isActiveFlags = Location::$isActive;

        return response()->json($isActiveFlags);
    }

    /**
     * @param Request $request
     * @param LocationService $locationService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFiles(Request $request , LocationService $locationService) {
        $files =  $locationService->getFiles($request->location_id);
        return response()->json($files);
    }
    
}
