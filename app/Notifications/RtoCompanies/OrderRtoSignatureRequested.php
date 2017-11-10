<?php

namespace App\Notifications\RtoCompanies;

use App\Models\Order;
use App\Models\FileSign;
use App\Notifications\DefaultNotificationChannel;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderRtoSignatureRequested extends Notification
{
    use Queueable;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var FileSign
     */
    private $fileSign;

    public $type = 'order_rto_signature_requested';

    public function __construct(Order $order, FileSign $fileSign)
    {
        $this->order = $order;
        $this->fileSign = $fileSign;
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
     * @param mixed $rtoCompany
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($rtoCompany)
    {
        $order = $this->order;
        $fileSign = $this->fileSign;

        return (new MailMessage())
            ->subject("There is an RTO contract awaiting your review and signature (Order ID: # {$order->id})")
            ->line("Hello {$rtoCompany->name}!")
            ->line("There is an RTO contract awaiting your review and signature.")
            ->action("Please click here to begin the e-signature process", route('esign-order-by-signature-id', [
                'order_uuid' => $order->uuid,
                'signature_id' => $fileSign->esign_signature_id
                ])
            );
    }

    public function toDatabase($notifable) {
        return [
            'order_uuid' => $this->order->uuid
        ];
    }
}
