<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use Log;
use Exception;
use Store;
use DB;
use Auth;

use App\Models\Plant;

use App\Validators\Validator;
use App\Http\Requests;
use App\Http\Requests\Plants\IndexPlantRequest;
use App\Http\Requests\Plants\AddPlantRequest;
use App\Http\Requests\Plants\UpdatePlantRequest;
use App\Http\Requests\Plants\DeletePlantRequest;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlantsController extends Controller
{
    private $keyId = 'id';

    /**
     * @var Company
     */
    private $company;

    public function __construct()
    {
        $this->company = Store::get('company');
        if ($this->company) {
            $this->keyId = Plant::getScopedKeyName($this->company);
        }
    }

    /**
     * Get all plants
     * @param IndexPlantRequest $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function index(IndexPlantRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $plant = new Plant;
        $abAssistant->setModel($plant);
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
        return response()->json($result, 200, [], JSON_PRETTY_PRINT);
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
        $plant = new Plant;
        $abAssistant->setModel($plant);
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
            return response()->json(['Plant is not found.'], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddPlantRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddPlantRequest $request)
    {
        try
        {
            $plant = null;
            DB::transaction(function() use($request, &$plant) {
                $plantParams = $request->all();
                $plant = Plant::create($plantParams);
            });
            return response()->json([
                'payload' => $plant,
                'msg' => 'Plant successfully created.'
            ]);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePlantRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePlantRequest $request)
    {
        try
        {
            DB::transaction(function() use($request) {
                $plant = Store::get('plant');
                $plantParams = $request->all();
                $plant->update($plantParams);
            });

            return response()->json(['Plant successfully updated.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeletePlantRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeletePlantRequest $request)
    {
        try
        {
            // get data which has got through validator
            $plant = Store::get('plant');
            $plant->delete();
            return response()->json(['Plant successfully deleted.']);
        } catch (Exception $e)
        {
            return response()->json(['Something went wrong.'], 422);
        }
    }
}
