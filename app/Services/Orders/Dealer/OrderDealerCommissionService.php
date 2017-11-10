<?php

namespace App\Services\Orders\Dealer;

use App\Models\DealerCommission;
use App\Models\Order;
use Auth;

class OrderDealerCommissionService
{
    /**
     * @param Order $order
     * @return DealerCommission
     */
    public function applyCommissionToOrder(Order $order): DealerCommission {
        $commissionRate = $order->dealer_commission_rate;
        $totalPrice = $order->building->total_price;

        // get dealer discount amount by options
        $dealerDiscountAmount = $order->options()
            ->whereHas('option', function($q) {
                $q->where('name', 'Dealer Commission Discount');
            })
            ->sum('total_price');

        $dealerCommission = new DealerCommission;
        $dealerCommission->commission_rate = $commissionRate;
        $dealerCommission->order_id = $order->id;
        $dealerCommission->status = DealerCommission::INITIAL_STATUS_ID;
        $dealerCommission->user_id = Auth::user()->id;
        $dealerCommission->dealer_id = $order->dealer_id;
        $dealerCommission->dealer_discount = $dealerDiscountAmount;
        $dealerCommission->amount_due = ($commissionRate / 100) * $totalPrice - $dealerCommission->dealer_discount;
        $dealerCommission->save();
        return $dealerCommission;
    }

    /**
     * Recalculate specified dealer commission attribute
     * @param DealerCommission $dealerCommission
     * @param string $attribute
     * @return DealerCommission
     */
    public function recalculateAttribute(DealerCommission $dealerCommission, string $attribute): DealerCommission {
        if ($attribute === 'amount_due') {
            $dealerCommission->amount_due = ($dealerCommission->commission_rate / 100) * $dealerCommission->order->building->total_price - $dealerCommission->dealer_discount;
            $dealerCommission->amount_due = (float) number_format($dealerCommission->amount_due, 2, '.', '');
        }

        if ($attribute === 'commission_rate') {
            $dealerCommission->commission_rate = ($dealerCommission->amount_due + $dealerCommission->dealer_discount) / $dealerCommission->order->building->total_price * 100;
            $dealerCommission->commission_rate = (float) number_format($dealerCommission->commission_rate, 2, '.', '');
        }

        return $dealerCommission;
    }
}
