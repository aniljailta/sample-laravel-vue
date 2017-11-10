<?php

namespace App\Services\Customers;

use App\Exceptions\GeneralException;
use App\Exceptions\BusinessException;
use App\Models\User;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Role;

class CustomerService
{
    public function __construct()
    {
    }

    /**
     * Add new user-customer attach attach role 'customer' to existed
     * @param Order $order
     * @return Customer
     * @throws GeneralException
     */
    public function createCustomerFromOrder(Order $order): Customer
    {
        try {
            $customerBasis = [];
            $customerBasis[MANUFACTURER_COMPANY_ID] = $order->{MANUFACTURER_COMPANY_ID};

            $dealer = $order->dealer()->withTrashed()->first();
            // use email for basis only if value is not dealer email
            // otherwise we can use person name
            if (!$order->order_reference->email || $dealer->email === $order->order_reference->email) {
                $customerBasis['email'] = null;
                $customerBasis['first_name'] = $order->order_reference->first_name;
                $customerBasis['last_name'] = $order->order_reference->last_name;
            } else {
                $customerBasis['email'] = $order->order_reference->email;
            }

            $customer = Customer::firstOrNew($customerBasis);
            $customerBasis['first_name'] = $order->order_reference->first_name ?: $customer->first_name;
            $customerBasis['last_name'] = $order->order_reference->last_name ?: $customer->last_name;
            $customerBasis['phone'] = $order->order_reference->phone_number ?: $customer->phone;
            $customerBasis['address'] = $order->order_reference->address ?: $customer->address;
            $customerBasis['city'] = $order->order_reference->city ?: $customer->city;
            $customerBasis['state'] = $order->order_reference->state ?: $customer->state;
            $customerBasis['zip'] = $order->order_reference->zip ?: $customer->zip;
            $customer->fill($customerBasis);
            $customer->save();

            if ($customer->user) {
                $this->attachCustomerRole($customer->user);
            }

            return $customer;
        } catch (Exception $e) {
            throw new GeneralException(trans('exceptions.customers.unable_to_save_customer_from_order'));
        }
    }

    /**
     * @param User $user
     * @return User
     */
    private function attachCustomerRole(User $user): User {
        if (!$user->hasRole('customer')) {
            $customerRole = Role::where('name', 'customer')->firstOrFail();
            $user->save();
            $user->roles()->attach($customerRole);
        }

        return $user;
    }
}
