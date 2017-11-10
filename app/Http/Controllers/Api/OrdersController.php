<?php

namespace App\Http\Controllers\Api;

use App\Events\Orders\OrderCancelled;
use App\Exceptions\BusinessException;
use App\Mail\Orders\OrderCancellerd;
use App\Http\Requests\Orders\ChangeOrderRequest;
use App\Http\Requests\Orders\DealerOrderStatusRequest;
use App\Http\Requests\Orders\RtoSignatureStatusRequest;
use App\Models\ManufacturerCompany;
use App\Models\OrderContact;
use App\Models\Setting;
use App\Models\Building;
use App\Models\DealerCommission;
use App\Services\Building\BuildingService;
use App\Services\Orders\OrderDocuments;
use App\Services\Orders\OrderCalculator;
use App\Services\Orders\OrderContactService;
use App\Services\QrCode\QrCodeService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Log;
use Auth;
use Exception;
use Uuid;
use Store;
use Storage;
use DB;
use Mail;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\File;

use App\Http\Requests;
use App\Http\Requests\Orders\IndexOrderRequest;
use App\Http\Requests\Orders\ShowOrderRequest;
use App\Http\Requests\Orders\SearchOrdersRequest;
use App\Http\Requests\Orders\UpdateOrderRequest;
use App\Http\Requests\Orders\SaveDealerOrderRequest;
use App\Http\Requests\Orders\GenerateOrderDocumentRequest;
use App\Http\Requests\Orders\GenerateCompleteOrderDocumentRequest;
use App\Http\Requests\Orders\CustomerOrderRequest;

use App\Http\Controllers\Controller;
use App\Services\Orders\Dealer\OrderDealerFormService;
use App\Services\Orders\OrderPdfService;
use App\Services\Orders\OrderService;
use App\Services\Orders\OrderCustomerFormService;

use App\Services\Sales\SaleService;
use App\Services\Files\FileService;
use App\Services\ArrayBuilder\ArrayBuilderAssistant;
use App\Validators\Order\OrderSaleValidator;
use Illuminate\Events\Dispatcher as EventDispatcher;

class OrdersController extends Controller
{
    /**
     * @var Order
     */
    protected $order;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;


    /**
     * @var ManufacturerCompany
     */
    protected $companySettings;

