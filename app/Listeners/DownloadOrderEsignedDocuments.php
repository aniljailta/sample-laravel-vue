<?php

namespace App\Listeners;

use App\Jobs\Orders\DownloadOrderEsignedDocuments as DownloadOrderEsignedDocumentsJob;
use App\Events\FileWasAllSigned;

class DownloadOrderEsignedDocuments
{

    /**
     * Create the event listener
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     * @param FileWasAllSigned $event
     * @return bool
     * @throws BusinessException
     */
    public function handle(FileWasAllSigned $event)
    {
        $job = new DownloadOrderEsignedDocumentsJob($event->order, $event->fileSign);
        dispatch($job->onQueue('default'));
    }
}
