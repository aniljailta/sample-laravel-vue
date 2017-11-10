<?php

namespace App\Services\Orders;

use App\Models\Order;
use App\Models\OrderContact;
use App\Models\Setting;
use App\Repositories\OrderContactsRepository;
use Carbon\Carbon;

class OrderContactService
{
    protected $orderContactRepository;

    public function __construct()
    {
        $this->orderContactRepository = new OrderContactsRepository();
    }

    /**
     * @param $data
     * @return OrderContact
     */
    public function store($data): OrderContact
    {
        return $this->orderContactRepository->storeOrderContact($data);
    }

    /**
     * @param OrderContact $orderContact
     * @return OrderContact
     */
    public function save(OrderContact $orderContact): OrderContact
    {
        return $this->orderContactRepository->saveOrderContact($orderContact);
    }

    /**
     * Save order lead contacts - used in dealer order form with sales person
     * @param Order $order
     * @return OrderContact
     */
    public function saveOrderLeadContacts(Order $order): OrderContact
    {
        $orderContact = $this->orderContactRepository->getOrderContactByOrderId($order->id);
        $initialOrderContactData = [
            'order_id' => $order->id,
            'customer_id' => $order->customer_id
        ];

        // checking existed order contact: if existed and not older than period - update, if not - add new
        if ($orderContact) {
            $initialOrderContactData['initial_contact'] = $orderContact->initial_contact;
            $eligibility = Setting::where('id', 'initial_contact_eligibility')->pluck('value')->first();
            $eligibility = !empty($eligibility) ? $eligibility : 0;
            if (!empty($orderContact->order_submit)) {
                return $orderContact;
            } else {
                if ($order->status_id == 'submitted') {
                    $orderContact->order_id = $order->id;
                    $orderContact->order_submit = $order->sales_person;
                    return $this->save($orderContact);
                } else {
                    if (Carbon::now() < $orderContact->created_at->addDays($eligibility)) {
                        $orderContact->updated_at = Carbon::now();
                        return $this->save($orderContact);
                    } else {
                        $orderContact->created_at = Carbon::now();
                        $orderContact->initial_contact = $order->sales_person;
                        return $this->save($orderContact);
                    }
                }
            }
        }
        $initialOrderContactData['initial_contact'] = $order->sales_person;
        return $this->store($initialOrderContactData);
    }

    public function generateExportData($data): Array
    {
        $dataToExport = array();
        // retrieve expired date from created_at + initial_contact_eligibility
        $setting = new Setting();
        $eligibility = $setting
            ->where('id', 'initial_contact_eligibility')
            ->pluck('value')
            ->first();
        foreach ($data as $key => $value) {
            $dataToExport[ $key ]['created_at'] = (!empty($value['created_at'])) ? $value['created_at'] : '';
            $dataToExport[ $key ]['order_id'] = (!empty($value['order_id'])) ? $value['order_id'] : '';
            $dataToExport[ $key ]['customer_name'] = (!empty($value['customer'])) ? $value['customer']['first_name'] ." ". $value['customer']['last_name'] : '';
            $dataToExport[ $key ]['initial_contact'] = (!empty($value['initial_contact'])) ? $value['initial_contact'] : '';
            if (!empty($value['created_at'])) {
                $expired_at = Carbon::createFromTimestamp(strtotime($value['created_at']))->addDays($eligibility);
                $dataToExport[ $key ]['expired_at'] = $expired_at->toDateTimeString();
            }
            $dataToExport[ $key ]['order_submit'] = (!empty($value['order_submit'])) ? $value['order_submit'] : '';
            $dataToExport[ $key ]['total_price'] = (!empty($value['building']) && !empty($value['building']['total_price'])) ? $value['building']['total_price'] : '';
            $dataToExport[ $key ]['date_submitted'] = (!empty($value['order']) && !empty($value['order']['date_submitted'])) ? $value['order']['date_submitted'] : '';
        }
        return $dataToExport;

    }

    public function generateExportHeader() : Array {
        return array(
            'created_at' => 'Date',
            'order_id' => 'Order ID',
            'customer_name' => 'Customer Name',
            'initial_contact' => 'Initial Contact',
            'expired_at' => 'Expired At',
            'order_submit' => 'Order Submitted',
            'total_price' => 'Total Price',
            'date_submitted' => 'Date Submitted',
        );
    }
}
