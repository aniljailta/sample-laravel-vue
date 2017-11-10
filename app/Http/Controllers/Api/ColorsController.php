<?php

namespace App\Http\Controllers\Api;

use Store;
use Auth;
use DB;
use File as Image;
use Storage;

use App\Models\File;
use App\Models\Color;
use App\Models\Option;
use App\Models\ColorCatalog;
use App\Models\BuildingModel;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Colors\IndexColorRequest;
use App\Http\Requests\Colors\AddColorRequest;
use App\Http\Requests\Colors\DeleteColorRequest;
use App\Http\Requests\Colors\UpdateColorRequest;
use App\Http\Requests\Colors\UploadImageRequest;

use App\Validators\Validator;
use App\Services\ArrayBuilder\ArrayBuilderAssistant;
use App\Services\Files\ImageService;

class ColorsController extends Controller
{
    public function __construct()
    {
    }

    public function index(IndexColorRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Color());
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
        $abAssistant->setModel(new Color());
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
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddColorRequest $request)
    {
        try
        {
            DB::transaction(function() use($request) {
                $colorParams = $request->all();
                if ($colorParams['option_id'] == 'null') $colorParams['option_id'] = null;

                $lastColor = Color::orderBy('sort_id', 'desc')->first();
                $colorParams['sort_id'] = $lastColor ? $lastColor->sort_id + 1 : 1;

                $color = Color::create($colorParams);

                $allowableModels = $colorParams['allowable_models_id'] ?? [];
                $color->allowable_models()->sync($allowableModels);

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
     * Store a color from Color Catalog in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function addColor(Request $request)
    {
        try
        {
            DB::transaction(function() use($request) {
                $colorsId = $request->all();
                $colors = ColorCatalog::whereIn('id', $colorsId)->with('image')->get()->toArray();

                $lastColor = Color::orderBy('sort_id', 'desc')->first();
                $i = 1;
                foreach ($colors as $color) {
                    $color['color_catalog_id'] = $color['id'];
                    $color['sort_id'] = $lastColor ? $lastColor->sort_id + $i : $i;
                    $newColor = Color::create($color);

                    if ($color['image']) {
                        $directory = 'public/color/'. $newColor->id;
                        Storage::makeDirectory($directory);
                        $image = Image::copy(storage_path('app/public'.$color['image']['path']), storage_path('app/'.$directory.'/'.$color['image']['name']));

                        $color['image']['user_id'] = Auth::check() ? Auth::user()->id : null;
                        $color['image']['storable_id'] =  $newColor->id;
                        $color['image']['storable_type'] = 'color';
                        $color['image']['path'] = '/color/' . $newColor->id . '/' . $color['image']['name'];
                        File::create($color['image']);
                    }

                    $i++;
                }
            });
            return response()->json(['Color Library successfully updated.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateColorRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateColorRequest $request, $id)
    {
        try
        {
            DB::transaction(function() use($request) {
                $colorParams = $request->all();
                if ($colorParams['option_id'] == 'null') $colorParams['option_id'] = null; 
                $color = Store::get('color');
                
                $color->update($colorParams);
                if ($color->id) {
                    if (array_key_exists('allowable_models_id', $colorParams)) {
                        $color->allowable_models()->sync((array) $colorParams['allowable_models_id']);
                    }

                    // add image
                    if ($request->file('upload_files')) {
                        $image = $request->file('upload_files')[0];

                        $imageService = new ImageService();
                        $imageService->setImage($image)->resize(140, 30)->uploadAs($color);
                    }
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
     * @param DeleteColorRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteColorRequest $request)
    {
        try
        {
            // get data which has got through validator
            $color = Store::get('color');

            $colors = Color::where('sort_id', '>', $color->sort_id)->get();
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
     * Display a listing of the resource.
     * TODO: should be deprecated
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function colorsByUsage(Request $request)
    {
        $useFlag = $request->route('use_flag');
        $validator = Validator::make(['usage' => $useFlag], ['usage' => 'required|string|alpha|in:body,trim,roof']);
        if ($validator->fails()) return response()->json($validator->errors()->all());

        $col = "use_{$useFlag}";
        $colors = Color::with('allowable_models')->where($col, 1)->get()->keyBy('id');

        $colorsFormat = [];
        $colors->each(function ($color) use(&$colorsFormat) {
            $modelsID = $color->allowable_models->pluck('id')->toArray();
            $color->setRelation('allowable_models', null);

            if (!isset($colorsFormat[$color->id]))
            {
                $colorsFormat[$color->id] = $color->toArray();
                $colorsFormat[$color->id]['availableIn'] = $modelsID;
            } else
            {
                $colorsFormat[$color->id]['availableIn'] = array_merge($colorsFormat[$color->id]['availableIn'], $modelsID);
            }

        });

        return response()->json($colorsFormat);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function activeFlags(Request $request) {
        $isActiveFlags = Color::$isActive;

        return response()->json($isActiveFlags);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function types(Request $request) {
        $isActiveFlags = Color::$types;

        return response()->json($isActiveFlags);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function options(Request $request) {
        $options = Option::all();

        return response()->json($options);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buildingModels(Request $request) {
        $buildingModels = BuildingModel::active()
            ->with('style')->whereHas('style', function ($query) {
                $query->where('is_active', 'yes');
            })->get();

        $buildingModelsPerStyle = [];
        $buildingModels->each(function($buildingModel) use(&$buildingModelsPerStyle) {
            if( !isset($buildingModelsPerStyle[$buildingModel->style->name]) )
                $buildingModelsPerStyle[$buildingModel->style->name] = [];

            $buildingModelsPerStyle[$buildingModel->style->name][$buildingModel->id] = $buildingModel->name;
        });

        return response()->json($buildingModelsPerStyle);
    }

    /**
     * Get user company allowable colors.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCompanyAllowableColors()
    {
        $companyColors = Color::pluck('color_catalog_id');
        $companyColors = $companyColors->filter(function($id) {
            return !empty($id);
        });
        // TODO: skip allowable options list while UI for super admin is not ready
        // $allowableColors = Auth::user()->company->company->allowable_colors->whereNotIn('id', $companyColors);
        $allowableStyles = ColorCatalog::active()->whereNotIn('id', $companyColors)->get();

        return response()->json($allowableStyles);
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

        $colors = Color::whereIn('sort_id', $sortIds)->get();
        foreach ($colors as $color) {
            if ($color->sort_id == $data['old_sort_id']) {
                $color->update(['sort_id' => $data['new_sort_id']]);
            } else {
                $data['old_sort_id'] > $data['new_sort_id'] ? $color->update(['sort_id' => $color->sort_id + 1]) : $color->update(['sort_id' => $color->sort_id - 1]);
            }
        }
        return response()->json('Colors sort ID successfully updated.');
    }
}