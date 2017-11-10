<?php

namespace App\Services\Orders;

use App\Models\Order;

class OrderCalculator
{

    protected $order;
    protected $dealer;
    protected $building;

    /**
     * OrderCalculator constructor.
     */
    public function __construct(){}

    /**
     * @param Order $model
     * @return OrderCalculator
     */
    public function setOrder(Order $model): OrderCalculator
    {
        $this->order = $model;
        return $this;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @return OrderCalculator
     */
    protected function calculateDealer(): OrderCalculator {
        $this->dealer->commission_rate = (float) $this->dealer->commission_rate;
        $this->dealer->tax_rate = (float) $this->dealer->tax_rate;
        $this->dealer->tax_factor = (float) $this->dealer->tax_rate / 100;
        return $this;
    }

    /**
     * Calculate all order properties
     * @return OrderCalculator
     */
    public function calculateOrder(): OrderCalculator {
        // formatting dealer taxes
        $this->calculateDealer();
        $this->calculateTaxRate();

        $this->calculateTotalSalesPrice();
        $this->calculateTotalOrderOptions();
        $this->calculateSecurityDeposit();
        $this->calculateRtoDeposit();
        $this->calculateSalesTax();

        // Deposits
        $this->calculateMfDepositReceived();
        $this->calculateRtoDepositReceived();

        $this->calculatePurchaseOutrightDepositAmount();
        $this->calculateMinAmountToApplyToOrder();

        // RTO calculation
        if ($this->order->payment_type === 'rto') {
            $this->calculateRtoPayments($this->order);
        }

        $this->order->rto_amount = $this->calculateRtoAmount($this->order);
        $this->calculateMfDepositAmountDue();
        $this->calculateTotalDepositAmountDue();
        $this->calculateTotalOrder();
        $this->calculateTotalAmount();
        $this->calculateBalanceDue();

        return $this;
    }

    /**
     * Calculate total sales price (@totalBuildingPrice)
     * @return float
     */
    protected function calculateTotalSalesPrice() {
        // total price = shell price + total option price
        $this->order->total_sales_price = $this->building->total_price;
        return $this->order->total_sales_price;
    }

    /**
     * Calculate totals order options (@totalTaxableOptions, @totalNonTaxableOptions)
     * @return float
     */
    protected function calculateTotalOrderOptions() {
        $totalTaxableOptions = 0;
        $totalNonTaxableOptions = 0;
        $totalRtoOptions = 0;
        $totalNonRtoOptions = 0;
        $totalOrderOptions = 0;
        $totalRtoDepositOptions = 0;

        foreach ($this->order->options as $option) {
            $totalOrderOptions += $option->total_price;

            if ($option->option->taxable)
                $totalTaxableOptions += $option->total_price;
            else
                $totalNonTaxableOptions += $option->total_price;

            if ($option->option->rto)
                $totalRtoOptions += $option->total_price;
            else
                $totalNonRtoOptions += $option->total_price;

            if ($option->option->rto_deposit)
                $totalRtoDepositOptions += $option->total_price;
        }

        $this->order->total_options = (float) number_format($totalOrderOptions, 2, '.', '');
        $this->order->total_taxable_options = (float) number_format($totalTaxableOptions, 2, '.', '');
        $this->order->total_non_taxable_options = (float) number_format($totalNonTaxableOptions, 2, '.', '');
        $this->order->total_rto_options = (float) number_format($totalRtoOptions, 2, '.', '');
        $this->order->total_non_rto_options = (float) number_format($totalNonRtoOptions, 2, '.', '');
        $this->order->total_rto_deposit_options = (float) number_format($totalRtoDepositOptions, 2, '.', '');
        return $this;
    }

    /**
     * Calculate tax and factor, can be dealer|sales (@taxRate, @taxFactor)
     * @return float
     */
    protected function calculateTaxRate() {
        // override dealer's tax rate if specified
        if ($this->order->sales_tax_rate !== null ) {
            $taxRate = $this->order->sales_tax_rate;
        } else {
            $taxRate = $this->dealer->tax_rate;
        }

        $this->order->tax_rate = (float) $taxRate;
        $this->order->tax_factor = (float) $taxRate / 100;
        return $this;
    }

    /**
     * Calculate order security deposit (@securityDeposit)
     */
    protected function calculateSecurityDeposit() {
        $building = $this->building;
        $promo99 = $this->order->promo99;
        $width = $building->width;

        if ($promo99)
            $securityDeposit = 99;
        else {
            if ($width <= 8) $securityDeposit = 150;
            if ($width > 8 && $width <= 10) $securityDeposit = 200;
            if ($width > 10 && $width <= 12) $securityDeposit = 250;
            if ($width > 12 && $width <= 14) $securityDeposit = 300;
        }

        $securityDeposit = $securityDeposit ?? 0;
        $securityDeposit = (float) number_format($securityDeposit, 2, '.', '');

        $this->order->security_deposit = $securityDeposit;
        return $securityDeposit;
    }

    /**
     * Calculate order rto deposit (@rtoDeposit)
     */
    protected function calculateRtoDeposit() {
        $securityDeposit = $this->order->security_deposit;
        $totalRtoDepositOptions = $this->order->total_rto_deposit_options;

        $rtoDeposit = $securityDeposit;
        $rtoDeposit += $totalRtoDepositOptions;
        $rtoDeposit = (float) number_format($rtoDeposit, 2, '.', '');

        $this->order->rto_deposit = $rtoDeposit;
        return $rtoDeposit;
    }

    /**
     * Calculate sales tax (@salesTax)
     * @return float
     * @internal param null $totalBuildingPrice
     */
    protected function calculateSalesTax() {
        $totalBuildingPrice = $this->order->total_sales_price;
        $totalTaxableOptions = $this->order->total_taxable_options;
        $taxFactor = $this->order->tax_factor;

        $salesTax = ($totalBuildingPrice + $totalTaxableOptions) * $taxFactor;
        $salesTax = (float) number_format($salesTax, 2, '.', '');
        $this->order->sales_tax = $salesTax;

        return $salesTax;
    }

    /**
     * Calculate NetBuydown (used for RTO, @netBuydown)
     * @return float
     */
    protected function calculateNetbuydown() {
        $paymentType = $this->order->payment_type;
        if ($paymentType !== 'rto') return 0;

        $grossBuydown = $this->calculateGrossBuydown();
        $minRtoDepositAmount = $this->calculateMinRtoDepositAmount('no-buydown');
        $taxFactor = $this->order->tax_factor;
        $netBuydownWTax = 0;

        if ($grossBuydown >= $minRtoDepositAmount) {
            $netBuydownNoTax = $grossBuydown - $minRtoDepositAmount;
            $netBuydownWTax = $netBuydownNoTax / (1 + $taxFactor);
            $buydownTax = $netBuydownNoTax - $netBuydownWTax;

            $this->order->net_buydown = (float) number_format($netBuydownNoTax, 2, '.', '');
            $this->order->buydown_tax = (float) number_format($buydownTax, 2, '.', '');
        }

        $netBuydownWTax = (float) number_format($netBuydownWTax, 2, '.', '');
        $this->order->rto_net_buydown = $netBuydownWTax;
        return $netBuydownWTax;
    }

    /**
     * Calculate RTO Amount (@rtoAmount)
     * @param Order $order
     * @return float
     */
    public function calculateRtoAmount(Order $order): float {
        $netBuydown = $this->calculateNetbuydown(); // netbuydown with tax
        $totalBuildingPrice = $order->total_sales_price;
        $totalRtoOptions = $order->total_rto_options;

        $rtoAmount = $totalBuildingPrice + $totalRtoOptions;
        if ($netBuydown > 0) {
            $rtoAmount -= $netBuydown;
        }

        $rtoAmount = (float) number_format($rtoAmount, 2, '.', '');
        return $rtoAmount;
    }

    /**
     * Calculate RTO Payments
     * @param Order $order
     * @param string $rtoType
     * @return Order $order
     */
    protected function calculateRtoPayments(&$order, $rtoType = 'buydown'): Order {
        if (!$this->order->rto_term_params) return $order;
        $rtoType = $rtoType ?? $order->rto_type;

        $rtoFactor = (float) $this->order->rto_term_params['rto_factor'];
        $rtoValue = (float) $this->order->rto_term_params['value'];
        $taxFactor = $this->order->tax_factor;

        if ($rtoType === 'no-buydown')
            $rtoAdvanceMonthlyRenewalPayment = ($order->total_sales_price + $this->order->total_rto_options) / $rtoFactor; // RTO payment w tax (reference to g.docs)
        else
            if ($rtoType === 'buydown') {
                $rtoAmount = $this->calculateRtoAmount($order);
                $rtoAdvanceMonthlyRenewalPayment = $rtoAmount / $rtoFactor;
            } // RTO payment w tax & buydown (reference to g.docs)
            else
                return $order;

        // RTO payment w/without buydown (reference to g.docs, common)
        $rtoSalesTax = $rtoAdvanceMonthlyRenewalPayment * $taxFactor;
        $rtoTotalAdvanceMonthlyRenewalPayment = $rtoAdvanceMonthlyRenewalPayment + $rtoSalesTax;

        $order->rto_advance_monthly_renewal_payment = (float) number_format($rtoAdvanceMonthlyRenewalPayment, 2, '.', '');
        $order->rto_sales_tax = (float) number_format($rtoSalesTax, 2, '.', '');
        $order->rto_total_advance_monthly_renewal_payment = (float) number_format($rtoTotalAdvanceMonthlyRenewalPayment, 2, '.', '');
        $order->rto_total_days_advance_monthly_renewal_payment = $rtoTotalAdvanceMonthlyRenewalPayment * $rtoValue;
        $order->rto_total_days_advance_monthly_renewal_payment = (float) number_format($order->rto_total_days_advance_monthly_renewal_payment, 2, '.', '');
        return $order;
    }

    /**
     * Calculate purchase outright deposit amount (@purchaseOutrightDepositAmount)
     * @return float $purchaseOutrightDepositAmount
     */
    protected function calculatePurchaseOutrightDepositAmount() {
        $totalPurchase = $this->calculateTotalPurchase();
        $dealer = $this->dealer;

        if ($dealer->cash_sale_deposit_rate !== null)
            $cashSaleDepositFactor = $dealer->cash_sale_deposit_rate / 100;
        else
            $cashSaleDepositFactor = 1;

        $depositAmount = $totalPurchase * $cashSaleDepositFactor;
        $depositAmount = (float) number_format($depositAmount, 2, '.', '');

        $this->order->po_deposit_amount = $depositAmount;
        return $depositAmount;
    }

    /**
     * Calculate min rto deposit amount (@minRtoDepositAmount)
     * @param string $rtoType
     * @return null|float $minRtoDepositAmount
     */
    protected function calculateMinRtoDepositAmount($rtoType = 'buydown') {
        $paymentType = $this->order->payment_type;
        if ($paymentType !== 'rto') return 0;

        $promo99 = $this->order->promo99;
        $rtoDeposit = $this->order->rto_deposit;

        if ($promo99)
            $minRtoDepositAmount = $rtoDeposit;
        else {
            // Calculate RTO payment w tax
            // reference to g.docs (no-buydown)
            $tmpOrder = $this->order->replicate();
            $tmpOrder = $this->calculateRtoPayments($tmpOrder, $rtoType);
            $rtoPaymentWtax = $tmpOrder->rto_total_advance_monthly_renewal_payment;

            $minRtoDepositAmount = $rtoDeposit + $rtoPaymentWtax;
        }

        $minRtoDepositAmount = $minRtoDepositAmount ?? 0;
        $minRtoDepositAmount = (float) number_format($minRtoDepositAmount, 2, '.', '');
        return $minRtoDepositAmount;
    }

    /**
     * Calculate Amount to Apply to Order (Minimum) (@minAmountApplyAmount, required for customer input)
     * @return float
     */
    protected function calculateMinAmountToApplyToOrder() {
        $changeOrderFee = $this->order->change_order_fee;
        $paymentType = $this->order->payment_type;

        $minAmountToApply = 0;
        $minRtoDepositAmount = $this->calculateMinRtoDepositAmount();
        $mfDepositAmountDue = $this->calculateMfDepositAmountDue();

        if ($paymentType === 'cash') {
            $minAmountToApply = $mfDepositAmountDue;
        }

        if ($paymentType === 'rto') {
            $rtoDepositAmountDue = $this->calculateRtoDepositAmountDue();

            // if it is change order
            if ($changeOrderFee) {
                $minAmountToApply = $mfDepositAmountDue + $rtoDepositAmountDue;
            } else {
                $minAmountToApply = $minRtoDepositAmount + $mfDepositAmountDue;
            }
        }

        $minAmountToApply = (float) number_format($minAmountToApply, 2, '.', '');
        $this->order->min_amount_apply = $minAmountToApply;
        return $minAmountToApply;
    }

    /**
     * Calculate total amount due on order
     * @totalAmountDue
     * @return float
     */
    protected function calculateTotalDepositAmountDue() {
        $mfDepositAmountDue = $this->order->mf_deposit_amount_due;
        $rtoDepositAmountDue = $this->order->rto_deposit_amount_due;

        $totalDepositAmountDue = $mfDepositAmountDue + $rtoDepositAmountDue;
        $totalDepositAmountDue = (float) number_format($totalDepositAmountDue, 2, '.', '');

        $this->order->total_deposit_amount_due = $totalDepositAmountDue;
        return $totalDepositAmountDue;
    }

    /**
     * Calculate manufacturer deposit amount due on order
     * @mfAmountDue
     * @return float
     */
    protected function calculateMfDepositAmountDue() {
        $paymentType = $this->order->payment_type;
        $changeOrderFee = $this->order->change_order_fee;

        // cash variables
        $purchaseOutrightDepositAmount = $this->order->po_deposit_amount;
        $mfDepositReceived = $this->order->mf_deposit_received;

        // rto variables
        $totalNonRtoOptions = $this->order->total_non_rto_options;

        if ($paymentType === 'cash') {
            $mfAmountDue = $purchaseOutrightDepositAmount - $mfDepositReceived;
            $mfAmountDue = max($mfAmountDue, 0);
            $mfAmountDue += $changeOrderFee;
        }

        if ($paymentType === 'rto') {
            $mfAmountDue = $totalNonRtoOptions - $mfDepositReceived;
            $mfAmountDue = max($mfAmountDue, 0);
            $mfAmountDue += $changeOrderFee;
        }

        $mfAmountDue = $mfAmountDue ?? 0;
        $mfAmountDue = (float) number_format($mfAmountDue, 2, '.', '');

        $this->order->mf_deposit_amount_due = $mfAmountDue;
        return $mfAmountDue;
    }

    /**
     * Calculate rto deposit amount due on order
     * @rtoAmountDue
     * @return float
     */
    protected function calculateRtoDepositAmountDue() {
        $paymentType = $this->order->payment_type;
        if ($paymentType !== 'rto') return 0;

        // min deposit amount based on buydown (default)
        $minDepositAmount = $this->calculateMinRtoDepositAmount('no-buydown');
        $netBuydown = $this->order->rto_net_buydown;
        $grossBuydown = $this->order->gross_buydown;
        $rtoDepositReceived = $this->order->rto_deposit_received;

        if ($netBuydown <= 0) {
            $rtoAmountDue = $minDepositAmount - $rtoDepositReceived;
        } else {
            $rtoAmountDue = $grossBuydown - $rtoDepositReceived;
        }

        $rtoAmountDue = max($rtoAmountDue, 0);
        $rtoAmountDue = (float) number_format($rtoAmountDue, 2, '.', '');

        $this->order->rto_deposit_amount_due = $rtoAmountDue;
        return $rtoAmountDue;
    }

    /**
     * Collect manufacturer deposits for entire chain of 'change orders'
     * @return float
     */
    protected function calculateMfDepositReceived() {
        $originalOrder = $this->order->original_order;
        $mfDepositReceived = 0;

        if ($originalOrder) {
            $mfDepositReceived = $originalOrder->mf_deposit_received + $originalOrder->amount_received - $originalOrder->rto_deposit_amount_due;
        }

        $mfDepositReceived = (float) number_format($mfDepositReceived, 2, '.', '');

        $this->order->mf_deposit_received = $mfDepositReceived;
        return $mfDepositReceived;
    }

    /**
     * Collect rto deposits for entire chain of 'change orders'
     * @return float
     */
    protected function calculateRtoDepositReceived() {
        $originalOrder = $this->order->original_order;
        $rtoDepositReceived = 0;

        if ($originalOrder) {
            $rtoDepositReceived = $originalOrder->rto_deposit_received + $originalOrder->rto_deposit_amount_due;
        }

        $rtoDepositReceived = (float) number_format($rtoDepositReceived, 2, '.', '');

        $this->order->rto_deposit_received = $rtoDepositReceived;
        return $rtoDepositReceived;
    }

    /**
     * (@orderTotal)
     * @return OrderCalculator
     */
    protected function calculateTotalOrder() {
        $totalBuildingPrice = $this->order->total_sales_price;
        $totalTaxableOptions = $this->order->total_taxable_options;
        $totalNonTaxableOptions = $this->order->total_non_taxable_options;

        $totalOrder = $totalBuildingPrice + $totalTaxableOptions + $totalNonTaxableOptions;
        $totalOrder = (float) number_format($totalOrder, 2, '.', '');

        $this->order->total_order = $totalOrder;
        return $totalOrder;
    }

    /**
     * (@totalAmount)
     * @return OrderCalculator
     */
    protected function calculateTotalAmount() {
        $totalBuildingPrice = $this->order->total_sales_price;
        $totalOrderOptions = $this->order->total_options;
        $salesTax = $this->order->sales_tax;
        $changeOrderFee = $this->order->change_order_fee;

        $totalAmount = $totalBuildingPrice + $totalOrderOptions + $salesTax + $changeOrderFee;
        $totalAmount = (float) number_format($totalAmount, 2, '.', '');

        $this->order->total_amount = $totalAmount;
        return $totalAmount;
    }

    /**
     * (@grossBuydown)
     * @return float
     */
    protected function calculateGrossBuydown() {
        $amountReceived = $this->order->amount_received;
        $rtoDepositReceived = $this->order->rto_deposit_received;
        $mfDepositAmountDue = $this->calculateMfDepositAmountDue();

        $grossBuydown = $amountReceived - $mfDepositAmountDue + $rtoDepositReceived;
        $grossBuydown = (float) number_format($grossBuydown, 2, '.', '');

        $this->order->gross_buydown = $grossBuydown;
        return $grossBuydown;
    }

    /** Calculate @balanceDue
     * @return float
     */
    protected function calculateBalanceDue() {
        $paymentType = $this->order->payment_type;
        $totalPurchase = $this->order->total_purchase;
        $amountReceived = $this->order->amount_received;
        $rtoAmount = $this->order->rto_amount;

        if ($paymentType === 'rto') {
            $balanceDue = $rtoAmount;
        } else {
            $balanceDue = $totalPurchase - $amountReceived;
        }

        $balanceDue = (float) number_format($balanceDue, 2, '.', '');
        $this->order->balance_due = $balanceDue;
        return $balanceDue;
    }

    /**
     * Calculate total purchase (@totalPurchase)
     * @return null|float $totalPurchase
     */
    protected function calculateTotalPurchase() {
        $dealer = $this->dealer;
        $totalBuildingPrice = $this->order->total_sales_price;
        $totalTaxableOrderOptions = $this->order->total_taxable_options;
        $totalNonTaxableOrderOptions = $this->order->total_non_taxable_options;
        $taxFactor = $this->order->tax_factor;

        if ($dealer->depositType === 1) {
            // TODO: check formula
            $totalPurchase = $totalBuildingPrice + $totalTaxableOrderOptions;
        } else {
            $totalPurchase = ($totalBuildingPrice + $totalTaxableOrderOptions) * (1 + $taxFactor) + $totalNonTaxableOrderOptions;
        }

        $totalPurchase = (float) number_format($totalPurchase, 2, '.', '');

        $this->order->total_purchase = $totalPurchase;
        return $totalPurchase;
    }

    /**
     * @param mixed $dealer
     * @return OrderCalculator
     */
    public function setDealer($dealer)
    {
        $this->dealer = $dealer;
        return $this;
    }

    /**
     * @param mixed $building
     * @return OrderCalculator
     */
    public function setBuilding($building)
    {
        $this->building = $building;
        return $this;
    }
}
