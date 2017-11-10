<?php

namespace App\Http\Controllers\Api;
use Log;
use Exception;
use Store;
use Auth;
use File as Image;
use Storage;

use App\Services\Files\FileService;

use App\Models\File;
use App\Models\Option;
use App\Models\OptionCategory;
use App\Models\OptionCatalog;

use App\Validators\Validator;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\IndexOptionRequest;
use App\Http\Requests\OptionCategories\IndexOptionCategoryRequest;
use App\Http\Requests\AddOptionRequest;
use App\Http\Requests\UpdateOptionRequest;
use App\Http\Requests\DeleteOptionRequest;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;

use App\Http\Controllers\Controller;

use Illuminate\Database\Connection as DB;

class OptionsController extends Controller
{
    /**
     * @var DB
     */
    private $db;

    /**
     * @var Option
     */
    private $option;

    /**
     * OptionsController constructor.
     *
     * @param DB $db
     * @param Option $option
     */
    public function __construct(DB $db, Option $option)
    {
        $this->db = $db;
        $this->option = $option;
    }

    /**
     * Get all options
     * @param IndexOptionRequest $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function index(IndexOptionRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $data = $request->all();
        $abAssistant->setModel(new Option());
        $abAssistant->setArrayQuery($data);
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }

        $query = $abAssistant->apply()->getQuery();

        if ($request['role'] === 'customer') $groups = OptionCategory::$customerGroups;
        if ($request['role'] === 'dealer') $groups = OptionCategory::$dealerGroups;
        if(!empty($groups))
            $query->whereHas('category', function($query) use($groups) {
                $query->whereIn('group', $groups);
            });

        $result = $abAssistant->setQuery($query)
            ->paginate(request('page'), request('per_page') ?? $this->getPerPageSetting()
        );
        usort($result['data'], function($a, $b)
        {
            return $a['sort_id'] <=> $b['sort_id'];
        });

        if(isset($data['sort_by_category'])) {
            $options = $result['data'];
            $result['data'] = [];
            foreach($options as $option) {
                $result['data'][$option['category']['name']][] = $option;
            }
            uksort($result['data'], 'strnatcasecmp');
        }
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
        $abAssistant->setModel(new Option());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) return response()->json($abAssistant->getMessages(), 400);

        $query = $abAssistant->apply()->getQuery();

        try {
            $item = $query->findOrFail($id);
            return response()->json($item);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['Option is not found.'], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddOptionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddOptionRequest $request)
    {
        try
        {
            $this->db->transaction(function() use($request) {
                $optionParams = $request->all();
                $optionParams['taxable'] = $optionParams['taxable'] ?? null;
                $optionParams['rto'] = $optionParams['rto'] ?? null;

                $lastOption = Option::where('category_id', $optionParams['category_id'])->orderBy('sort_id', 'desc')->first();
                $optionParams['sort_id'] = $lastOption ? $lastOption->sort_id + 1 : 1;

                if($optionParams['is_default'] == 1) {
                    $defaultOption = OptionCategory::find($optionParams['category_id'])->options()->where('is_default', 1)->first();
                    if($defaultOption) $a = $defaultOption->update(['is_default' => 0]);
                }

                $option = $this->option->create($optionParams);
                if ($option->id) {
                    if (array_key_exists('allowable_models_id', $optionParams)) {
                        $option->allowable_models()->sync((array) $optionParams['allowable_models_id']);
                    }

                    if (array_key_exists('allowable_colors_id', $optionParams)) {
                        $option->allowable_colors()->sync((array) $optionParams['allowable_colors_id']);
                    }

                    // add files
                    if ($request->file('upload_files')) {
                        $fileService = new FileService();
                        $fileService->store($request->file('upload_files'), [
                            'key' => $option->id,
                            'user_id' => Auth::check() ? Auth::user()->id : null,
                            'type' => 'option',
                            'id' => $option->id
                        ]);
                    }
                }
            });
            return response()->json(['Option successfully created.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Store an option from Option Catalog in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function addOption(Request $request)
    {
        try
        {
            $this->db->transaction(function() use($request) {
                $optionsId = $request->all();
                $options = OptionCatalog::whereIn('id', $optionsId)->with('files')->get()->toArray();

                foreach ($options as $option) {
                    $lastOption = Option::where('category_id', $option['category_id'])
                                        ->orderBy('sort_id', 'desc')
                                        ->first();

                    $option['option_catalog_id'] = $option['id'];

                    $option['sort_id'] = $lastOption ? $lastOption->sort_id + 1 : 1;
                    $newOption = Option::create($option);

                    foreach ($option['files'] as $file) {
                        $directory = 'public/option/'. $newOption->id;
                        Storage::makeDirectory($directory);
                        $image = Image::copy(storage_path('app/public'.$file['path']), storage_path('app/'.$directory.'/'.$file['name']));

                        $file['user_id'] = Auth::check() ? Auth::user()->id : null;
                        $file['storable_id'] =  $newOption->id;
                        $file['storable_type'] = 'option';
                        $file['path'] = '/option/' . $newOption->id . '/' . $file['name'];
                        File::create($file);
                    }
                }
            });
            return response()->json(['Option Library successfully updated.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOptionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOptionRequest $request)
    {
        try
        {
            $this->db->transaction(function() use($request) {
                $option = Store::get('option');
                $optionParams = $request->all();
                $optionParams['taxable'] = !empty($optionParams['taxable']) ? "1" : null;
                $optionParams['rto'] = !empty($optionParams['rto']) ? "1" : null;

                if ($option->unit_price != $optionParams['unit_price']) {
                    foreach ($option->building_packages as $buildingPackageOption) {
                        $buildingPackage = $buildingPackageOption->building_package;
                        if ($buildingPackage) {
                            $buildingPackage->total_price += $buildingPackageOption->quantity * ($optionParams['unit_price'] - $option->unit_price);
                            $buildingPackage->save();
                        }
                    }
                }

                if ($option->category_id != $optionParams['category_id']) {
                    $lastOption = Option::where('category_id', $optionParams['category_id'])
                                        ->orderBy('sort_id', 'desc')
                                        ->first();
                    $optionParams['sort_id'] = $lastOption ? $lastOption->sort_id + 1 : 1;
                    $options = $option->category->options()->where('sort_id', '>', $option->sort_id)->get();
                    foreach($options as $opt) {
                        $opt->update(['sort_id' => $opt->sort_id - 1]);
                    }
                }

                if ($optionParams['is_default'] == 1) {
                    $defaultOption = OptionCategory::find($optionParams['category_id'])->options()->where('is_default', 1)->first();
                    if($defaultOption && $defaultOption->id != $option->id) $defaultOption->update(['is_default' => 0]);
                }

                $option->update($optionParams);
                if ($option->id) {
                    if (array_key_exists('allowable_models_id', $optionParams)) {
                        $option->allowable_models()->sync((array) $optionParams['allowable_models_id']);
                    }

                    if (array_key_exists('allowable_colors_id', $optionParams)) {
                        $option->allowable_colors()->sync((array) $optionParams['allowable_colors_id']);
                    }

                    // add files
                    if ($request->file('upload_files')) {
                        $fileService = new FileService();
                        $fileService->store($request->file('upload_files'), [
                            'key' => $option->id,
                            'user_id' => Auth::check() ? Auth::user()->id : null,
                            'type' => 'option',
                            'id' => $option->id
                        ]);
                    }
                }
            });

            return response()->json(['Option successfully updated.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteOptionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteOptionRequest $request)
    {
        try
        {
            // get data which has got through validator
            $option = Store::get('option');

            $options = Option::where('category_id', $option->category_id)
                             ->where('sort_id', '>', $option->sort_id)
                             ->get();

            $option->delete();
            foreach($options as $option) {
                $option->update(['sort_id' => $option->sort_id - 1]);
            }

            return response()->json(['Option successfully deleted.']);
        } catch (Exception $e)
        {
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexOptionCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function categories(IndexOptionCategoryRequest $request)
    {
        $role = $request->input('role');

        $groups = [];
        if ($role === 'customer') $groups = OptionCategory::$customerGroups;
        if ($role === 'dealer') $groups = OptionCategory::$dealerGroups;

        $optionCategories = OptionCategory::query();
        if (!empty($groups)) $optionCategories->whereIn('group', $groups);
        $optionCategories = $optionCategories->orderBy('sort_id','ASC')->with('options')->get();

        return response()->json($optionCategories);
    }

    /**
     * Get files specified option
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getFiles(Request $request)
    {
        $optionId = $request->route('option_id');
        $files = File::where('storable_type', '=', 'option')
            ->where('storable_id', '=', $optionId)->get();
        
        return response()->json($files);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function activeFlags(Request $request) {
        $isActiveFlags = Option::$isActive;

        return response()->json($isActiveFlags);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceQuantityFlags(Request $request) {
        $forceQuantity = Option::$forceQuantity;

        return response()->json($forceQuantity);
    }

    public function constraintTypes(Request $request) {
        $constraintType = Option::$constraintType;
        return response()->json($constraintType);
    }

    /**
     * Get user company allowable options.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCompanyAllowableOptions()
    {
        $companyOptions = Option::pluck('option_catalog_id');
        $companyOptions = $companyOptions->filter(function($id) {
            return !empty($id);
        });
        // TODO: skip allowable options list while UI for super admin is not ready
        // $allowableOptions = Auth::user()->company->company->allowable_options->whereNotIn('id', $companyOptions);
        $allowableOptions = OptionCatalog::active()->whereNotIn('id', $companyOptions)->get();

        return response()->json($allowableOptions);
    }

    /**
     * Update options sort ID
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

        $options = Option::where('category_id', $data['category_id'])->whereIn('sort_id', $sortIds)->get();

        foreach ($options as $option) {
            if ($option->sort_id == $data['old_sort_id']) {
                $option->update(['sort_id' => $data['new_sort_id']]);
            } else {
                $data['old_sort_id'] > $data['new_sort_id'] ? $option->update(['sort_id' => $option->sort_id + 1]) : $option->update(['sort_id' => $option->sort_id - 1]);
            }
        }
        return response()->json('Options sort ID successfully updated.');
    }
}
