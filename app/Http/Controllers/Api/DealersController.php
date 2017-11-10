<?php

namespace App\Http\Controllers\Api;

use DB;
use Log;
use Store;
use Auth;
use App\Models\Dealer;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Files\FileService;
use App\Http\Requests\Dealers\AddDealerRequest;
use App\Http\Requests\Dealers\UpdateDealerRequest;
use App\Http\Requests\Dealers\DeleteDealerRequest;
use App\Http\Requests\Dealers\UploadFileRequest;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;

class DealersController extends Controller
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
        $abAssistant->setModel(new Dealer());
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
        $abAssistant->setModel(new Dealer());
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
            return response()->json(['Dealer is not found.'], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddDealerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddDealerRequest $request)
    {
        try
        {
            DB::transaction(function() use($request) {
                $attributes = $request->all();

                $model = Dealer::create($attributes);
                $this->saveFiles($model, $attributes);
            });
            return response()->json(['Dealer successfully created.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDealerRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDealerRequest $request, $id)
    {
        try
        {
            DB::transaction(function() use($request) {
                $attributes = $request->all();
                
                $model = Store::get('dealer');
                $model->update($attributes);
                $this->saveFiles($model, $attributes);
            });
            return response()->json(['Dealer successfully updated.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Save files in storage.
     *
     * @param $model
     * @param array $attributes
     * @return \Illuminate\Http\Response
     * @internal param UpdateDealerRequest $request
     * @internal param int $id
     */
    public function saveFiles($model, $attributes = []) {
        // Add files
        if (isset($attributes['dealer_app_files'])) {
            $fileService = new FileService();
            $fileService->store($attributes['dealer_app_files'], [
                'key' => null,
                'user_id' => Auth::check() ? Auth::user()->id : null,
                'type' => 'dealer',
                'id' => $model->id,
                'category_id' => 'dealer_application'
            ]);
        }

        if (isset($attributes['other_files'])) {
            $fileService = new FileService();
            $fileService->store($attributes['other_files'], [
                'key' => null,
                'user_id' => Auth::check() ? Auth::user()->id : null,
                'type' => 'dealer',
                'id' => $model->id,
                'category_id' => 'other'
            ]);
        }

        if (isset($attributes['w_9_files'])) {
            $fileService = new FileService();
            $fileService->store($attributes['w_9_files'], [
                'key' => null,
                'user_id' => Auth::check() ? Auth::user()->id : null,
                'type' => 'dealer',
                'id' => $model->id,
                'category_id' => 'w9'
            ]);
        }
        return $this;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteDealerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteDealerRequest $request)
    {
        try
        {
            // get data which has got through validator
            $model = Store::get('dealer');
            $model->delete();
            return response()->json(['Dealer successfully deleted.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }
    
    /**
     * Display a listing of the resource.
     * TODO: now it used only in /buildings/show (dealer-inventory modal)
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function perId(Request $request)
    {
        $dealers = Dealer::with(['location'])->get()->keyBy('id');

        return response()->json($dealers);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function activeFlags(Request $request) {
        $isActiveFlags = Dealer::$isActive;

        return response()->json($isActiveFlags);
    }
}
