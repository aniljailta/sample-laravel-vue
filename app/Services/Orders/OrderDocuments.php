<?php

namespace App\Services\Orders;

use App\Models\File;
use App\Models\Order;
use App\Services\Files\FileService;
use App\Services\Orders\OrderPdfService;
use App\Services\Orders\OrderEsignatureService;

use Barryvdh\Snappy\PdfWrapper;
use Carbon\Carbon;
use App\Exceptions\GeneralException;
use App\Exceptions\BusinessException;

use Illuminate\Http\Response;

class OrderDocuments
{
    /**
     * @var OrderPdfService
     */
    protected $orderPdfService;
    /**
     * @var OrderEsignatureService
     */
    protected $orderEsignatureService;
    /**
     * @var FileService
     */
    protected $fileService;

    /**
     * OrderDocuments constructor.
     *
     * @param OrderPdfService $orderPdfService
     * @param FileService $fileService
     * @param OrderEsignatureService $orderEsignatureService
     */
    public function __construct(
        OrderPdfService $orderPdfService,
        FileService $fileService,
        OrderEsignatureService $orderEsignatureService
    ) {
        $this->orderPdfService = $orderPdfService;
        $this->orderEsignatureService = $orderEsignatureService;
        $this->fileService = $fileService;
    }

    /**
     * Generate document from dealer
     * @param Order $order
     * @param string $categoryId
     * @return PdfWrapper
     * @throws BusinessException
     */
    public function generateDocument(Order &$order, string $categoryId): PdfWrapper {
        /* TODO: use in auth check
        $user = Auth::user();
        if ($order->dealer_id !== $user->id) {

        }
        */

        $order = $this->orderPdfService->calculateOrder($order, $order->dealer, $order->building)->calculator()->getOrder();
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

        $pdfDocument = $this->orderPdfService->generateDocument($order, $categoryId);
        return $pdfDocument;
    }

    public function checkAllowableStateForSave(Order $order, string $categoryId) {
        // modifying files is not allowed by dealer if status is wrong
        $wrongStatuses = ['signature_pending', 'signed', 'submitted', 'cancellation_requested'];
        if (in_array($order->status_id, $wrongStatuses)) {
            throw new BusinessException(trans('exceptions.orders.document.generate.order_status_is_not_acceptable', ['wrong_status' => $order->status['title']]));
        }

        return true;
    }

    /**
     * Save document to storage
     * Return array of added files
     * @param Order $order
     * @param PdfWrapper $pdfDocument
     * @param string $categoryId
     * @param bool $overwriteCategory
     * @return array
     * @throws BusinessException
     */
    public function saveDocumentAs(Order $order, PdfWrapper $pdfDocument, string $categoryId, bool $overwriteCategory = true): array {
        $this->checkAllowableStateForSave($order, $categoryId);

        $storageParams = $this->orderPdfService->saveDocument($order, $pdfDocument, $categoryId);

        // add generated file to storage
        $this->fileService->add($storageParams['path'], $storageParams);
        if ($this->fileService->error()) {
            throw new BusinessException($this->fileService->errors);
        }

        if ($overwriteCategory) {
            // remove unused with the same file category
            $lastFile = last($this->fileService->files);
            File::where('path', $lastFile->path)
                ->where('name', $lastFile->name)
                ->where('storable_id', $lastFile->storable_id)
                ->where('storable_type', $lastFile->storable_type)
                ->where('id', '!=', $lastFile->id)
                ->delete();
        }

        return $this->fileService->files;
    }

    /**
     * @param Order $order
     * @param PdfWrapper $pdfDocument
     * @param string $categoryId
     * @return Response
     */
    public function downloadDocument(Order $order, PdfWrapper $pdfDocument, string $categoryId): Response {
        $documentName = $this->orderPdfService->getOrderDocumentNameByCategory($order, $categoryId);
        return $pdfDocument->download($documentName);
    }

    /**
     * Get document before make esignature request
     * @param Order  $order
     * @param string $categoryId
     * @param string $esignMethod
     * @return mixed
     */
    public function getDocumentForEsign(
        Order $order,
        string $categoryId,
        string $esignMethod
    ) {
        $this->orderEsignatureService->checkRequirements($order, $esignMethod);

        $document = $order->files()->where('category_id', $categoryId)->first();
        // if document is not exists - generate new one
        if (!$document) {
            $pdfDocument = $this->generateDocument($order, $categoryId);
            $document = $this->saveDocumentAs($order, $pdfDocument, $categoryId)[0];
        }

        return $document;
    }

    /**
     * @param Order  $order
     * @param string $categoryId
     * @param string $esignMethod (email or embed)
     * @param array  $signer
     * @return mixed
     * @throws GeneralException
     */
    public function esignDocument(
        Order $order,
        string $categoryId,
        string $esignMethod,
        array $signer
    ) {
        $document = $this->getDocumentForEsign($order, $categoryId, $esignMethod);

        if ($esignMethod === 'embed') {
            $params = $this->orderEsignatureService->createOrderEmbedEsignatures($order, $document, $signer);
        } else {
            if ($esignMethod === 'email') {
                $params = $this->orderEsignatureService->createOrderEmailEsignatures($order, $document, $signer);
            } else {
                throw new GeneralException(trans('exceptions.orders.document.esignature.unable_to_esign'));
            }
        }

        $order->status_id = 'signature_pending';
        $order->save();

        return $params;
    }
}
