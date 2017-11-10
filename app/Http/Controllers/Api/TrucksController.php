<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Trucks\AddTruckRequest;
use App\Http\Requests\Trucks\DestroyTruckRequest;
use App\Http\Requests\Trucks\IndexTruckRequest;
use App\Http\Requests\Trucks\ShowTruckRequest;
use App\Http\Requests\Trucks\UpdateTruckRequest;
use Log;
use Exception;
use Store;
use DB;
use Auth;

use App\Models\Truck;

use App\Validators\Validator;


use App\Services\ArrayBuilder\ArrayBuilderAssistant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TrucksController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Get all plants
     * @param IndexTruckRequest $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function index(IndexTruckRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Truck());
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
     * @param ShowTruckRequest $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function show($truck, ShowTruckRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Truck());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }

        $query = $abAssistant->apply()->getQuery();
        try {
            $item = $query->findOrFail($truck);
            return response()->json($item);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Truck is not found.'], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddTruckRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $truck = null;
            DB::transaction(function () use ($request) {
                $trucksParams = $request->all();
                $truck = Truck::create($trucksParams);
            });
            return response()->json([
                'payload' => $truck,
                'msg' => 'Truck successfully created.'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTruckRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTruckRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $truck = Store::get('truck');
                $truckParams = $request->all();
                $truck->update($truckParams);
            });

            return response()->json(['msg' => 'Truck successfully updated.']);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyTruckRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyTruckRequest $request)
    {
        try {
            // get data which has got through validator
            $truck = Store::get('truck');
            $truck->delete();
            return response()->json(['Truck successfully deleted.']);
        } catch (Exception $e) {
            return response()->json(['Something went wrong.'], 422);
        }
    }
}
