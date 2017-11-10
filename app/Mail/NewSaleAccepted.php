<?php

namespace App\Mail;

use Store;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Sale;

class NewSaleAccepted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The dealer and order reference instance.
     *
     * @var Dealer
     * @var Order
     * @array
     */
    public $dealer;
    public $order;
    public $orderReference;
    public $companySettings;

    /**
     * Create a new message instance.
     *
     * @param Sale $sale
     */
    public function __construct(Sale $sale)
    {
        $this->order = $sale->order;
        $this->dealer = $sale->order->dealer;
        $this->orderReference = $sale->order->order_reference;
        $this->companySettings = Store::get('company_settings');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->to($this->orderReference->email, $this->orderReference->customer_name);
        $this->cc($this->dealer->email, $this->dealer->business_name);
        $this->subject('Your order has been received and processed.');

        if ($this->order->sale_type === 'dealer-inventory') {
            return $this->view('emails.sales.accepted-dealer-inventory')->with('companySettings', $this->companySettings);
        }

        if ($this->order->sale_type === 'custom-order') {
            return $this->view('emails.sales.accepted-custom-order')->with('companySettings', $this->companySettings);
        }
    }
}
