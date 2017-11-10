<?php

namespace App\Http\Controllers;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\FileSign;
use App\Http\Requests\Esigns\EsignOrderRequest;
use App\Http\Requests\Esigns\EsignOrderRequestBySignatureId;
use App\Services\Esignatures\EsignaturesService;
use App\Services\Orders\OrderDocuments;
use App\Services\Orders\OrderEsignatureService;

use Entrust;
use DB;
use Auth;
use Exception;
use Log;

class OrdersController extends Controller
{
    /**
     * @var Order
     */
    private $order;

    /**
     * @var string
     */
    private $route_name = 'orders';

    /**
     * @var array
     */
    private $params = [];

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->params = [
            'breadcrumbs' => [
                ['url' => '/Orders/', 'page' => 'Orders']
            ],
            'title' => 'Orders',
            'subtitle' => 'Orders',
            'search' => 'yes',
            'filter' => '',
            'items_per_page' => 500,
            'route_name' => $this->route_name,
            'route' => '/' . $this->route_name,
            'data' => null,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view($this->route_name . '.index')->withParams($this->params);
    }

    /**
     * Make embedded esign request (dealer form only)
     * TODO: method only for dealer
     * @param string $orderUuid
     * @param EsignOrderRequest $request
     * @param OrderDocuments $orderDocuments
     * @return mixed
     */
    public function initialEsignOrderDocument(
        string $orderUuid,
        EsignOrderRequest $request,
        OrderDocuments $orderDocuments
    ) {
        try {
            $order = $this->order->where('uuid', $orderUuid)->firstOrFail();
            $embedParams = $orderDocuments->esignDocument(
                $order,
                'complete_order_documents',
                'embed',
                [
                    'role' => FileSign::CUSTOMER_ROLE,
                    'initial' => true
                ]
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['Order is not found.'], 422);
        } catch (Exception $e) {
            Log::error($e);
            return view('esign.errors')->withErrors(['Something went wrong.']);
        }

        $this->params = array_merge($this->params, $embedParams);
        $this->params['thanks_url'] = route('order-esign-thanks');

        return view('esign.hellosign-embed')->withParams($this->params);
    }

    /**
     * Make embedded esign request by signature ID
     * TODO: method only for rto company now
     * @param string                         $orderUuid
     * @param string                         $signatureRequestId
     * @param EsignOrderRequestBySignatureId $request
     * @param OrderEsignatureService         $orderEsignatureService
     * @return mixed
     */
    public function esignOrderDocumentBySignatureId(string $orderUuid,
                                                    string $signatureRequestId,
                                                    EsignOrderRequestBySignatureId $request,
                                                    OrderEsignatureService $orderEsignatureService)
    {
        try {
            $fileSign = FileSign::where('esign_signature_id', $signatureRequestId)
                ->where('is_esigned', false)
                ->whereHas('file.order', function($has) use($orderUuid) {
                    $has->where('uuid', $orderUuid);
                })
                ->firstOrFail();

            $embedParams = $orderEsignatureService->createOrderEmbedEsignatures(
                $fileSign->file->order,
                $fileSign->file,
                ['role' => $fileSign->signer_role]
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['Order is not found.'], 422);
        } catch (Exception $e) {
            Log::error($e);
            return view('esign.errors')->withErrors(['Something went wrong.']);
        }

        $this->params = array_merge($this->params, $embedParams);
        $this->params['thanks_url'] = route('order-esign-thanks');
        return view('esign.hellosign-embed')->withParams($this->params);
    }

    /**
     * Make esign by email request (dealer form only)
     * @param string $orderUuid
     * @param EsignOrderRequest $request
     * @param OrderDocuments $orderDocuments
     * @return mixed
     */
    public function esignOrderDocumentViaEmail(string $orderUuid,
                                               EsignOrderRequest $request,
                                               OrderDocuments $orderDocuments)
    {
        try {
            $order = Order::where('uuid', $orderUuid)->firstOrFail();
            $confirmed = ((int) request('confirm') === 1);
            $signer = [
                'role' => FileSign::CUSTOMER_ROLE,
                'initial' => true
            ];

            $document = $orderDocuments->getDocumentForEsign($order, 'complete_order_documents', 'email', $signer);
            // user should confirm that he want to make esignature request
            if (!$confirmed) {
                return view('esign.hellosign-email')
                    ->with($this->params)
                    ->with('signer', $signer)
                    ->with('order', $order)
                    ->with('file', $document);
            }

            $params = $orderDocuments->esignDocument($order, 'complete_order_documents', 'email', $signer);
        } catch (ModelNotFoundException $e) {
            return response()->json(['Order is not found.'], 422);
        } catch (Exception $e) {
            Log::error($e);
            return view('esign.errors')->withErrors(['Something went wrong.']);
        }

        $this->params = array_merge($this->params, $params);
        return view('esign.hellosign-email-complete')
            ->with($this->params)
            ->with('signer', $signer)
            ->with('order', $order)
            ->with('file', $document);
    }
}
