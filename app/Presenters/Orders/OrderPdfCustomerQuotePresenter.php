<?php
namespace App\Presenters\Orders;

use Hemp\Presenter\Presenter;

class OrderPdfCustomerQuotePresenter extends Presenter
{
    public function getTxtDepositAmountAttribute(){
        return '';
    }

    public function getTxtDepositReceivedAttribute(){
        return '';
    }

    public function getTxtNetBuydownAttribute(){
        return '';
    }

    public function getTxtBuydownTaxAttribute(){
        return '';
    }

    public function getTxtRtoNetBuydownAttribute(){
        return '';
    }

    public function getTxtRtoSalesTaxAttribute(){
        return '$' . number_format($this->rto_sales_tax, 2);
    }

    public function getTxtRtoAmountAttribute(){
        return '$' . number_format($this->rto_amount, 2);
    }

    public function getTxtRtoAdvanceMonthlyRenewalPaymentAttribute(){
        return '';
    }

    public function getTxtRtoTotalAdvanceMonthlyRenewalPaymentAttribute(){
        return '';
    }

    public function getTxtRtoTotalDaysAdvanceMonthlyRenewalPaymentAttribute(){
        return '';
    }

    /**
     * @return string
     */
    public function getOrderDateAttribute(){
        if (!$this->model->order_date) return null;
        return Carbon::createFromFormat('Y-m-d', $this->model->order_date)->format('m/d/Y');
    }

    /**
     * @return string
     */
    public function getCedStartAttribute(){
        if (!$this->model->ced_start) return null;
        return Carbon::createFromFormat('Y-m-d', $this->model->ced_start)->format('m/d/Y');
    }

    /**
     * @return string
     */
    public function getCedEndAttribute(){
        if (!$this->model->ced_end) return null;
        return Carbon::createFromFormat('Y-m-d', $this->model->ced_end)->format('m/d/Y');
    }

    /**
     * @return string
     */
    public function getTxtTotalOptionsAttribute(){
        return '$' . number_format($this->total_options, 2);
    }

    /**
     * @return string
     */
    public function getTxtGrossBuydownAttribute(){
        return '$' . number_format($this->gross_buydown, 2);
    }

    /**
     * @return string
     */
    public function getTxtTotalSalesPriceAttribute(){
        return '$' . number_format($this->total_sales_price, 2);
    }

    /**
     * @return string
     */
    public function getTxtTaxRateAttribute(){
        $taxRate = $this->tax_rate;
        return number_format($taxRate, 2) . '%';
    }

    /**
     * @return string
     */
    public function getTxtSalesTaxRateAttribute(){
        $taxRate = $this->sales_tax_rate;
        return number_format($taxRate, 2) . '%';
    }

    /**
     * @return string
     */
    public function getTxtChangeOrderFeeAttribute(){
        $changeOrderFee = $this->change_order_fee;
        return '$' . number_format($changeOrderFee, 2);
    }

    /**
     * @return string
     */
    public function getTxtSalesTaxAttribute(){
        return '$' . number_format($this->sales_tax, 2);
    }

    /**
     * @return string
     */
    public function getTxtSecurityDepositAttribute(){
        return '$' . number_format($this->security_deposit, 2);
    }
    /**
     * @return string
     */
    public function getTxtPoDepositAmountAttribute(){
        return '$' . number_format($this->po_deposit_amount, 2);
    }

    /**
     * @return string
     */
    public function getTxtTotalOrderAttribute(){
        return '$' . number_format($this->total_order, 2);
    }

    /**
     * @return string
     */
    public function getTxtTotalAmountAttribute(){
        return '$' . number_format($this->total_amount, 2);
    }

    /**
     * @return string
     */
    public function getTxtTotalDepositAmountDueAttribute(){
        return '$' . number_format($this->total_deposit_amount_due, 2);
    }

    /**
     * @return string
     */
    public function getTxtBalanceDueAttribute(){
        return '$' . number_format($this->balance_due, 2);
    }

    /**
     * @return string
     */
    public function getTxtAmountReceivedAttribute(){
        return '$' . number_format($this->amount_received, 2);
    }
}