<?php

namespace App\Http\Controllers\Api;

use App\Events\BuildingLocationWasAdded;

use App\Models\Building;
use App\Models\BuildingLocation;
use App\Models\Location;
use App\Models\Delivery;

use App\Services\Deliveries\DeliveryService;
use Validator;
use Event;
use DB;
use Log;
use Auth;
use Store;
use Exception;
use Carbon\Carbon;

use App\Http\Requests\Deliveries\AddDeliveryRequest;
use App\Http\Requests\Deliveries\UpdateDeliveryRequest;
use App\Http\Requests\Deliveries\DeleteDeliveryRequest;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;

class DeliveriesController extends Controller
{

    public function __construct()
    {
    }

    /**
     * Display a listing of the model statuses.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function statuses(Request $request)
    {
        $deliveryStatuses = Delivery::$statuses;

        return response()->json($deliveryStatuses);
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
        $abAssistant->setModel(new Delivery());
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
     * Get resource
     * @param $id
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function show($id, Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Delivery());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }

        $query = $abAssistant->apply()->getQuery();
        try {
            $item = $query->findOrFail($id);
            return response()->json($item);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['Delivery is not found.'], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddBillRequest|AddDeliveryRequest $request
     * @param DeliveryService $deliveryService
     * @return \Illuminate\Http\Response
     */
    public function store(AddDeliveryRequest $request,DeliveryService $deliveryService)
    {
        try {
            $deliveryParams = $request->all();
            $deliveryParams['dispatcher_id'] = Auth::user()->id;
            $delivery = $deliveryService->create($deliveryParams);
            return response()->json(['Delivery successfully created.']);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDeliveryRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDeliveryRequest $request, $id, DeliveryService $deliveryService)
    {
        // get data which has got through validator
        $delivery = Store::get('delivery');
        try {
            $deliveryParams = $request->all();

            $deliveryParams['dispatcher_id'] = Auth::user()->id;
            $deliveryService->update($delivery,$deliveryParams);
            return response()->json(['Delivery successfully updated.']);
        } catch (QueryException $e) {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteDeliveryRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteDeliveryRequest $request, $id)
    {
        try {
            // get data which has got through validator
            $delivery = Store::get('delivery');
            $delivery->delete();
            return response()->json(['Delivery successfully deleted.']);
        } catch (Exception $e) {
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Display a listing of the model Categories.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function categories(Request $request)
    {
        $deliveryCategories = Delivery::$categories;

        return response()->json($deliveryCategories);
    }

    /**
     * Display a listing of the model Priorities.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function priorities(Request $request)
    {
        $deliveryPriorities = Delivery::$priorities;

        return response()->json($deliveryPriorities);
    }
}
