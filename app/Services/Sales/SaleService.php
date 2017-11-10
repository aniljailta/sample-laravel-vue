<?php

namespace App\Services\Sales;

use App\Models\DealerCommission;
use App\Models\Sale;
use App\Models\Order;
use App\Services\Orders\Dealer\OrderDealerCommissionService;
use App\Services\Orders\OrderService;
use App\Services\Building\BuildingService;
use App\Repositories\SalesRepository;

use DB;
use Illuminate\Support\Facades\Auth;
use Uuid;
use PDF;
use Storage;
use Store;
use Helper;

use Carbon\Carbon;

class SaleService
{
    /**
     * @param Order $order
     * @param OrderService $orderService
     * @return Sale
     */
    public function create(Order $order, OrderService $orderService): Sale {

        $sale = null;
        
        DB::transaction(function() use (&$sale, &$order, $orderService)
        {
            $saleParams = [];
            $orderLocation = $orderService->generateLocation($order);

            $saleParams['building_id'] = $order->building_id;
            $saleParams['location_id'] = $orderLocation->id;
            $saleParams['order_id'] = $order->id;
            $saleParams['status_id'] = 'open';

            $sale = Sale::create($saleParams);

            // apply dealer commission to order
            if ($sale->order->status_id === 'sale_generated') {
                (new OrderDealerCommissionService)->applyCommissionToOrder($order);
            }

            // update building, generate serial and change status to next by priority
            (new BuildingService)->update($order->building, [], [
                'update_serial_number' => true,
                'next_status' => true
            ]);
        });

        return $sale;
    }

    /**
     * @param $sale
     */
    public function cancel(Sale $sale) {
        $sale->order()->update([
            'status_id' => 'draft',
            // reset building
            'building_id' => null, // foreign 
            
        ]);
        $sale->building_id = null;
        $sale->save();
    }

    /**
     * @param $month
     * @param SalesRepository $salesRepo
     * @return array
     */
    public function getCharts($month, SalesRepository $salesRepo): array
    {
        return $salesRepo->getChartData($month);
    }

    /**
     * @param $month
     * @param SalesRepository $salesRepo
     * @return array
     */
    public function getproductionCharts($month, SalesRepository $salesRepo): array
    {
        return $salesRepo->getProductionChartData($month);
    }
}
