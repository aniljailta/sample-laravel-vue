<?php

namespace App\Services\Orders;

use App\Models\Company;
use App\Models\RtoCompany;
use App\Services\Esignatures\EsignaturesService;
use App\Notifications\RtoCompanies\OrderRtoSignatureRequested;

use App\Models\Order;
use App\Models\OrderReference;
use App\Models\File;
use App\Models\FileSign;
use App\Models\Setting;
use App\Exceptions\BusinessException;

use HelloSign\SignatureRequest;
use Illuminate\Support\Collection;
use DB;

class OrderEsignatureService
{
    /**
     * @var EsignaturesService
     */
    protected $esignaturesService;

    public function __construct(EsignaturesService $esignaturesService)
    {
        $this->esignaturesService = $esignaturesService;
    }

    /**
     * Check requirements for making new esignature request (for both methods: embed and email)
     * @param Order $order
     * @param string $type
     * @throws BusinessException
     */
    public function checkRequirements(Order $order, string $type)
    {
        if (empty($order->order_reference->email)) {
            throw new BusinessException(trans('exceptions.orders.document.esignature.customer_email_is_required'));
        }

        // email and embed requests are different,
        // so we can't to continue (after breaking of embed request) from embed request to email
        if ($order->status_id === 'signature_pending' && $type === 'email') {
            throw new BusinessException(trans('exceptions.orders.document.esignature.status_is_signature_pending'));
        }

        if ($order->status_id === 'signed') {
            throw new BusinessException(trans('exceptions.orders.document.esignature.status_is_signed'));
        }
    }

    /**
     * Generate Embed Esignature Request for all signers, return initial FileSign for generate embeded iframe
     * initial = customer role for now
     * @param Order $order
     * @param File  $document
     * @param array $signer
     * @return array
     */
    public function createOrderEmbedEsignatures(
        Order $order,
        File $document,
        array $signer
    ): array {
        $signers = $this->getSignersByOrder($order);

        // check existed signer and try to complete previous request if exists,
        // if signer is INITIAL - we can generate new embed signatures
        $sign = $this->getFileSignByRole($document, $signer);
        if (!$sign && $signer['initial']) {
            $signatureRequest = $this->esignaturesService->createEmbedEsignatureRequest(
                'Order #' . $order->id,
                'Order by ' . $order->order_reference->customer_name,
                'Please sign this Order',
                $signers,
                $document
            );
            $this->addFileSignsToDocument($document, $signers, $signatureRequest, 'embed');
            $sign = $this->getFileSignByRole($document, $signer);
        }

        $embedResponse = $this->esignaturesService->getEmbeddedSignUrl($sign->esign_signature_id);

        $sign->esign_user_agent = request()->header('User-Agent');
        $sign->esign_ip_address = request()->ip();
        $sign->updated_at = date('Y-m-d H:i:s');
        $sign->save();

        return [
            'sign_url' => $embedResponse->getSignUrl(),
            'api_key' => $this->esignaturesService->apiKey,
            'client_id' => $this->esignaturesService->cliendID,
            'file_id' => $document->id,
            'order_id' => $order->id,
            'file_sign' => $sign,
            'signer' => $signer
        ];
    }

    /**
     * Generate Email Esignature for all signers, return some data for view
     * @param Order $order
     * @param File  $document
     * @param array $signer
     * @return array
     * @throws BusinessException
     */
    public function createOrderEmailEsignatures(Order $order,
                                                File $document,
                                                array $signer): array
    {
        $signers = $this->getSignersByOrder($order);

        // check existed signes and try to complete previous request if exists,
        // instead of create new
        $sign = $this->getFileSignByRole($document, $signer);

        // signature request by email can be cancelled
        if ($sign) {
            throw new BusinessException(trans('exceptions.orders.document.esignature.status_is_signature_pending'));
            /*
            try {
                $cancelled = $this->esignaturesService->cancelSignatureRequest($sign->esign_signature_request_id);
                if ($cancelled) $sign->delete();
            } catch (Exception $e) {
                if ($e->getCode() === 410) $sign->delete();
                throw new GeneralException($e->getMessage());
            }
            */
        }

        $signatureRequest = $this->esignaturesService->createEmailEsignatureOrderRequest(
            'Order #' . $order->id,
            'Please sign ' . config('app.name') . ' Order',
            null,
            $signers,
            $document
        );

        $signatureRequest = $this->esignaturesService->sendSignatureRequest($signatureRequest);
        $this->addFileSignsToDocument($document, $signers, $signatureRequest, 'email');
        return [
            'api_key' => $this->esignaturesService->apiKey,
            'client_id' => $this->esignaturesService->cliendID,
            'file_id' => $document->id,
            'file_sign' => $sign,
            'signer' => $signer
        ];
    }

    /**
     * Get signers list based on order payment type
     * Each signer will be used for SignatureRequest
     * @param Order $order
     * @return Collection
     */
    private function getSignersByOrder(Order $order): Collection {
        $signers = [];
        $signers[0] = [
            'role' => FileSign::CUSTOMER_ROLE,
            'id' => $order->order_reference->id,
            'email' => $order->order_reference->email,
            'name' => $order->order_reference->customer_name,
            'index' => 1,
            'initial' => true // there are no much sense to have signatures without inital signer
        ];

        if ($order->payment_type === 'rto') {
            $rtoCompany = RtoCompany::first();
            if (!$rtoCompany) return collect($signers);

            $signers[1] = [
                'role' => RtoCompany::SIGNER_ROLE,
                'id' => $rtoCompany->{COMPANY_ID},
                'email' => $rtoCompany->email,
                'name' => $rtoCompany->name,
                'index' => 2
            ];
        }

        return collect($signers);
    }

    /**
     * Get first file sign by inital signer
     * @param File  $file
     * @param array $signer
     * @return null
     * @internal param Collection $signers
     */
    private function getFileSignByRole(File $file, array $signer) {
        if ($signer) {
            $sign = $file->signs()
                ->where('signer_role', $signer['role'])
                ->where('is_esigned', false)
                ->first();

            return $sign;
        }

        return null;
    }

    /**
     * @param File             $document
     * @param Collection       $signers
     * @param SignatureRequest $signatureRequest
     * @param string           $requestType (embed or esign)
     */
    private function addFileSignsToDocument(File $document,
                                            Collection $signers,
                                            SignatureRequest $signatureRequest,
                                            string $requestType)
    {
        DB::transaction(function () use (
            $signatureRequest,
            $signers,
            $document,
            $requestType)
        {
            $signature_request_id = $signatureRequest->getId();

            foreach ($signatureRequest->getSignatures() as $signature) {
                $signer = $signers->first(function($signer) use($signature) {
                    return ($signer['email'] === $signature->getSignerEmail() || $signer['name'] === $signature->getSignerName());
                });
                $sign = new FileSign;
                $sign->signer_id = $signer['id'];
                $sign->signer_role = $signer['role'];
                $sign->request_type = $requestType;
                $sign->esign_signature_id = $signature->getId();
                $sign->esign_signature_request_id = $signature_request_id;
                $document->signs()->save($sign);
            }
        });
    }
}
