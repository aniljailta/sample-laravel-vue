<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DealerCommission\UpdateAmountDueRequest;
use App\Http\Requests\DealerCommission\UpdateCommissionRateRequest;
use App\Models\DealerCommission;
use App\Services\ArrayBuilder\ArrayBuilderAssistant;
use App\Services\Orders\Dealer\OrderDealerCommissionService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Store;
use Auth;
use DB;

class DealerCommissionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new DealerCommission());
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

        return response()->json($result);
    }

    /**
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new DealerCommission());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }

        return $abAssistant->apply()->get();
    }

    /**
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     */
    public function processAndExport(Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $dealerCommissions = $this->getData($request, $abAssistant);
        $result = $dealerCommissions->toArray();

        $header = [
            'Order Id',
            'Date Order Submitted',
            'Date Order Updated',
            'Order Status',
            'Dealer',
            'Customer Name',
            'Building Serial Number',
            'Total Building Price',
            'Commission Rate',
            'Commission Amount',
            'Dealer Discount',
            'Amount Due',
        ];

        foreach ($dealerCommissions as $dealerCommission) {
            $dealerCommission->status = 'processed';
            $dealerCommission->save();
        }

        Excel::create('dealer_commission ' . date("Ymd-H:i:s"), function ($excel) use ($result, $header) {
            $excel->sheet('Dealer Commission ' . date("Y-m-d"), function ($sheet) use ($result, $header) {
                $sheet->row(1, $header);
                $rowNumber = 2;
                foreach ($result as $key => $item) {
                    $commissionAmount = $item['commission_rate'] / 100 * $item['order']['building']['total_price'];
                    $amountDue = $commissionAmount - $item['dealer_discount'] * ($item['status'] === 'cancelled' ? -1 : 1);
                    $data = [
                        $item['order_id'],
                        $item['order']['date_submitted'],
                        $item['order']['updated_at'],
                        $item['order']['status_id'],
                        $item['dealer']['business_name'],
                        $item['order']['order_reference']['customer_name'],
                        $item['order']['building']['serial_number'],
                        $item['order']['building']['total_price'],
                        $item['commission_rate'],
                        $commissionAmount,
                        $item['dealer_discount'],
                        $amountDue,
                    ];
                    $sheet->row($rowNumber, $data);
                    $rowNumber++;
                }
            });
        })->download('xls');
    }

    /**
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportXls(Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new DealerCommission());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }

        $result = $abAssistant
            ->apply()
            ->get()
            ->toArray();

        $header = [
            'Order Id',
            'Date Order Submitted',
            'Date Order Updated',
            'Order Status',
            'Dealer',
            'Customer Name',
            'Building Serial Number',
            'Total Building Price',
            'Commission Rate',
            'Commission Amount',
            'Dealer Discount',
            'Amount Due',
        ];

        Excel::create('dealer_commission ' . date("Ymd-H:i:s"), function ($excel) use ($result, $header) {
            $excel->sheet('Dealer Commission ' . date("Y-m-d"), function ($sheet) use ($result, $header) {
                $sheet->row(1, $header);
                $rowNumber = 2;
                foreach ($result as $key => $item) {
                    $commissionAmount = $item['commission_rate'] * $item['order']['building']['total_price'];
                    $amountDue = $commissionAmount - $item['dealer_discount'] * ($item['status'] === 'cancelled' ? -1 : 1);
                    $data = [
                        $item['order_id'],
                        $item['order']['date_submitted'],
                        $item['order']['updated_at'],
                        $item['order']['status_id'],
                        $item['dealer']['business_name'],
                        $item['order']['order_reference']['customer_name'],
                        $item['order']['building']['serial_number'],
                        $item['order']['building']['total_price'],
                        $item['commission_rate'],
                        $commissionAmount,
                        $item['dealer_discount'],
                        $amountDue,
                    ];
                    $sheet->row($rowNumber, $data);
                    $rowNumber++;
                }
            });
        })->download('xls');
    }

    /**
     * @param UpdateCommissionRateRequest $request
     * @param OrderDealerCommissionService $orderDealerCommissionService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateCommissionRate(UpdateCommissionRateRequest $request, OrderDealerCommissionService $orderDealerCommissionService)
    {
        $dealerCommission = DealerCommission::with('dealer')->find($request->id);
        $dealerCommission->commission_rate = $request->commission_rate;
        $dealerCommission = $orderDealerCommissionService->recalculateAttribute($dealerCommission, 'amount_due');
        $dealerCommission->save();

        return response([
            'id' => $dealerCommission->id,
            'commission_rate' => $dealerCommission->commission_rate,
            'amount_due' => $dealerCommission->amount_due
        ]);
    }

    /**
     * @param UpdateAmountDueRequest $request
     * @param OrderDealerCommissionService $orderDealerCommissionService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAmountDue(UpdateAmountDueRequest $request, OrderDealerCommissionService $orderDealerCommissionService)
    {
        $dealerCommission = DealerCommission::find($request->id);
        $dealerCommission->amount_due = $request->amount_due;
        $dealerCommission = $orderDealerCommissionService->recalculateAttribute($dealerCommission, 'commission_rate');
        $dealerCommission->save();

        return response([
            'id' => $dealerCommission->id,
            'commission_rate' => $dealerCommission->commission_rate,
            'amount_due' => $dealerCommission->amount_due
        ]);
    }
}