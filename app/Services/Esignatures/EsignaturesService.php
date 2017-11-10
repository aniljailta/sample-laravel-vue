<?php

namespace App\Services\Esignatures;

use App\Exceptions\GeneralException;
use App\Models\File;

use HelloSign\EmbeddedResponse;
use HelloSign\SignatureRequest;
use HelloSign\FileResponse;
use Illuminate\Support\Collection;
use PDF;
use DB;
use stdClass;
use Storage;

class EsignaturesService
{
    // hellosign credentials
    public $apiKey;
    public $cliendID;

    /**
     * @var \HelloSign\Client
     */
    public $client;

    public function __construct()
    {
        $this->apiKey = config('hellosign.api_key');
        $this->cliendID = config('hellosign.client_id');
        $this->client = new \HelloSign\Client($this->apiKey);
    }

    /**
     * @return SignatureRequest
     */
    private function createSignatureRequest(): SignatureRequest {
        $signatureRequest = new \HelloSign\SignatureRequest;
        $signatureRequest->setClientId($this->cliendID);
        if (!app()->environment('production')) {
            $signatureRequest->enableTestMode();
        }
        return $signatureRequest;
    }

    /**
     * @param string     $title
     * @param string      $subject
     * @param string     $message
     * @param Collection|array $signers
     * @param File $file
     * @return SignatureRequest
     */
    public function createEmbedEsignatureRequest(string $title,
                                                 string $subject,
                                                 $message,
                                                 $signers,
                                                 File $file): SignatureRequest
    {
        // Create signature request
        $signatureRequest = $this->createSignatureRequest();
        $signatureRequest->setUseTextTags(true);
        $signatureRequest->setTitle($title);
        $signatureRequest->setSubject($subject);
        $signatureRequest->addFile($file->storage_path);
        if ($message) {
            $signatureRequest->setMessage($message);
        }

        // document can have multiple signers
        foreach ($signers as $signer) {
            $signatureRequest->addSigner($signer['email'], $signer['name'], $signer['index']);
        }

        $embedded_request = new \HelloSign\EmbeddedSignatureRequest($signatureRequest, $this->cliendID);
        $response = $this->client->createEmbeddedSignatureRequest($embedded_request);
        return $response;
    }

    /**
     * @param string $title
     * @param string $subject
     * @param string $message
     * @param        $signers
     * @param File   $file
     * @return SignatureRequest
     */
    public function createEmailEsignatureOrderRequest(string $title,
                                                      string $subject,
                                                      $message,
                                                      $signers,
                                                      File $file): SignatureRequest
    {
        // Create new signature request
        $signatureRequest = $this->createSignatureRequest();
        $signatureRequest->setUseTextTags(true);
        $signatureRequest->setTitle($title);
        $signatureRequest->setSubject($subject);
        $signatureRequest->addFile($file->storage_path);
        if ($message) {
            $signatureRequest->setMessage($message);
        }

        // document can have multiple signers
        foreach ($signers as $signer) {
            $signatureRequest->addSigner($signer['email'], $signer['name'], $signer['index']);
        }

        return $signatureRequest;
    }

    /**
     * @param string $signatureRequestId
     * @param string $path
     * @return bool|int
     * @throws GeneralException
     */
    public function downloadSignedDocument(string $signatureRequestId, $path = null) {
        // save file to path if path is provided
        $response = $this->client->getFiles($signatureRequestId, $path, \HelloSign\SignatureRequest::FILE_TYPE_PDF);

        // save file to string if path is not provided
        if (!$path) {
            if (!$response->getFileUrl()) {
                throw new GeneralException(trans('exceptions.orders.document.esignature.download_url_is_emtpy'));
            }

            $response = file_get_contents($response->getFileUrl());
        }

        return $response;
    }

    /**
     * @param string $signatureRequestId
     * @return SignatureRequest
     */
    public function getSignatureRequiest(string $signatureRequestId): SignatureRequest {
        $signatureRequest = $this->client->getSignatureRequest($signatureRequestId);
        return $signatureRequest;
    }

    /**
     * @param string $signatureRequestId
     * @return EmbeddedResponse
     */
    public function getEmbeddedSignUrl(string $signatureRequestId): EmbeddedResponse {
        $embeddedResponse = $this->client->getEmbeddedSignUrl($signatureRequestId);
        return $embeddedResponse;
    }

    /**
     * @param string $signatureRequestId
     * @return bool
     */
    public function cancelSignatureRequest(string $signatureRequestId): bool {
        $response = $this->client->cancelSignatureRequest($signatureRequestId);
        return $response;
    }

    /**
     * @param SignatureRequest $signatureRequest
     * @return SignatureRequest
     */
    public function sendSignatureRequest(SignatureRequest $signatureRequest): SignatureRequest {
        $signatureRequest = $this->client->sendSignatureRequest($signatureRequest);
        return $signatureRequest;
    }
}
