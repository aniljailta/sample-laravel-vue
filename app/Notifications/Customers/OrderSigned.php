<?php

namespace App\Notifications\Customers;

use App\Models\Order;
use App\Mail\Customers\OrderSigned as OrderSignedByCustomerMail;
use App\Notifications\DefaultNotificationChannel;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderSigned extends Notification
{
    use Queueable;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var array
     */
    private $document;

    public $type = self::TYPE;

    CONST TYPE = 'customer_order_signed';

    public function __construct(Order $order, array $document = [])
    {
        $this->order = $order;
        $this->document = $document;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', DefaultNotificationChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $user
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($user)
    {
        return new OrderSignedByCustomerMail($this->order, $this->document);
    }

    public function toDatabase($notifable) {
        return [
            'order_uuid' => $this->order->uuid
        ];
    }
}