    /**
     * OrdersController constructor.
     *
     * @param Order $order
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(
        Order $order,
        EventDispatcher $eventDispatcher
    ) {
        $this->companySettings = Store::get('company_settings');
        $this->order = $order;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexOrderRequest     $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\Response
     */
    public function index(IndexOrderRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Order());
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
     * Get resource
     * @param                       $id
     * @param Request               $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function show($id, Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Order());
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
            return response()->json(['Order is not found.'], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SaveOrderRequestRemove $request
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {

    }

    /**
     * @param $id
     * @param UpdateOrderRequest $request
     * @param SaleService $saleService
     * @param OrderService $orderService
     * @return JsonResponse
     */
    public function update(
        $id,
        UpdateOrderRequest $request,
        SaleService $saleService,
        OrderService $orderService
    )
    {
        $messages = [];

        try {
            DB::beginTransaction();
            $order = $this->order->where('uuid', $id)->firstOrFail();

            // any order with sale && new order status cancelled|submitted:
            // update sale, send email
            // TODO: event based update?
            if ($orderService->saleShouldBeUpdated($order, $request->all())) {
                $order->sale->status_id = 'updated';
                $order->sale->save();
                $this->eventDispatcher->fire(new OrderCancelled($order));
            }

            $order->update($request->all());

            if ($request->status_id === 'sale_generated') {
                // Change order behavior,
                // affected original order
                if ($order->original_order) {
                    $order = $orderService->changeOrder($order);
                    $messages = array_merge_recursive($messages, $orderService->messages);
                }

                // Standard order behavior
                if (!$order->sale) {
                    $sale = $saleService->create($order, $orderService);
                    $messages[] = "New sale #{$sale->id} successfully created.";
                    $building = Building::find($sale->building_id);
                    $building->sold_status = 'sold';
                    $building->save();
                }

                // generate qr codes for buildings which been queued to production
                (new QrCodeService)->generateBuildingQrCodes($order->building);
            }

            if ($request->status_id == 'cancelled' && $order->sale !== null) {
                $existingRecord = DealerCommission::where('order_id', $order->id)->first();
                if ($existingRecord) {
                    $newRecord = $existingRecord->replicate();
                    $newRecord->status = 'pending';
                    $newRecord->amount_due = (-1) * $existingRecord->amount_due;
                    $newRecord->save();
                } else {
                    $commissionRate = $order->dealer_commission_rate;
                    $totalPrice = $order->building->total_price;
                    $dealerCommission = new DealerCommission();
                    $dealerCommission->commission_rate = $commissionRate;
                    $dealerCommission->order_id = $order->id;
                    $dealerCommission->status = 'pending';
                    $dealerCommission->user_id = Auth::user()->id;
                    $dealerCommission->dealer_id = $order->dealer_id;
                    $dealerCommission->dealer_discount = 10; // todo remove hardcoded and place right value
                    $dealerCommission->amount_due = ($commissionRate / 100) * $totalPrice - $dealerCommission->dealer_discount;
                    $dealerCommission->save();
                }
            }

            $messages[] = 'Order successfully updated.';
            DB::commit();
            return response()->json($messages);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([$e->getMessage()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

    }

    /**
     * Get files specified order
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function files(Request $request)
    {
        $orderUuid = $request->route('order_uuid');
        $order = Order::where('uuid', $orderUuid)->first();
        $files = File::where('storable_type', '=', 'order')
            ->where('storable_id', '=', $order->id)->get();

        return response()->json($files);
    }

    /**
     * Get order statuses
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function statuses(Request $request)
    {
        $orderStatuses = collect(Order::$statuses);
        return response()->json($orderStatuses);
    }

    /**
     * Get order types
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function orderTypes(Request $request)
    {
        $orderTypes = collect(Order::$orderTypes);
        return response()->json($orderTypes);
    }

    /**
     * Get order payment types
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function paymentTypes(Request $request)
    {
        $paymentTypes = collect(Order::$paymentTypes);
        return response()->json($paymentTypes);
    }

    /**
     * Search order based on email & dealer.
     *
     * @param SearchOrdersRequest|Request $request
     * @return \Illuminate\Http\Response
     */

    public function search(SearchOrdersRequest $request)
    {
        try {
            $data = $request->all();
            if (isset($data['id'])) {
                $order = Order::with('order_reference')
                              ->whereId($data['id'])
                              ->select('uuid', 'dealer_notes', 'updated_at', 'status_id', 'reference_id')
                              ->first();
                return response()->json($order);
            }

            $inputs = $request->only('customer', 'dealer_id');
            $orders = Order::with('order_reference')
                ->where('dealer_id', $inputs['dealer_id'])
                ->whereHas('order_reference', function ($query) use ($inputs) {
                    if (array_key_exists('email', $inputs['customer']))
                        $query->where('email', 'like', "%{$inputs['customer']['email']}%");

                    if (array_key_exists('first_name', $inputs['customer']))
                        $query->where('first_name', 'like', "%{$inputs['customer']['first_name']}%");

                    if (array_key_exists('last_name', $inputs['customer']))
                        $query->where('last_name', 'like', "%{$inputs['customer']['last_name']}%");
                })
                ->select('uuid', 'dealer_notes', 'updated_at', 'status_id', 'reference_id')
                ->get();

            if ($orders->isEmpty()) {
                return response()->json(['There are no orders for provided customer.'], 422);
            }

            return response()->json($orders);
        } catch (ModelNotFoundException $e) {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Store a newly created resource in storage (from dealer form).
     * + Calculations
     *
     * @param SaveDealerOrderRequest $request
     * @param OrderService           $orderService
     * @param OrderDealerFormService $orderDealerFormService
     * @return \Illuminate\Http\Response
     */
    public function saveDealerOrder(
        SaveDealerOrderRequest $request,
        OrderService $orderService,
        OrderDealerFormService $orderDealerFormService
    ) {
        try {
            $order = $orderDealerFormService->toModel($request->all());
            $order = $orderService->saveDealerOrder($order);

            //look for plan view images and save them
            if ($order->building_id && isset($request->views) && is_array($request->views) && count($request->views)) {
                foreach ($request->views as $view => $base64_image) {
                    // Add files
                    $fileService = new FileService();
                    $fileService->saveViewFiles($order, $view, $base64_image);
                }
            }
            $resState = [
                'state' => [
                    'id' => $order->uuid,
                    'dealer_notes' => $order->dealer_notes,
                    'note_admin' => $order->note_admin,
                    'note_dealer' => $order->note_dealer,
                    'status' => $order->status,
                    'status_id' => $order->status_id,
                    'updated_at' => $order->updated_at->format('m-d-Y H:i:s T'),
                ],
                'customer' => [
                    'email' => $order->order_reference->email,
                    'first_name' => $order->order_reference->first_name,
                    'last_name' => $order->order_reference->last_name,
                ],
            ];
            return $resState;
        } catch (BusinessException $e) {
            Log::error($e);
            return response()->json([$e->getMessage()], 422);
        }
    }

    /**
     * Display the specified resource. (for dealer order form now)
     *
     * @param  int                   $id
     * @param ShowOrderRequest       $request
     * @param OrderDealerFormService $orderDealerFormService
     * @return \Illuminate\Http\Response
     */
    public function getDealerOrder($id, ShowOrderRequest $request, OrderDealerFormService $orderDealerFormService)
    {
        try {
            $order = $this->order->where('uuid', $id)
                ->with([
                    'dealer',
                    'dealer.location',
                    'files' => function ($query) {
                        $query->whereIn('category_id', [
                            'signed_order_documents',
                            'signed_building_configuration',
                            'signed_neighbor_release',
                            'e_signed_order_documents',
                            'driver_license',
                            'signed_deposit_receipt',
                            'complete_order_documents'
                        ]);
                    },
                    'options',
                    'options.option',
                    'order_reference',
                    'building',
                    'building.building_package',
                    'building.building_package.options',
                    'building.building_package.building_model',
                    'building.building_options',
                    'building.building_options.option',
                    'building.building_options.option.category',
                    'building.building_options.option_color',
                    'building.building_options.option_color.color',
                    'building.building_options.option.allowable_colors',
                    'building.building_options.option.allowable_models',
                    'building.building_model',
                    'building.building_model.style.catalog',
                    'building.building_model.style.building_models',
                    'building.building_history',
                    'building.building_history.building_status',
                    'original_order'
                ])
                ->firstOrFail();

            $adjustedOrder = $orderDealerFormService->toArray($order);

            return response()->json($adjustedOrder);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * @param DealerOrderStatusRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDealerOrderStatus(DealerOrderStatusRequest $request): JsonResponse
    {
        try {
            $status = $this->order->where('uuid', $request->get('id'))->firstOrFail(['status_id']);
        } catch (Exception $e) {
            return $this->returnJsonErrorResponse();
        }

        return response()->json($status);
    }

    /**
     * @param RtoSignatureStatusRequest $request
     *
     * @return JsonResponse
     */
    public function getRtoSignatureStatus(RtoSignatureStatusRequest $request): JsonResponse
    {
        try {
            $order = $this->order->where('uuid', $request->get('id'))->firstOrFail();
            $rtoSignaturePending = false;

            $file = $order->files->where('category_id', 'complete_order_documents')->first();
            if ($file) {
                $rtoSignaturePending = $file->rto_signature_pending;
            }

            return response()->json(['rto_signature_received' => !$rtoSignaturePending]);
        } catch (Exception $e) {
            return $this->returnJsonErrorResponse();
        }
    }

    /**
     * Generate document (used for dealer order form now)
     * @param                              $id
     * @param                              $categoryId
     * @param GenerateOrderDocumentRequest $request
     * @param OrderDocuments $orderDocuments
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateDocument($id,
                                     $categoryId,
                                     GenerateOrderDocumentRequest $request,
                                     OrderDocuments $orderDocuments)
    {
        try {
            $order = Order::where('uuid', $id)->with('original_order')->firstOrFail();
            $save = $request->input('save');

            $pdfDocument = $orderDocuments->generateDocument($order, $categoryId);

            if ($save) {
                $files = $orderDocuments->saveDocumentAs($order, $pdfDocument, $categoryId);
            } else {
                $response = $orderDocuments->downloadDocument($order, $pdfDocument, $categoryId);

                // cookie required for correct detecting the moment on client-side, that
                // file is successfully started transfering
                // https://stackoverflow.com/questions/1106377/detect-when-browser-receives-file-download/4168965#4168965
                if ($request->token) {
                    $response->withCookie(cookie($request->token, 1, 1, '/', null, false, false));
                }

                return $response;
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['Order is not found.'], 422);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }

        return response()->json([
            'success' => true,
            'files' => $files,
        ]);
    }

    /**
     * @param $id
     * @param OrderPdfService $orderPdfService
     * @return mixed
     */
    public function pdfOrderDocuments($id, OrderPdfService $orderPdfService)
    {
        try {
            $order = Order::where('uuid', $id)->firstOrFail();
            $order = $orderPdfService->calculateOrder($order, $order->dealer, $order->building)->calculator()->getOrder();
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

            $pdf = $orderPdfService->getOrderDocuments($order);
            return $pdf->stream();
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * /customer-order-form
     * @param CustomerOrderRequest $request
     * @param OrderService $orderService
     * @param OrderPdfService $orderPdfService
     * @param OrderDocuments $orderDocuments
     * @param OrderCustomerFormService $orderCustomerFormService
     * @return JsonResponse
     */
    public function customerOrder(
        CustomerOrderRequest $request,
        OrderService $orderService,
        OrderPdfService $orderPdfService,
        OrderDocuments $orderDocuments,
        OrderCustomerFormService $orderCustomerFormService)
    {
        try {
            $order = $orderCustomerFormService->toModel($request->all());
            $order = $orderService->saveCustomerOrder($order);

            $pdf = $orderPdfService->getQuoteForms($order);
            $files = $orderDocuments->saveDocumentAs($order, $pdf, 'quote_forms');
            $file = last($files);

            $inputs = $request->all();
            $mailResult = Mail::send('emails.customer-order', [
                'customer' => $inputs['customer'],
                'contact_type' => $inputs['contact_type'],
                'contact_time' => $inputs['contact_time'],
            ], function ($message) use ($file, $inputs) {
                $fullName = $inputs['customer']['first_name'] . ' ' . $inputs['customer']['last_name'];

                if (app()->environment('production')) {
                    $message->cc(config('mail.from.address'));
                }

                $message->to($inputs['customer']['email'], $fullName)->subject('Shed Quote');
                $message->attachData(Storage::disk('local')->get('public' . $file->path), 'order.pdf', [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="quote.pdf"',
                ]);
            });

            if (!Mail::failures()) {
                return response()->json([
                    'status' => 'success',
                ]);
            }
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'Something went wrong.',], 422);
        }

        return response()->json(['message' => 'Something went wrong.',], 422);
    }

    public function exportCsv(IndexOrderRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Order());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }

        $result = $abAssistant->apply()->get()->toArray();

        $header = $this->getExportDataHeader($result[0]);

        Excel::create('export' . date("Ymd-H:i:s"), function ($excel) use ($result, $header) {
            $excel->sheet('Orders Export ' . date("Y-m-d"), function ($sheet) use ($result, $header) {
                $sheet->row(1, $header);
                $rowNumber = 2;
                foreach ($result as $key => $item) {
                    $data = $this->getExportDataValues($header, $item);
                    $sheet->row($rowNumber, $data);
                    $rowNumber++;
                }
            });
        })->download('csv');
    }

    public function exportXls(IndexOrderRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Order());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }

        $result = $abAssistant->apply()->get()->toArray();

        $header = $this->getExportDataHeader($result[0]);

        Excel::create('export' . date("Ymd-H:i:s"), function ($excel) use ($result, $header) {
            $excel->sheet('Orders Export ' . date("Y-m-d"), function ($sheet) use ($result, $header) {
                $sheet->row(1, $header);
                $rowNumber = 2;
                foreach ($result as $key => $item) {
                    $data = $this->getExportDataValues($header, $item);
                    $sheet->row($rowNumber, $data);
                    $rowNumber++;
                }
            });
        })->download('xls');
    }

    public function getExportDataHeader($item)
    {
        $header = [];
        if (isset($item['id'])) {
            $header['id'] = 'Order ID';
        }
        if (isset($item['created_at'])) {
            $header['date_created'] = 'Date Created';
        }
        if (isset($item['status'])) {
            $header['status'] = 'Status';
        }
        if (isset($item['order_reference']) && array_key_exists('customer_name', $item['order_reference'])) {
            $header['customer'] = 'Customer';
        }
        if (isset($item['order_type'])) {
            $header['order_type'] = 'Order Type';
        }
        if (isset($item['building']) && array_key_exists('serial_number', $item['building'])) {
            $header['serial_number'] = 'Building SN';
        }
        if (isset($item['dealer'])) {
            $header['dealer'] = 'Dealer';
        }
        if (isset($item['retail'])) {
            $header['retail'] = 'Retail';
        }
        return $header;
    }

    public function getExportDataValues($header, $item)
    {
        $data = [];
        if (isset($header['id'])) {
            $data[] = $item['id'];
        }
        if (isset($header['date_created'])) {
            $data[] = $item['created_at'];
        }
        if (isset($header['status'])) {
            $data[] = $item['status']['title'];
        }
        if (isset($header['customer'])) {
            $data[] = $item['order_reference']['customer_name'];
        }
        if (isset($header['order_type'])) {
            $data[] = $item['order_type']['title'];
        }
        if (isset($header['serial_number'])) {
            $data[] = $item['building']['serial_number'];
        }
        if (isset($header['dealer'])) {
            $data[] = $item['dealer']['business_name'];
        }
        if (isset($header['retail'])) {
            $data[] = $item['retail'];
        }

        return $data;
    }

    public function getLeadContacts(Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new OrderContact());
        $abAssistant->setArrayQuery($request->all());
        $query = $abAssistant->apply()->getQuery();
        $result = $abAssistant->setQuery($query)->paginate(
            request('page'),
            request('per_page') ? request('per_page') : $this->getPerPageSetting()
        );

        // retrieve expired date from created_at + initial_contact_eligibility
        $setting = new Setting();
        $eligibility = $setting
            ->where('id', 'initial_contact_eligibility')
            ->pluck('value')
            ->first();
        foreach ($result['data'] as $key => $value) {
            if (!empty($value['created_at'])) {
                $expired_at = Carbon::createFromTimestamp(strtotime($value['created_at']))->addDays($eligibility);
                $result['data'][ $key ]['expired_at'] = $expired_at;
            }
        }

        return response()->json($result);
    }

    public function exportXlsForLeadContacts(Request $request, ArrayBuilderAssistant $abAssistant, OrderContactService $orderContactService) {
        $abAssistant->setModel(new OrderContact());
        $abAssistant->setArrayQuery($request->all());
        $result = $abAssistant->apply()->get()->toArray();
        $dataToExport = $orderContactService->generateExportData($result);
        $header = $orderContactService->generateExportHeader();
        Excel::create('export' . date("Ymd-H:i:s"), function ($excel) use ($dataToExport, $header, $orderContactService) {
            $excel->sheet('L and C Export ' . date("Y-m-d"), function ($sheet) use ($dataToExport, $header) {
                $sheet->row(1, $header);
                $rowNumber = 2;
                foreach ($dataToExport as $key => $item) {
                    $sheet->row($rowNumber, $item);
                    $rowNumber++;
                }
            });
        })->download('xls');
    }
    public function exportCsvForLeadContacts(Request $request, ArrayBuilderAssistant $abAssistant, OrderContactService $orderContactService) {
        $abAssistant->setModel(new OrderContact());
        $abAssistant->setArrayQuery($request->all());
        $result = $abAssistant->apply()->get()->toArray();
        $dataToExport = $orderContactService->generateExportData($result);
        $header = $orderContactService->generateExportHeader();
        Excel::create('export' . date("Ymd-H:i:s"), function ($excel) use ($dataToExport, $header, $orderContactService) {
            $excel->sheet('L and C Export ' . date("Y-m-d"), function ($sheet) use ($dataToExport, $header) {
                $sheet->row(1, $header);
                $rowNumber = 2;
                foreach ($dataToExport as $key => $item) {
                    $sheet->row($rowNumber, $item);
                    $rowNumber++;
                }
            });
        })->download('csv');
    }

    public function getCustomer()
    {
        $orderContact = new OrderContact();
        $customers = $orderContact
            ->whereHas('customer')
            ->with(['customer' => function ($query) {
                $query->select('id', 'first_name', 'last_name');
            }])
            ->get();
        $customerData = [];
        foreach ($customers as $key => $value) {
            $customerData[$value->customer->id] = [
                'id' => $value->customer->id,
                'title' => $value->customer->first_name . ' ' . $value->customer->last_name,
            ];
        }
        return response()->json($customerData);
    }

    public function getOrder()
    {
        $orderContact = new OrderContact();
        $orders = $orderContact
            ->whereHas('order')
            ->with(['order' => function ($query) {
                $query->select('id', 'date_submitted');
            }])
            ->get();
        $orderData = [];
        foreach ($orders as $key => $value) {
            $orderData[$value->order->id] = [
                'id' => $value->order->id,
                'title' => $value->order->date_submitted,
            ];
        }
        return response()->json($orderData);
    }

    /**
     * @param $orderUuid
     * @param ChangeOrderRequest $request
     * @param OrderService $orderService
     * @param OrderDealerFormService $orderDealerFormService
     * @param BuildingService $buildingService
     * @return JsonResponse
     */
    public function cloneOrder(
        $orderUuid,
        ChangeOrderRequest $request,
        OrderService $orderService,
        OrderDealerFormService $orderDealerFormService,
        BuildingService $buildingService
    )
    {
        try {
            $order = Store::get('order');
            $order->load('building.last_status.building_status');

            // allow only submitted/sale generated order to be changed via 'change order'
            // allow building to be changed if status priority > 2 (building started)
            if (!in_array($order->status_id, ['signature_pending', 'signed', 'submitted', 'sale_generated'])) {
                throw new \App\Exceptions\BusinessException(trans('exceptions.orders.change_order_is_not_allowed_order_status', [
                    'status' => $order->status['title']
                ]));
            }

            if (!in_array($order->building->last_status->building_status->name, ['Draft', 'Pending', 'Building Started', 'Building in Progress'])) {
                throw new \App\Exceptions\BusinessException(trans('exceptions.orders.change_order_is_not_allowed_building_status', [
                    'status' => $order->building->last_status->building_status->name
                ]));
            }

            DB::beginTransaction();
            $order->load([
                    'dealer',
                    'dealer.location',
                    'files' => function ($query) {
                        $query->whereIn('category_id', [
                            'driver_license',
                        ]);
                    },
                    'order_reference',
                    'options',
                    'options.option',
                    'building.building_package',
                    'building.building_package.options',
                    'building.building_package.building_model',
                    'building.building_options',
                    'building.building_options.option',
                    'building.building_options.option.category',
                    'building.building_options.option_color',
                    'building.building_options.option_color.color',
                    'building.building_options.option.allowable_colors',
                    'building.building_options.option.allowable_models',
                    'building.building_model',
                    'building.building_model.style',
                    'building.building_model.style.building_models',
                    'building.building_history',
                    'building.building_history.building_status',
                    'original_order',
                ]);

            // duplicate parent order
            $newOrder = $order->replicate();
            $newOrder->uuid = Uuid::generate(4)->string;
            $newOrder->status_id = Order::INITIAL_STATUS_ID; // default on new
            $newOrder->order_date = date('Y-m-d');
            $newOrder->amount_received = null;
            $newOrder->payment_method = null;
            $newOrder->transaction_id = null;
            $newOrder->signature_method_id = null;
            $newOrder->date_submitted = null;

            // set initial change order fee and recalculate order
            $newOrder->change_order_fee = $this->companySettings->change_order_fee;
            $newOrder->original_order_id = $order->id;
            $newOrder->setRelation('original_order', $order);
            $newOrder = (new OrderCalculator)
                ->setOrder($newOrder)
                ->setBuilding($newOrder->building)
                ->setDealer($newOrder->dealer)
                ->calculateOrder()
                ->getOrder();
            $newOrder->save();

            foreach ($order->options as $option) {
                $newOrder->options()->save($option->replicate());
            }

            // driver license attachment is not specific per order values - can to copy symlink
            $driverLicense = $order->files()->where('category_id', 'driver_license')->first();
            if (!empty($driverLicense)) {
                $driverLicense = $driverLicense->replicate();
                $driverLicense->storable_id = $newOrder->id;
                $newOrder->files()->save($driverLicense);
            }

            $orderBuildingDetails = $order->building->toArray();
            $orderBuildingDetails['order_id'] = $newOrder->id;
            $orderBuildingDetails['options'] = $orderBuildingDetails['building_options'];
            $orderBuildingDetails['model'] = $orderBuildingDetails['building_model'];
            unset($orderBuildingDetails['serial_number']);

            $newBuilding = $buildingService->create($orderBuildingDetails);
            $newOrder->update(['building_id' => $newBuilding->id]);
            $newOrder->load([
                'dealer',
                'dealer.location',
                'files',
                'order_reference',
                'options',
                'options.option',
                'building',
                'building.building_package',
                'building.building_package.options',
                'building.building_package.building_model',
                'building.building_options',
                'building.building_options.option',
                'building.building_options.option.category',
                'building.building_options.option_color',
                'building.building_options.option_color.color',
                'building.building_options.option.allowable_colors',
                'building.building_options.option.allowable_models',
                'building.building_model',
                'building.building_model.style',
                'building.building_model.style.building_models',
                'building.building_history',
                'building.building_history.building_status',
                'original_order',
            ]);
            DB::commit();
            $adjustedOrder = $orderDealerFormService->toArray($newOrder);
            return response()->json($adjustedOrder);
        } catch (BusinessException $e) {
            Log::error($e);
            return response()->json([$e->getMessage()], 422);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }
}
