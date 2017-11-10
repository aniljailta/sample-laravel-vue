<?php

namespace App\Http\Controllers\Api;

use DB;
use Event;
use Store;
use Auth;
use File as Image;
use Storage;

use App\Models\File;
use App\Models\Style;
use App\Models\StyleCatalog;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Styles\IndexStyleRequest;
use App\Http\Requests\Styles\AddStyleRequest;
use App\Http\Requests\Styles\UpdateStyleRequest;
use App\Http\Requests\Styles\DeleteStyleRequest;
use App\Http\Requests\Styles\UploadImageRequest;
use App\Http\Controllers\Controller;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;
use App\Services\Files\ImageService;

class StylesController extends Controller
{
    /**
     * Get all styles
     * @param IndexStyleRequest $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function index(IndexStyleRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $model = new Style;
        $abAssistant->setModel($model);
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
    public function show($id, Request $request, ArrayBuilderAssistant $abAssistant) {
        $abAssistant->setModel(new Style());
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
     * Store a newly created resource in storage.
     *
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            DB::transaction(function() use($request) {
                $stylesId = $request->all();
                $styles = StyleCatalog::whereIn('id', $stylesId)->with('image')->get()->toArray();

                $lastStyle = Style::orderBy('sort_id', 'desc')->first();
                $i = 1;
                foreach ($styles as $style) {
                    $style['style_catalog_id'] = $style['id'];
                    $style['sort_id'] = $lastStyle ? $lastStyle->sort_id + $i : $i;
                    $newStyle = Style::create($style);

                    if ($style['image']) {
                        $directory = 'public/style/'. $newStyle->id;
                        Storage::makeDirectory($directory);
                        $image = Image::copy(storage_path('app/public'.$style['image']['path']), storage_path('app/'.$directory.'/'.$style['image']['name']));

                        $style['image']['user_id'] = Auth::check() ? Auth::user()->id : null;
                        $style['image']['storable_id'] =  $newStyle->id;
                        $style['image']['storable_type'] = 'style';
                        $style['image']['path'] = '/style/' . $newStyle->id . '/' . $style['image']['name'];
                        File::create($style['image']);
                    }

                    $i++;
                }
            });
            return response()->json(['Style Library successfully updated.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @param UpdateStyleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateStyleRequest $request)
    {
        try
        {
            DB::transaction(function() use($request, $id) {
                $style = Store::get('style');
                $styleParams = $request->all();

                if (isset($styleParams['short_code'])) {
                    $styleParams['short_code'] = strtoupper($styleParams['short_code']);
                }

                $style->update($styleParams);

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
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteStyleRequest $request)
    {
        try
        {
            // get data which has got through validator
            $style = Store::get('style');

            $styles = Style::where('sort_id', '>', $style->sort_id)->get();
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
            $style = Store::get('style');

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
        $isActiveFlags = Style::$isActive;

        return response()->json($isActiveFlags);
    }

    /**
     * Get user company allowable styles.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCompanyAllowableStyles()
    {
        $companyStyles = Style::pluck('style_catalog_id');
        $companyStyles = $companyStyles->filter(function($id) {
            return !empty($id);
        });
        // TODO: skip allowable options list while UI for super admin is not ready
        // $allowableStyles = Auth::user()->company->company->allowable_styles->whereNotIn('id', $companyStyles);
        $allowableStyles = StyleCatalog::active()->whereNotIn('id', $companyStyles)->get();

        return response()->json($allowableStyles);
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

        $styles = Style::whereIn('sort_id', $sortIds)->get();
        foreach ($styles as $style) {
            if ($style->sort_id == $data['old_sort_id']) {
                $style->update(['sort_id' => $data['new_sort_id']]);
            } else {
                $data['old_sort_id'] > $data['new_sort_id'] ? $style->update(['sort_id' => $style->sort_id + 1]) : $style->update(['sort_id' => $style->sort_id - 1]);
            }
        }
        return response()->json('Styles sort ID successfully updated.');
    }
}
