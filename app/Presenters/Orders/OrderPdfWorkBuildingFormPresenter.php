<?php
namespace App\Presenters\Orders;

use Hemp\Presenter\Presenter;

class OrderPdfWorkBuildingFormPresenter extends Presenter
{
    public function getTxtGrossBuydownAttribute(){
        return '';
    }

    public function getTxtTotalSalesPriceAttribute(){
        return '$' . number_format($this->total_sales_price, 2);
    }

    public function getTxtTotalOptionsAttribute(){
        return '$' . number_format($this->total_options, 2);
    }

    public function getTxtSalesTaxRateAttribute(){
        return '';
    }

    public function getTxtSalesTaxAttribute(){
        return '';
    }

    public function getTxtSecurityDepositAttribute(){
        return '';
    }

    public function getTxtTotalAmountAttribute(){
        return '';
    }

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
        return '';
    }

    public function getTxtRtoAmountAttribute(){
        return '';
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
}