<?php
namespace App\Listeners\Orders;

use App\Events\Orders\OrderCancelled;
use App\Mail\Orders\OrderCancelled as OrderCancelledEmail;
use Illuminate\Mail\Mailer;

class OrderCancelledListener
{
    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * OrderCancelledListener constructor.
     *
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param OrderCancelled $event
     */
    public function handle(OrderCancelled $event)
    {
        $this->mailer->send(new OrderCancelledEmail($event->getOrder()));
    }
}