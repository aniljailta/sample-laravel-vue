<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\Trailers\AddTrailerRequest;
use App\Http\Requests\Trailers\DestroyTrailerRequest;
use App\Http\Requests\Trailers\IndexTrailerRequest;
use App\Http\Requests\Trailers\ShowTrailerRequest;
use App\Http\Requests\Trailers\UpdateTrailerRequest;
use Log;
use Exception;
use Store;
use DB;
use Auth;

use App\Models\Trailer;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;

use App\Http\Controllers\Controller;

class TrailersController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Get all Trailers
     *
     * @param IndexTrailerRequest $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function index(IndexTrailerRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Trailer());
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
     * @param ShowTrailerRequest $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function show($id, ShowTrailerRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Trailer());
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
            return response()->json(['msg' => 'Trailer is not found.'], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddTrailerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddTrailerRequest $request)
    {
        try {
            $trailer = null;
            DB::transaction(function () use ($request) {
                $trailersParams = $request->all();
                $trailer = Trailer::create($trailersParams);
            });
            return response()->json([
                'payload' => $trailer,
                'msg' => 'Trailer successfully created.'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTrailerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTrailerRequest $request,$trailer)
    {
        try {
            DB::transaction(function () use ($request) {
                $trailer = Store::get('trailer');
                $trailerParams = $request->all();
                $trailer->update($trailerParams);
            });

            return response()->json(['msg' => 'Trailer successfully updated.']);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyTrailerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyTrailerRequest $request)
    {
        try {
            // get data which has got through validator
            $trailer = Store::get('trailer');
            $trailer->delete();
            return response()->json(['Trailer successfully deleted.']);
        } catch (Exception $e) {
            return response()->json(['Something went wrong.'], 422);
        }
    }
}
