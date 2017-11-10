<?php

namespace App\Http\Controllers\Api;

use DB;
use Store;
use Auth;

use App\Models\ColorCatalog;
use App\Models\ManufacturerCompany;
use App\Models\File;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\ColorCatalog\IndexColorCatalogRequest;
use App\Http\Requests\ColorCatalog\AddColorCatalogRequest;
use App\Http\Requests\ColorCatalog\UpdateColorCatalogRequest;
use App\Http\Requests\ColorCatalog\DeleteColorCatalogRequest;
use App\Http\Requests\ColorCatalog\UploadImageRequest;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;
use App\Services\Files\ImageService;

class ColorCatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  IndexColorCatalogRequest  $request
     * @param  ArrayBuilderAssistant  $abAssistant
     * @return \Illuminate\Http\Response
     */
    public function index(IndexColorCatalogRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new ColorCatalog());
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
     * Display the specified resource.
     *
     * @param  int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new ColorCatalog());
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
            return response()->json(['Color is not found.'], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddColorCatalogRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddColorCatalogRequest $request)
    {
        try
        {
            DB::transaction(function() use($request) {
                $colorParams = $request->all();
                $lastColor = ColorCatalog::orderBy('sort_id', 'desc')->first();
                $colorParams['sort_id'] = $lastColor ? $lastColor->sort_id + 1 : 1;

                $color = ColorCatalog::create($colorParams);
                $color->allowable_companies()->sync($colorParams['allowable_companies_id']);

                // add image
                if ($request->file('upload_files')) {
                    $image = $request->file('upload_files')[0];

                    $imageService = new ImageService();
                    $imageService->setImage($image)->resize(140, 30)->uploadAs($color);
                }
            });
            return response()->json(['Color successfully created.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateColorCatalogRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateColorCatalogRequest $request, $id)
    {
        try
        {
            DB::transaction(function() use($request) {
                $colorParams = $request->all();
                $color = Store::get('color');
                
                $color->update($colorParams);
                $color->allowable_companies()->sync($colorParams['allowable_companies_id']);

                // add files
                if ($request->file('upload_files')) {
                    $image = $request->file('upload_files')[0];

                    $imageService = new ImageService();
                    $imageService->setImage($image)->resize(140, 30)->uploadAs($color);
                }
            });
            return response()->json(['Color successfully updated.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteColorCatalogRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteColorCatalogRequest $request)
    {
        try
        {
            // get data which has got through validator
            $color = Store::get('color');

            $colors = ColorCatalog::where('sort_id', '>', $color->sort_id)->get();
            $color->delete();
            foreach($colors as $color) {
                $color->update(['sort_id' => $color->sort_id - 1]);
            }

            return response()->json(['Color successfully deleted.']);
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
            $color = Store::get('color');

            // Add image
            $image = $request->file('upload_files')[0];
            $imageService = new ImageService();
            $imageService->setImage($image)->resize(140, 30)->uploadAs($color);

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
        $isActiveFlags = ColorCatalog::$isActive;

        return response()->json($isActiveFlags);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function types(Request $request) {
        $types = ColorCatalog::$types;

        return response()->json($types);
    }

    /**
     * Update colors sort ID
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

        $colors = ColorCatalog::whereIn('sort_id', $sortIds)->get();
        foreach ($colors as $color) {
            if ($color->sort_id == $data['old_sort_id']) {
                $color->update(['sort_id' => $data['new_sort_id']]);
            } else {
                $data['old_sort_id'] > $data['new_sort_id'] ? $color->update(['sort_id' => $color->sort_id + 1]) : $color->update(['sort_id' => $color->sort_id - 1]);
            }
        }
        return response()->json('Colors sort ID successfully updated.');
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
