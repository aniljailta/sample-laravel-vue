<?php

namespace App\Jobs\Orders;

use App\Exceptions\BusinessException;
use App\Models\FileSign;
use App\Models\Order;
use App\Models\Company;
use App\Services\Esignatures\EsignaturesService;
use App\Services\Files\FileService;
use App\Notifications\Dealers\OrderAllSigned;

use App\Jobs\Job;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Store;
use Storage;
use Exception;

class DownloadOrderEsignedDocuments extends Job implements ShouldQueue
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
     * @param FileService        $fileService
     * @return void
     * @throws Exception
     */
    public function handle(EsignaturesService $esignaturesService, FileService $fileService)
    {
        $this->applyTenantScope($this->company);

        $fileSign = $this->fileSign;
        $order = $this->order;
        $file = $fileSign->file;
        
        $filePath = storage_path('app/public')."/{$file->storable_type}/{$file->storable_id}/";
        $fileName = pathinfo($file->storage_path, PATHINFO_FILENAME);
        $fileExt = pathinfo($file->storage_path, PATHINFO_EXTENSION);
        $fullPath = $filePath.$fileName.'_signed.'.$fileExt;

        try {
            if ($file->category_id === 'complete_order_documents') {
                $storable['category_id'] = 'e_signed_order_documents';
            }

            $storable['user_id'] = $file->user_id; // Auth::user()->id,
            $storable['type'] = $file->storable_type;
            $storable['id'] = $file->storable_id;
            $storable['key'] = $file->storable_id;
            $storable['name'] = $fileName.'_signed.'.$fileExt;
            $storable['ext'] = $file->ext;
            $storable['description'] = "E-Sign for {$file->storable_type} #{$file->storable_id} Electronic Signature Request ID: {$fileSign->esign_signature_request_id}";

            \File::isDirectory($filePath) or \File::makeDirectory($filePath);

            $esignaturesService->downloadSignedDocument($fileSign->esign_signature_request_id, $fullPath);

            $fileService->add($fullPath, $storable);
            if ($fileService->error()) {
                throw new BusinessException('Unable to save file.');
            }

            $order->status_id = 'signed';
            $order->save();
            $order->dealer->notify(new OrderAllSigned($order));

            // remove unsigned document (old)
            $deleted = Storage::delete('public/'.$file->path);
            if ($deleted) $file->delete();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
