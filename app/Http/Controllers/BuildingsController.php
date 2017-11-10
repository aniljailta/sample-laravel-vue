<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetWorkBuildingFormRequest;
use App\Services\Orders\OrderPdfService;
use Illuminate\Support\Facades\Artisan;
use PDF;
use DB;
use Auth;
use Event;
use Store;
use Log;
use Storage;
use Helper;
use Exception;

use App\Models\Building;
use App\Models\Order;
use App\Models\Dealer;
use App\Http\Requests;

use App\Http\Requests\GetInventoryFormRequest;
use App\Http\Requests\GetWorkFormRequest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BuildingsController extends Controller
{
    public function __construct(){}

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){}

    /**
     * @param $id
     * @param GetInventoryFormRequest $request
     * @param OrderPdfService $orderPdfService
     * @return mixed
     */
    public function pdfInventoryForm($id, GetInventoryFormRequest $request, OrderPdfService $orderPdfService)
    {
        try {
            $building = Building::findOrFail($id)->load([
                'building_package.options',
                'building_package.building_model',
                'building_options.option.category',
                'building_options.option_color.color',
                'building_model.style.building_models'
            ]);
            $dealer = Store::get('dealer')->load('location');

            $pdf = $orderPdfService->getInventoryForm($building, $dealer);

            return $pdf->stream();
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    /**
     * @param $id
     * @param GetWorkFormRequest $request
     * @param OrderPdfService $orderPdfService
     * @return mixed
     */
    public function pdfWorkOrder($id, GetWorkFormRequest $request, OrderPdfService $orderPdfService)
    {
        try {
            $order = Order::with(['building', 'dealer'])
                ->where('building_id', $id)
                ->orderBy('id', 'desc')
                ->firstOrFail()
                ->load([
                    'dealer',
                    'building.building_package.options',
                    'building.building_package.building_model',
                    'building.building_options.option.category',
                    'building.building_options.option_color.color',
                    'building.building_model.style.building_models'
                ]);

            $pdf = $orderPdfService->getWorkOrder($order);
            return $pdf->stream();
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    /**
     * @param $id
     * @param GetWorkBuildingFormRequest $request
     * @param OrderPdfService $orderPdfService
     * @return mixed
     */
    public function pdfCustomerOrder($id, GetWorkBuildingFormRequest $request, OrderPdfService $orderPdfService)
    {
        try {
            $building = Building::findOrFail($id);
            $order = $building->order;
            $order->load([
                'options',
                'options.option',
                'order_reference',
                'building.building_package.options',
                'building.building_package.building_model',
                'building.building_options.option',
                'building.building_options.option_color',
                'building.building_options.option.allowable_colors',
                'building.building_options.option.allowable_models',
                'building.building_model.style.building_models'
            ]);

            $pdf = $orderPdfService->getCustomerOrder($order);
            return $pdf->stream();
        } catch (Exception $e) {
            Log::error($e);
        }
    }
    
    public function import()
    {
        array_push($this->params['breadcrumbs'], ['url' => '', 'page' => 'Import Buildings']);
        $this->params['subtitle'] = 'Import Buildings';

        return view($this->route_name . '.import')->withParams($this->params);
    }
}
