<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\User;
use Auth;

class CustomerObserver
{
    /**
     * Listen to the Customer creatig event.
     *
     * @param  Customer  $customer
     * @return void
     */
    public function creating(Customer $customer)
    {
        if ($customer->email) {
            $user = $this->getUserAccount($customer);
            $user->email = $customer->email;
            $user->first_name = $customer->first_name;
            $user->last_name = $customer->last_name;
            $user->phone = $customer->phone;
            $user->save();
            $customer->user()->associate($user);
        }
    }

    /**
     * Listen to the Customer updating event.
     *
     * @param  Customer  $customer
     * @return void
     */
    public function updating(Customer $customer)
    {
        if ($customer->email && $customer->isDirty(['email', 'first_name', 'last_name', 'phone'])) {
            $user = $customer->user;
            if (!$user) {
                $user = $this->getUserAccount($customer);
            }

            $user->email = $customer->email;
            $user->first_name = $customer->first_name;
            $user->last_name = $customer->last_name;
            $user->phone = $customer->phone;
            $user->save();
        }
    }

    /**
     * Listen to the Customer deleting event.
     *
     * @param  Customer  $customer
     * @return void
     */
    public function deleting(Customer $customer)
    {
    }

    /**
     * @param Customer $customer
     * @return User
     */
    private function getUserAccount(Customer $customer): User {
        return User::firstOrNew([
            COMPANY_ID => $customer->{MANUFACTURER_COMPANY_ID},
            'email' => $customer->email,
        ]);
    }
}