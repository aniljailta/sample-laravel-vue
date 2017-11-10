<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;

use App\Models\Order;
use App\Models\Sale;
use App\Models\Building;
use App\Models\BuildingStatus;
use DB;

class HomeController extends Controller
{
    private $order;

    private $sale;

    private $building;

    private $buildingStatus;

    private $carbon;

    public function __construct(
        Order $order,
        Sale $sale,
        Building $building,
        BuildingStatus $buildingStatus,
        Carbon $carbon
    ) {
        $this->order = $order;
        $this->sale = $sale;
        $this->building = $building;
        $this->buildingStatus = $buildingStatus;
        $this->carbon = $carbon;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData()
    {
    	$orders = $this->order->where('status_id', 'submitted')->get();
    	$ordersTotalRetail = 0;
    	foreach ($orders as $order) {
    		$ordersTotalRetail += $order->total_sales_price;
    	}

        $sales = $this->sale->whereIn('status_id', ['open', 'updated'])->get();
        $salesTotalRetail = 0;
        foreach ($sales as $sale) {
            if ($sale->order) $salesTotalRetail += $sale->order->total_sales_price;
        }

        $lastMonthSales = $this->sale->whereHas('order', function ($query){
            $query->where('date_submitted', '>=', $this->carbon->now()->firstOfMonth());
        })->where('status_id', 'invoiced')->get();

        $lastMonthSalesTotalRetail = 0;
        foreach ($lastMonthSales as $sale) {
            if ($sale->order) $lastMonthSalesTotalRetail += $sale->order->total_sales_price;
        }

        $readyToDeliver = $this->buildingStatus->where('name', 'Ready to Deliver')->first();
        $buildingsProduced = $this->building->whereHas('building_history', function ($query) use($readyToDeliver) {
                                $query->where([['created_at', '>=', $this->carbon->now()->firstOfMonth()], ['status_id', $readyToDeliver->id]]);
                            })->get();
        $buildingsProducedTotalRetail = 0;
        foreach ($buildingsProduced as $building) {
            if ($sale->order) $buildingsProducedTotalRetail += $building->total_price;
        }

        $buildingsMoved = $this->building->where('created_at', '>=', $this->carbon->now()->firstOfMonth())->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('building_locations')
                ->whereRaw('building_locations.created_at <> buildings.created_at');
        })->get();

        $buildingsMovedTotalRetail = 0;
        foreach ($buildingsMoved as $building) {
            if ($sale->order) $buildingsMovedTotalRetail += $building->total_price;
        }

        $quotes = $this->order->where('created_at', '>=', $this->carbon->now()->firstOfMonth())->get();
        $quotesTotalRetail = 0;
        foreach ($quotes as $quote) {
            $quotesTotalRetail += $quote->total_sales_price;
        }

    	$data = [
            'date' => $this->carbon->now()->format('F Y'),
    		'needing_rewiew_orders_count' => $orders->count(),
    		'orders_total_retail' => $ordersTotalRetail,
            'needing_rewiew_sales_count' => $sales->count(),
            'sales_total_retail' => $salesTotalRetail,
            'last_month_invoised_sales_count' => $lastMonthSales->count(),
            'last_month_sales_total_retail' => $lastMonthSalesTotalRetail,
            'building_produced_count' => $buildingsProduced->count(),
            'building_produced_total_retail' => $buildingsProducedTotalRetail,
            'building_moved_count' => $buildingsMoved->count(),
            'building_moved_total_retail' => $buildingsMovedTotalRetail,
            'quotes_count' => $quotes->count(),
            'quotes_total_retail' => $quotesTotalRetail
    	];

    	return response()->json($data);
    }
}
