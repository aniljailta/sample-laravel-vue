<?php

namespace App\Mail\Customers;

use Store;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderReference;
use App\Models\RtoCompany;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderSigned extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The dealer and order reference instance.
     *
     * @var User
     * @var Order
     * @var OrderReference
     * @var RtoCompany
     * @array
     */
    public $customer;
    public $order;
    public $orderReference;
    public $rtoCompany;
    public $document;
    public $companySettings;

    /**
     * Create a new message instance.
     *
     * @param Order $order
     * @param array $document
     */
    public function __construct(Order $order, array $document = [])
    {
        $this->customer = $order->customer;
        $this->order = $order;
        $this->orderReference = $order->order_reference;
        $this->rtoCompany = RtoCompany::first();
        $this->document = $document;
        $this->companySettings = Store::get('company_settings');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->to($this->customer->email, $this->customer->full_name);
        $this->subject('Congratulations on the purchase of your shed from ' . config('app.name'));
        $this->attachData($this->document['file_content'], $this->document['name'], $this->document['options']);

        return $this->view('emails.customers.order-was-signed')->with('companySettings', $this->companySettings);
    }
}
