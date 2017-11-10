<?php

namespace App\Http\Controllers\Api;

use DB;
use Store;
use Auth;

use App\Services\Files\FileService;

use App\Models\OptionCatalog;
use App\Models\OptionCategory;
use App\Models\ManufacturerCompany;
use App\Models\File;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\OptionCategories\IndexOptionCategoryRequest;
use App\Http\Requests\OptionCatalog\IndexOptionCatalogRequest;
use App\Http\Requests\OptionCatalog\AddOptionCatalogRequest;
use App\Http\Requests\OptionCatalog\UpdateOptionCatalogRequest;
use App\Http\Requests\OptionCatalog\DeleteOptionCatalogRequest;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;

class OptionCatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @param  IndexOptionCatalogRequest $request
     * @param  ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\Response
     */
    public function index(IndexOptionCatalogRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $data = $request->all();
        $abAssistant->setModel(new OptionCatalog());
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

        $result = $abAssistant->setQuery($query)->paginate(
            request('page'),
            request('per_page') ? request('per_page') : $this->getPerPageSetting()
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
     * Store a newly created resource in storage.
     *
     * @param  AddOptionCatalogRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddOptionCatalogRequest $request)
    {
        try
        {
            DB::transaction(function() use($request) {
                $optionParams = $request->all();
                $optionParams['3d_model'] = $optionParams['3_d_model'] === null ? null : json_encode($optionParams['3_d_model']);
                $optionParams['taxable'] = $optionParams['taxable'] ?? null;
                $optionParams['rto'] = $optionParams['rto'] ?? null;

                $lastOption = OptionCatalog::where('category_id', $optionParams['category_id'])->orderBy('sort_id', 'desc')->first();
                $optionParams['sort_id'] = $lastOption ? $lastOption->sort_id + 1 : 1;

                $option = OptionCatalog::create($optionParams);
                $option->allowable_companies()->sync($optionParams['allowable_companies_id']);

                // add files
                if ($request->file('upload_files')) {
                    $fileService = new FileService();
                    $fileService->store($request->file('upload_files'), [
                        'key' => $option->id,
                        'user_id' => Auth::check() ? Auth::user()->id : null,
                        'type' => 'option-catalog',
                        'id' => $option->id
                    ]);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @param  Request $request
     * @param  ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new OptionCatalog());
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
     * Update the specified resource in storage.
     *
     * @param  UpdateOptionCatalogRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOptionCatalogRequest $request)
    {
        try
        {
            DB::transaction(function() use($request) {
                $option = Store::get('optionCatalog');

                $optionParams = $request->all();
                $optionParams['taxable'] = !empty($optionParams['taxable']) ? "1" : null;
                $optionParams['rto'] = !empty($optionParams['rto']) ? "1" : null;

                if($option->category_id != $optionParams['category_id']) {
                    $lastOption = OptionCatalog::where('category_id', $optionParams['category_id'])
                                               ->orderBy('sort_id', 'desc')
                                               ->first();
                    $optionParams['sort_id'] = $lastOption ? $lastOption->sort_id + 1 : 1;
                    $options = $option->category->catalog_options()->where('sort_id', '>', $option->sort_id)->get();
                    foreach($options as $opt) {
                        $opt->update(['sort_id' => $opt->sort_id - 1]);
                    }
                }

                $option->update($optionParams);
                $option->allowable_companies()->sync($optionParams['allowable_companies_id']);

                // add files
                if ($request->file('upload_files')) {
                    $fileService = new FileService();
                    $fileService->store($request->file('upload_files'), [
                        'key' => $option->id,
                        'user_id' => Auth::check() ? Auth::user()->id : null,
                        'type' => 'option-catalog',
                        'id' => $option->id
                    ]);
                }
            });

            return response()->json(['Option successfully updated.']);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeleteOptionCatalogRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteOptionCatalogRequest $request)
    {
        try
        {
            // get data which has got through validator
            $option = Store::get('optionCatalog');

            $options = OptionCatalog::where('sort_id', '>', $option->sort_id)
                                    ->where('category_id', $option->category_id)->get();
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

        $options = OptionCatalog::where('category_id', $data['category_id'])->whereIn('sort_id', $sortIds)->get();
        foreach ($options as $option) {
            if ($option->sort_id == $data['old_sort_id']) {
                $option->update(['sort_id' => $data['new_sort_id']]);
            } else {
                $data['old_sort_id'] > $data['new_sort_id'] ? $option->update(['sort_id' => $option->sort_id + 1]) : $option->update(['sort_id' => $option->sort_id - 1]);
            }
        }
        return response()->json('Options sort ID successfully updated.');
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
        $optionCategories = $optionCategories->get();

        return response()->json($optionCategories);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function activeFlags(Request $request) {
        $isActiveFlags = OptionCatalog::$isActive;

        return response()->json($isActiveFlags);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceQuantityFlags(Request $request) {
        $forceQuantity = OptionCatalog::$forceQuantity;

        return response()->json($forceQuantity);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function constraintTypes(Request $request) {
        $constraintType = OptionCatalog::$constraintType;
        return response()->json($constraintType);
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
