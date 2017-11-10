<?php

namespace App\Http\Controllers\Api;

use Log;
use Exception;
use Store;
use DB;
use Auth;

use App\Models\OptionCategory;

use App\Validators\Validator;
use App\Http\Requests;
use App\Http\Requests\OptionCategories\IndexOptionCategoryRequest;
use App\Http\Requests\OptionCategories\AddOptionCategoryRequest;
use App\Http\Requests\OptionCategories\UpdateOptionCategoryRequest;
use App\Http\Requests\OptionCategories\DeleteOptionCategoryRequest;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OptionCategoriesController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Get all options
     * @param IndexOptionCategoryRequest $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function index(IndexOptionCategoryRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new OptionCategory());
        $abAssistant->setArrayQuery($request->all());
        // $abAssistant->validate(); // TODO -- not applicable -- need to fix assitant validation
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
     * Get resource
     * @param $id
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function show($id, Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new OptionCategory());
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
            return response()->json(['Option category is not found.'], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddOptionCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddOptionCategoryRequest $request)
    {
        try
        {
            DB::transaction(function() use($request) {
                $params = $request->all();
                $lastOptionCat = OptionCategory::orderBy('sort_id', 'desc')->first();
                $params['sort_id'] = $lastOptionCat ? $lastOptionCat->sort_id + 1 : 1;

                $item = OptionCategory::create($params);
                if ($item->id) {
                    /*
                    if (!isset($params['allowable_models_id']))
                        $params['allowable_models_id'] = [];
                    $item->allowable_models()->sync($params['allowable_models_id']);*/
                }
            });
            return response()->json(['Option category successfully created.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateOptionCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOptionCategoryRequest $request)
    {
        try
        {
            DB::transaction(function() use($request) {
                $item = Store::get('option_category');
                $params = $request->all();

                $item->update($params);
                if ($item->id) {
                    /*
                    if (!isset($params['allowable_models_id']))
                        $params['allowable_models_id'] = [];
                    $item->allowable_models()->sync($params['allowable_models_id']);*/
                }
            });

            return response()->json(['Option category successfully updated.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteOptionCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteOptionCategoryRequest $request)
    {
        try
        {
            // get data which has got through validator
            $item = Store::get('option_category');

            $optionCats = OptionCategory::where('sort_id', '>', $item->sort_id)->get();
            $item->delete();
            foreach($optionCats as $optionCat) {
                $optionCat->update(['sort_id' => $optionCat->sort_id - 1]);
            }

            return response()->json(['Option category successfully deleted.']);
        } catch (Exception $e)
        {
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function groups(Request $request)
    {
        $optionGroups = OptionCategory::groupBy('group')->get()->pluck('group');
        $optionGroups->transform(function($item) {
            return [
                'id' => $item,
                'name' => $item
            ];
        });

        return response()->json($optionGroups);
    }

    /**
     * Update option categories sort ID
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
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

        $optionCats = OptionCategory::whereIn('sort_id', $sortIds)->get();
        foreach ($optionCats as $optionCat) {
            if ($optionCat->sort_id == $data['old_sort_id']) {
                $optionCat->update(['sort_id' => $data['new_sort_id']]);
            } else {
                $data['old_sort_id'] > $data['new_sort_id'] ? $optionCat->update(['sort_id' => $optionCat->sort_id + 1]) : $optionCat->update(['sort_id' => $optionCat->sort_id - 1]);
            }
        }
        return response()->json('Option categories Sort ID successfully updated.');
    }
}
