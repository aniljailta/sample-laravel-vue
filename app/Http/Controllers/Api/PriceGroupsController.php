<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\PriceGroups\PriceGroupRequest;
use App\Http\Requests\PriceGroups\ImportPriceGroupRequest;
use App\Http\Requests\PriceGroups\ExportPriceListRequest;
use App\Http\Requests\PriceGroups\ImportPriceListRequest;
use App\Models\PriceGroup;
use App\Repositories\PriceGroups\PriceGroupPriceRepository;
use App\Services\PriceGroups\PriceGroupPriceService;
use App\Services\PriceGroups\PriceGroupService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\PriceGroups\PriceGroupRepository;
use App\Services\ArrayBuilder\ArrayBuilderAssistant;

class PriceGroupsController extends Controller
{
    protected $priceGroupRepository, $priceGroupPriceRepository, $priceGroupService;

    /**
     * PriceGroupsController constructor.
     * @param PriceGroupRepository $priceGroupRepository
     * @param PriceGroupPriceRepository $priceGroupPriceRepository
     * @param PriceGroupService $priceGroupService
     */
    public function __construct(
        PriceGroupRepository $priceGroupRepository,
        PriceGroupPriceRepository $priceGroupPriceRepository,
        PriceGroupService $priceGroupService)
    {
        $this->priceGroupRepository = $priceGroupRepository;
        $this->priceGroupPriceRepository = $priceGroupPriceRepository;
        $this->priceGroupService = $priceGroupService;
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
        $abAssistant->setModel(new PriceGroup());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }
        $query = $abAssistant->apply()->getQuery();
        $result = $abAssistant->setQuery($query)->paginate(request('page'), request('per_page'));
        return response()->json($result);
    }

    /**
     * Store a newly created resource in storage.
     * @param PriceGroupRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PriceGroupRequest $request)
    {
        try {
            $this->priceGroupRepository->create(['data' => $request->only('name', 'category', 'publish_date', 'notes')]);
            return response()->json(['msg' => 'Price Group created successfully.']);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * Display the specified resource.
     * @param                       $id
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function show($id, Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new PriceGroup());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }

        $query = $abAssistant->apply()->getQuery();
        try {
            $item = $query->findOrFail($id);
            $priceGroupPublishDates = PriceGroup::whereNotNull('publish_date')->where('category', $item->category)->pluck('publish_date')->toArray();
            $item->publish_dates = $priceGroupPublishDates;
            return response()->json($item);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['Price Group is not found.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PriceGroupRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(PriceGroupRequest $request, $id)
    {
        try {
            $this->priceGroupRepository->update(['data' => $request->all()]);
            return response()->json(['msg' => 'Price Group updated successfully.']);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $this->priceGroupRepository->delete($id);
            return response()->json(['msg' => 'Price Group successfully deleted.']);
        } catch (\Exception $e) {
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPriceList(Request $request)
    {
        $data = $request->all();
        try {
            $price_lists = $this->priceGroupRepository->getPriceListByCategory($data);
            return response()->json($price_lists);
        } catch (\Exception $e) {
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    public function updatePriceGroup(PriceGroupRequest $request)
    {
        try {
            $response = $this->priceGroupRepository->updatePriceGroup($request->only('id', 'category', 'publish_date', 'type'));
            if (!$response) {
                return response()->json(['msg' => 'You can not select this date for publish, Already exist!'], 422);
            }
            return response()->json(['msg' => 'Price Group updated successfully.', 'type' => $request->get('type')]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePriceList(Request $request)
    {
        try {
            $this->priceGroupPriceRepository->updatePriceListByPriceGroup($request->all());
            return response()->json(['msg' => 'Price List Updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportCsv(Request $request, ArrayBuilderAssistant $abAssistant)
    {
        return $this->priceGroupService->exportPriceGroup(
            $request->all(),
            $abAssistant
        )->download('csv');
    }

    /**
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportXls(Request $request, ArrayBuilderAssistant $abAssistant)
    {
        return $this->priceGroupService->exportPriceGroup(
            $request->all(),
            $abAssistant
        )->download('xls');
    }

    /**
     * @param ExportPriceListRequest $request
     * @param PriceGroupPriceService $priceGroupPriceService
     * @return string
     */
    public function exportPriceList(ExportPriceListRequest $request, PriceGroupPriceService $priceGroupPriceService)
    {
        return $priceGroupPriceService->exportPriceList(
            $request->id,
            $request->category
        )->download('xls');
    }

    /**
     * @param ImportPriceListRequest $request
     * @param PriceGroupPriceService $priceGroupPriceService
     * @return \Illuminate\Http\JsonResponse
     */
    public function importPriceList(ImportPriceListRequest $request, PriceGroupPriceService $priceGroupPriceService)
    {
        return $priceGroupPriceService->importPriceList(
            $request->only('name', 'category', 'notes'),
            $request->file('upload_files')[0]
        );
    }

    public function getCategories()
    {
        $categories = collect(PriceGroup::$categories);
        return response()->json($categories);
    }

    public function getStatuses()
    {
        $statuses = collect(PriceGroup::$statuses);
        return response()->json($statuses);
    }
}