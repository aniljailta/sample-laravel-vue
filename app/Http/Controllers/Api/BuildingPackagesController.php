<?php

namespace App\Http\Controllers\Api;

use Log;
use Exception;
use DB;
use Store;
use Event;
use Auth;

use App\Models\Building;
use App\Models\BuildingPackage;
use App\Models\BuildingModel;
use App\Http\Requests\IndexBuildingPackageRequest;
use App\Http\Requests\AddBuildingPackageRequest;
use App\Http\Requests\EditBuildingPackageRequest;
use App\Http\Requests\UpdateBuildingPackageRequest;
use App\Http\Requests\DeleteBuildingPackageRequest;
use App\Validators\Validator;
use App\Services\Files\FileService;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;
use App\Events\BuildingPackageWasUpdated;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BuildingPackagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param IndexBuildingPackageRequest $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\Response
     */
    public function index(IndexBuildingPackageRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new BuildingPackage());
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
        $abAssistant->setModel(new BuildingPackage());
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
            return response()->json(['Building Package is not found.'], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddBuildingPackageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddBuildingPackageRequest $request)
    {
        try
        {
            DB::transaction(function() use($request) {
                $buildingPackageParams = $request->all();

                $buildingPackage = BuildingPackage::create($buildingPackageParams);
                if ($buildingPackage->id) {
                    if (array_key_exists('package_options', $buildingPackageParams)) {
                        foreach ((array) $buildingPackageParams['package_options'] as $option) {
                            $buildingPackage->options()->create($option);
                        }
                    }

                    // add files
                    if ($request->file('upload_files')) {
                        $fileService = new FileService();
                        $fileService->store($request->file('upload_files'), [
                            'key' => $buildingPackage->id,
                            'user_id' => Auth::check() ? Auth::user()->id : null,
                            'type' => 'building-package',
                            'id' => $buildingPackage->id
                        ]);
                    }
                }

                Event::fire(new BuildingPackageWasUpdated($buildingPackage));
            });
            return response()->json(['Building Package successfully created.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBuildingPackageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBuildingPackageRequest $request)
    {
        try
        {
            DB::transaction(function() use($request) {
                $buildingPackage = Store::get('buildingPackage');
                $buildingPackageParams = $request->all();

                $buildingPackage->update($buildingPackageParams);
                if ($buildingPackage->id) {
                    if (array_key_exists('package_options', $buildingPackageParams)) {
                        $buildingPackage->options()->delete();
                        foreach ((array) $buildingPackageParams['package_options'] as $option) {
                            $buildingPackage->options()->create($option);
                        }
                    }

                    // add files
                    if ($request->file('upload_files')) {
                        $fileService = new FileService();
                        $fileService->store($request->file('upload_files'), [
                            'key' => $buildingPackage->id,
                            'user_id' => Auth::check() ? Auth::user()->id : null,
                            'type' => 'building-package',
                            'id' => $buildingPackage->id
                        ]);
                    }
                }

                Event::fire(new BuildingPackageWasUpdated($buildingPackage));
            });

            return response()->json(['Building Package successfully updated.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteBuildingPackageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteBuildingPackageRequest $request)
    {
        try
        {
            // get data which has got through validator
            $buildingPackage = Store::get('buildingPackage');
            $buildingPackage->delete();
            return response()->json(['Building Package successfully deleted.']);
        } catch (Exception $e)
        {
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function activeFlags(Request $request) {
        $isActiveFlags = BuildingPackage::$isActive;

        return response()->json($isActiveFlags);
    }
}
