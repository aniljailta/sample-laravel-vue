<?php

namespace App\Http\Controllers\Api;

use DB;
use Store;
use Auth;

use App\Models\StyleCatalog;
use App\Models\ManufacturerCompany;
use App\Models\File;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StyleCatalog\IndexStyleCatalogRequest;
use App\Http\Requests\StyleCatalog\AddStyleCatalogRequest;
use App\Http\Requests\StyleCatalog\DeleteStyleCatalogRequest;
use App\Http\Requests\StyleCatalog\UpdateStyleCatalogRequest;
use App\Http\Requests\StyleCatalog\UploadImageRequest;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;
use App\Services\Files\ImageService;

class StyleCatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  IndexStyleCatalogRequest  $request
     * @param  ArrayBuilderAssistant  $abAssistant
     * @return \Illuminate\Http\Response
     */
    public function index(IndexStyleCatalogRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new StyleCatalog());
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
        usort($result['data'], function($a, $b)
        {
            return $a['sort_id'] <=> $b['sort_id'];
        });
        return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AddStyleCatalogRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddStyleCatalogRequest $request)
    {
        try
        {
            DB::transaction(function() use($request) {
                $styleParams = $request->all();
                $styleParams['3d_model'] = $styleParams['3_d_model'] === null ? null : json_encode($styleParams['3_d_model']);
                $lastStyle = StyleCatalog::orderBy('sort_id', 'desc')->first();
                $styleParams['sort_id'] = $lastStyle ? $lastStyle->sort_id + 1 : 1;

                if (isset($styleParams['short_code'])) {
                    $styleParams['short_code'] = strtoupper($styleParams['short_code']);
                }

                $style = StyleCatalog::create($styleParams);
                $style->allowable_companies()->sync($styleParams['allowable_companies_id']);

                // add image
                if ($request->file('upload_files')) {
                    $image = $request->file('upload_files')[0];

                    $imageService = new ImageService();
                    $imageService->setImage($image)->resize(140, 30)->uploadAs($style);
                }
            });
            return response()->json(['Style successfully created.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new StyleCatalog());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) return response()->json($abAssistant->getMessages(), 400);

        $query = $abAssistant->apply()->getQuery();

        try {
            $item = $query->findOrFail($id);
            return response()->json($item);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['Style is not found.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateStyleCatalogRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateStyleCatalogRequest $request)
    {
        try
        {
            DB::transaction(function() use($request, $id) {
                $style = Store::get('style');
                $styleParams = $request->all();
                $styleParams['3d_model'] = json_encode($styleParams['3_d_model']);

                if (isset($styleParams['short_code'])) {
                    $styleParams['short_code'] = strtoupper($styleParams['short_code']);
                }

                $style->update($styleParams);
                $style->allowable_companies()->sync($styleParams['allowable_companies_id']);

                // add image
                if ($request->file('upload_files')) {
                    $image = $request->file('upload_files')[0];

                    $imageService = new ImageService();
                    $imageService->setImage($image)->resize(100, 100)->uploadAs($style);
                }
            });

            return response()->json(['Style successfully updated.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  DeleteStyleCatalogRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, DeleteStyleCatalogRequest $request)
    {
        try
        {
            // get data which has got through validator
            $style = Store::get('style');

            $styles = StyleCatalog::where('sort_id', '>', $style->sort_id)->get();
            $style->delete();
            foreach($styles as $style) {
                $style->update(['sort_id' => $style->sort_id - 1]);
            }

            return response()->json(['Style successfully deleted.']);
        } catch (Exception $e)
        {
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Store a newly created image file in storage.
     *
     * @param UploadImageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function uploadImage(UploadImageRequest $request)
    {
        try {
            $style = Store::get('style_catalog');

            // Add image
            $image = $request->file('upload_files')[0];
            $imageService = new ImageService();
            $imageService->setImage($image)->resize(100, 100)->uploadAs($style);

            $message = $imageService->success() ? $imageService->messages : ['Image successfully uploaded.'];
            $payload = $imageService->files;

            return response()->json(['message' => $message, 'payload' => $payload], 200);
        } catch (Exception $e) {
            Log::error($e);
            $message = $imageService->error() ? $imageService->errors : ['Image has not been uploaded.'];
            return response()->json($message, 422);
        }

        return response()->json(['Image has not been uploaded.'], 422);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function activeFlags(Request $request) {
        $isActiveFlags = StyleCatalog::$isActive;

        return response()->json($isActiveFlags);
    }

    /**
     * Update styles sort ID
     * @param Request $request
     * @return array
     */
    public function updateSortId(Request $request)
    {
        $data = $request->all();

        $sortIds = [];
        if($data['old_sort_id'] > $data['new_sort_id']) {
            for ($i = $data['new_sort_id']; $i <= $data['old_sort_id']; $i++) {
                array_push($sortIds, $i);
            }
        } else {
            for ($i = $data['old_sort_id']; $i <= $data['new_sort_id']; $i++) {
                array_push($sortIds, $i);
            }
        }

        $styles = StyleCatalog::whereIn('sort_id', $sortIds)->get();
        foreach ($styles as $style) {
            if ($style->sort_id == $data['old_sort_id']) {
                $style->update(['sort_id' => $data['new_sort_id']]);
            } else {
                $data['old_sort_id'] > $data['new_sort_id'] ? $style->update(['sort_id' => $style->sort_id + 1]) : $style->update(['sort_id' => $style->sort_id - 1]);
            }
        }
        return response()->json('Styles sort ID successfully updated.');
    }

    /**
     * Get Manufacturer Companies
     *
     * @return array
     */
    public function manufacturerCompanies()
    {
        $companies = ManufacturerCompany::get();

        return response()->json($companies);
    }
}
