<?php

namespace App\Listeners\Orders;

use App\Jobs\Orders\SendOrderDocumentsToCustomer as SendOrderDocumentsToCustomerJob;
use App\Events\FileWasSignedByCustomer;
use App\Models\FileSign;
use App\Models\Order;

class SendOrderDocumentsToCustomer
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     * @param FileWasSignedByCustomer $event
     * @return bool
     */
    public function handle(FileWasSignedByCustomer $event)
    {
        $order = $event->order;
        $fileSign = $event->fileSign;
        if (!$order->customer) return true;

        if ($order->payment_type === 'rto') {
            $this->setOrderStatus($order);
        }

        $job = new SendOrderDocumentsToCustomerJob($order, $fileSign);
        dispatch($job->delay(10)->onQueue('default'));
    }

    /**
     * @param Order $order
     */
    private function setOrderStatus(Order $order): void
    {
        $order->status_id = 'signed';
        $order->save();
    }
}
