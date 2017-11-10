<?php

namespace App\Notifications\Dealers;

use Store;
use App\Models\Company;
use App\Models\Order;
use App\Notifications\Notification;
use App\Notifications\DefaultNotificationChannel;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderAllSigned extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var Company
     */
    private $company;

    public $type = 'order_all_signed';

    public function __construct(Order $order)
    {
        $this->company = Store::get('company');
        $this->order = $order;
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
     * @param mixed $dealer
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($dealer)
    {
        $this->applyTenantScope($this->company);

        $order = $this->order;
        return (new MailMessage())
            ->subject("The Order for {$order->order_reference->customer_name}, (Order ID: # {$order->id}) has been signed")
            ->line("Hello {$dealer->business_name},")
            ->line("The Order for {$order->order_reference->customer_name}, (Order ID: # {$order->id}) has been signed by all parties and is ready to be submitted. 
            Please submit at your earliest convenience at the " . config('app.name') . " dealer portal.");
    }

    public function toDatabase($notifable) {
        return [
            'order_uuid' => $this->order->uuid
        ];
    }
}
