<?php

namespace App\Jobs\Orders;

use App\Models\FileSign;
use App\Models\Order;
use App\Models\RtoCompany;
use App\Services\Esignatures\EsignaturesService;
use App\Services\Orders\OrderPdfService;
use App\Notifications\Customers\OrderSigned;
use App\Events\Orders\CustomerOrderDocumentsWasSent;

use App\Jobs\Job;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Store;

class SendOrderDocumentsToCustomer extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $queue;

    /**
     * @var Company
     */
    public $company;

    /**
     * @var Order
     */
    public $order;

    /**
     * @var FileSign
     */
    public $fileSign;

    /**
     * Create the job instance.
     * @param Order    $order
     * @param FileSign $fileSign
     */
    public function __construct(Order $order, FileSign $fileSign)
    {
        $this->company = Store::get('company');
        $this->order = $order;
        $this->fileSign = $fileSign;
    }

    /**
     * Execute the job.
     *
     * @param EsignaturesService $esignaturesService
     * @param OrderPdfService    $orderPdfService
     * @return void
     */
    public function handle(EsignaturesService $esignaturesService, OrderPdfService $orderPdfService)
    {
        $this->applyTenantScope($this->company);

        $order = $this->order;

        $notified = $order->customer->notifications()
            ->where('order_uuid', $this->order->uuid)
            ->where('type', OrderSigned::TYPE)
            ->exists();

        if (!$notified) {
            $document = $esignaturesService->downloadSignedDocument($this->fileSign->esign_signature_request_id);
            $order->customer->notify(new OrderSigned($order, [
                'file_content' => $document,
                'name' => $orderPdfService->getOrderDocumentNameByCategory($order, 'complete_order_documents'),
                'options' => [
                    'mime' => 'application/pdf'
                ]
            ]));
        }

        // Send email to RTO company if payment method is RTO
        if ($order->payment_type === 'rto' && $order->rto_company) {
            $rtoFileSign = FileSign::where('esign_signature_request_id', $this->fileSign->esign_signature_request_id)
                ->where('signer_role', RtoCompany::SIGNER_ROLE)
                ->where('is_esigned', false)
                ->first();
            if (!$rtoFileSign) return true;

            $job = new SendOrderDocumentsToRtoCompany($order, $rtoFileSign);
            dispatch($job->delay(1)->onQueue('default'));
        }
    }
}
