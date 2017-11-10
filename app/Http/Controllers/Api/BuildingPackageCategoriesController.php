<?php

namespace App\Http\Controllers\Api;

use DB;
use Store;
use Event;
use Auth;

use App\Models\BuildingPackageCategory;
use App\Http\Requests\IndexBuildingPackageCategoryRequest;
use App\Http\Requests\AddBuildingPackageCategoryRequest;
use App\Http\Requests\EditBuildingPackageCategoryRequest;
use App\Http\Requests\UpdateBuildingPackageCategoryRequest;
use App\Http\Requests\DeleteBuildingPackageCategoryRequest;

use App\Services\Files\FileService;
use App\Services\ArrayBuilder\ArrayBuilderAssistant;
use App\Validators\Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BuildingPackageCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param IndexBuildingPackageCategoryRequest $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\Response
     */
    public function index(IndexBuildingPackageCategoryRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new BuildingPackageCategory());
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
        $abAssistant->setModel(new BuildingPackageCategory());
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
            return response()->json(['Building Package Category is not found.'], 422);
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param AddBuildingPackageCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddBuildingPackageCategoryRequest $request)
    {
        try
        {
            $buildingPackageCategory = new BuildingPackageCategory();
            DB::transaction(function() use($request, &$buildingPackageCategory) {
                $buildingPackageCategoryParams = $request->all();

                $buildingPackageCategory = BuildingPackageCategory::create($buildingPackageCategoryParams);
                if ($buildingPackageCategory->id) {
                    // add files
                    if ($request->file('upload_files')) {
                        $fileService = new FileService();
                        $fileService->store($request->file('upload_files'), [
                            'key' => $buildingPackageCategory->id,
                            'user_id' => Auth::check() ? Auth::user()->id : null,
                            'type' => 'building-package-category',
                            'id' => $buildingPackageCategory->id
                        ]);
                    }
                }
            });
            return response()->json(['Building Package Category successfully created.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBuildingPackageCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBuildingPackageCategoryRequest $request)
    {
        try
        {
            DB::transaction(function() use($request) {
                $buildingPackageCategory = Store::get('buildingPackageCategory');
                $buildingPackageCategoryParams = $request->all();

                $buildingPackageCategory->update($buildingPackageCategoryParams);
                if ($buildingPackageCategory->id) {
                    // add files
                    if ($request->file('upload_files')) {
                        $fileService = new FileService();
                        $fileService->store($request->file('upload_files'), [
                            'key' => $buildingPackageCategory->id,
                            'user_id' => Auth::check() ? Auth::user()->id : null,
                            'type' => 'building-package-category',
                            'id' => $buildingPackageCategory->id
                        ]);
                    }
                }
            });

            return response()->json(['Building Package Category successfully updated.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteBuildingPackageCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteBuildingPackageCategoryRequest $request)
    {
        try
        {
            // get data which has got through validator
            $buildingPackageCategory = Store::get('buildingPackageCategory');
            $buildingPackageCategory->delete();
            return response()->json(['Building Package Category successfully deleted.']);
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
        $isActiveFlags = BuildingPackageCategory::$isActive;

        return response()->json($isActiveFlags);
    }
}
