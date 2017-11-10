<?php

namespace App\Http\Controllers\Api;

use Log;
use Exception;
use DB;
use Store;
use Event;
use Auth;

use App\Models\OrderReference;
use App\Validators\Validator;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\OrderReferences\IndexOrderReferenceRequest;
use App\Http\Controllers\Controller;

class OrderReferencesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param IndexOrderReferenceRequest $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\Response
     */
    public function index(IndexOrderReferenceRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new OrderReference());
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
     * Show resource
     * @param $id
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function show($id, Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new OrderReference());
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
            return response()->json(['Order Reference is not found.'], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

    }

    /**
     * Get possible values for learning about us
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function learningAboutUs(Request $request)
    {
        $learningAboutUs = collect(OrderReference::$learningAboutUs);
        return response()->json($learningAboutUs);
    }
}
