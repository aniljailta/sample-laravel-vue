<?php

namespace App\Listeners;

use App\Exceptions\BusinessException;
use Storage;
use Log;
use App\Events\FileWasSigned;
use App\Events\FileWasSignedByCustomer;

class UpdateFileSign
{

    /**
     * Create the event listener.
     */
    public function __construct(){}

    /**
     * Handle the event.
     * @param FileWasSigned $event
     * @return bool
     * @throws BusinessException
     */
    public function handle(FileWasSigned $event)
    {
        $fileSign = $event->fileSign;
        $fileSign->is_esigned = true;
        $fileSign->esigned_on = date('Y-m-d H:i:s');
        $fileSign->save();

        if ($fileSign->signer_role === 'customer') {
            event(new FileWasSignedByCustomer($event->order, $fileSign));
        }
    }
}
