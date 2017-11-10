@extends('forms.quote-layout')

<style type="text/css">

    #cash-receipt {
        font-size: 16px;
    }

    #cash-receipt .text-center {
        text-align: center;
    }

    #cash-receipt h1 {
        font-size: 44px;
        margin: 0;
        padding: 0;
    }

    #cash-receipt h2 {
        font-size: 32px;
        margin: 0;
        padding: 0;
    }

    #cash-receipt h3 {
        font-size: 24px;
        margin: 0;
        padding: 0;
    }

    #cash-receipt h4 {
        font-size: 16px;
        margin: 0;
        padding: 0;
    }
    .text-right {
        text-align: right;
        padding-right: 5px;
    }
    .border-box-field {
        border: 1px solid #000;
    }
    .border-collapse-table {
        border-collapse: collapse;
    }

</style>

<table width="800" class="small-text">
    <tr>
        <td colspan="6" style="text-align: left;">
            Customer Receipt
        </td>
    </tr>
    <tr><td colspan="6">&nbsp;</td></tr>
    <tr>
        <td colspan="6" style="text-align: center; font-size: 21px !important; line-height: 26px;">
            Thank you for purchasing your building from {{ config('app.name') }}!
        </td>
    </tr>
    <tr>
        <td colspan="6" valign="center" align="center" style="border: 1px solid black;" class="header">
            <strong>Order Information</strong>
        </td>
    </tr>
    <tr>
        <td width="34%" valign="top">
            <table width="100%" class="border-collapse-table">
                <tr>
                    <td width="40%" class="text-right">Dealer:</td>
                    <td width="60%" class="border-box-field">{{ $dealer->business_name ?? '' }}</td>
                </tr>
                <tr>
                    <td width="40%" class="text-right">Sales Person:</td>
                    <td width="60%" class="border-box-field">{{ $order->sales_person ?? '' }}</td>
                </tr>
                <tr>
                    <td width="40%" class="text-right">Order Date:</td>
                    <td width="60%" class="border-box-field">{{ $order->order_date ?? '' }}</td>
                </tr>
                <tr>
                    <td width="40%" class="text-right">Est. Delivery Period:</td>
                    <td width="60%" class="border-box-field">
                        @if($order->ced_start && $order->ced_end)
                            {{ $order->ced_start }} - {{ $order->ced_end }}
                        @endif
                    </td>
                </tr>
            </table>
        </td>
        <td width="1%"></td>
        <td width="65%" valign="top" style="font-size: 16px; text-align: center;">
            <table width="100%">
                <tr>
                    <td valign="top" width="52%" style="font-size: 9px;">
                        <table width="20%" style="float: left;">
                            <tr><td><strong>Tax Rate</strong></td></tr>
                            <tr>
                                <td align="center">
                                    @if($dealer->tax_rate)
                                        {{ $dealer->tax_rate }}%
                                    @endif
                                </td>
                            </tr>
                            <tr><td></td></tr>
                            <tr><td></td></tr>
                        </table>
                        <table width="30%" style="float: left;">
                            <tr><td><strong>Order Type</strong></td></tr>
                            @if ($order->sale_type)
                                <tr><td><i class="fa {{ ( $order->sale_type == 'dealer-inventory' ) ? 'fa-check-square-o' : 'fa-square-o' }}"></i> Inventory</td></tr>
                                <tr><td><i class="fa {{ ( $order->sale_type == 'custom-order' ) ? 'fa-check-square-o' : 'fa-square-o' }}"></i> Custom</td></tr>
                                <tr><td><i class="fa {{ ( $order->sale_type == 're-sale' ) ? 'fa-check-square-o' : 'fa-square-o' }}"></i> Re-sale</td></tr>
                            @else
                                <tr><td><i class="fa fa-square-o"></i> Inventory</td></tr>
                                <tr><td><i class="fa fa-square-o"></i> Custom</td></tr>
                                <tr><td><i class="fa fa-square-o"></i> Re-sale</td></tr>
                            @endif
                        </table>
                        <table width="40%" style="float: left;">
                            <tr><td><strong>Order ID:</strong> {{ $order->id }}</td></tr>
                            @if($order->original_order_id !== null)
                                <tr><td><strong>Orig. Order:</strong> {{ $order->original_order_id ?? '' }}</td></tr>
                                <tr><td><strong>Fees:</strong> {{ $order->txt_change_order_fee ?? '' }}</td></tr>
                            @endif
                        </table>
                    </td>
                    <td valign="top" width="47%">
                        @if($companySettings->company_logo_public_path)
                            <img src="{{ url($companySettings->company_logo_public_path) }}" alt="{{ config('app.name') }} Logo" style="width: 68px; float: left" />
                        @endif
                        <div style="display: inherit; font-size: 10px !important; height: 68px; vertical-align: middle; padding-left: 0.7em;">
                            {{ $companySettings->website_domain ?? config('app.url') }}<br>
                            {{ $dealer->email ?? $companySettings->email }}<br>
                            {{ $dealer->phone ?? $companySettings->phone }}
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="padding: 0;">
            <table width="100%">
                <tr>
                    <td width="5.15%"></td>
                    <td width="42.85%">*Special orders may extend beyond the estimated delivery period</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Customer Information -->
<table width="800" class="small-text border-cells">
    <tr>
        <td colspan="6" valign="center" align="center" style="border: 1px solid black;" class="header">
            <strong>Customer Information</strong>
        </td>
    </tr>
    <tr>
        <td valign="center" align="right" width="14%">Name:</td>
        <td valign="center" align="left" width="20%" style="border: 1px solid black;">{{ $orderReference->first_name ?? '' }} {{ $orderReference->last_name ?? '' }}</td>
        <td valign="center" align="right" width="10%">Cell Phone:</td>
        <td valign="center" align="left" width="23%" style="border: 1px solid black;">{{ $orderReference->phone_number ?? '' }}</td>
        <td align="center" colspan="2" style="font-weight: bold">Building Location</td>
    </tr>
    <tr>
        <td valign="center" align="right" width="14%">Address:</td>
        <td valign="center" align="left" width="20%" style="border: 1px solid black;">{{ $orderReference->address ?? '' }}</td>
        <td valign="center" align="right" width="10%">Home Phone:</td>
        <td valign="center" align="left" width="23%" style="border: 1px solid black;">{{ $orderReference->home_phone_number ?? '' }}</td>

        <td valign="center" align="right" width="12%">Address:</td>
        <td valign="center" align="left" width="21%" style="border: 1px solid black;">{{ $buildingLocation['address'] or '' }}</td>
    </tr>
    <tr>
        <td valign="center" align="right" width="14%">City / State / Zip:</td>
        <td valign="center" align="left" width="20%" style="border: 1px solid black;">
            @if($orderReference->city)
                {{ $orderReference->city }} /
            @endif

            @if($orderReference->state)
                {{ $orderReference->state }} /
            @endif

            @if($orderReference->zip)
                {{ $orderReference->zip }} /
            @endif
        </td>
        <td valign="center" align="right" width="10%">Email:</td>
        <td valign="center" align="left" width="23%" style="border: 1px solid black;">{{ $orderReference->email ?? '' }}</td>

        <td valign="center" align="right" width="12%">City / State / Zip:</td>
        <td valign="center" align="left" width="21%" style="border: 1px solid black;">
            @if(isset($buildingLocation['city']) && !empty($buildingLocation['city']))
                {{ $buildingLocation['city'] }} /
            @endif

            @if(isset($buildingLocation['state']) && !empty($buildingLocation['state']))
                {{ $buildingLocation['state'] }} /
            @endif

            @if(isset($buildingLocation['zip']) && !empty($buildingLocation['zip']) )
                {{ $buildingLocation['zip'] }} /
            @endif
        </td>
    </tr>
</table>

<table width="800" class="border-cells small-text">
    <tr>
        <td colspan="7" valign="center" align="center" style="border: 1px solid black;" class="header">
            <strong>Payment Information</strong>
        </td>
    </tr>
    <tr>
        <td valign="center" align="right" width="14%">Order Total:</td>
        <td valign="center" align="left" width="20%" style="padding-left: 5px;">
            {{ $order->txt_total_order ?? '' }}
        </td>
        <td width="20%"></td>
        <td valign="center" align="right" width="14%">Purchase Method:</td>
        <td valign="center" align="left" width="32%" style="padding-left: 5px;">
            {{ $order->payment_type === 'cash' ? 'Outright' : 'RTO' }}
        </td>
    </tr>
    <tr>
        <td valign="center" align="right" width="14%">Tax:</td>
        <td valign="center" align="left" width="20%" style="padding-left: 5px;">{{ $order->txt_sales_tax ?? ''}}</td>
        <td width="20%"></td>
        @if ($order->payment_type == 'rto' && $order->rto_company)
            <td valign="center" align="right" width="14%">RTO Company:</td>
            <td valign="center" align="left" width="32%" style="padding-left: 5px;">{{ $order->rto_company->name }}</td>
        @endif
    </tr>
    <tr>
        <td valign="center" align="right" width="14%">Total Amount Due:</td>
        <td valign="center" align="left" width="20%" style="padding-left: 5px;">
            @if ($order->payment_type == 'rto')
                RTO
            @else
                {{ $order->txt_total_deposit_amount_due ?? '' }}
            @endif
        </td>
        <td width="20%"></td>
        <td valign="center" align="right" width="14%">
            {{ $order->payment_type == 'rto' ? 'RTO Amount:' : '' }}
        </td>
        <td valign="center" align="left" width="32%" style="padding-left: 5px;">
            {{ $order->payment_type == 'rto' ? $order->txt_rto_amount : '' }}
        </td>
    </tr>
    <tr><td colspan="5">&nbsp;</td></tr>
    <tr><td colspan="5">&nbsp;</td></tr>
    <tr>
        <td valign="center" align="right" width="14%">Deposit Received:</td>
        <td valign="center" align="left" width="20%" style="padding-left: 5px;">{{ $order->txt_amount_received ?? '' }}</td>
        <td width="20%"></td>
        <td valign="center" align="center" width="46%" colspan="2">Deposit Method</td>
    </tr>
    <tr>
        <td valign="center" align="right" width="14%">
            {{ $order->original_order_id ? 'Change Order Fee:' : '' }}
        </td>
        <td valign="center" align="left" width="20%" style="padding-left: 5px;">
            @if($order->original_order_id)
                {{ $order->txt_change_order_fee ?? '' }}
            @endif
        </td>
        <td width="20%"></td>
        <td colspan="2">
            <table width="100%" class="border-cells">
                <tr>
                    <td style="text-align: center;">
                        <i class="fa {{ ($order->payment_method == 'cash' ) ? 'fa-check-square-o' : 'fa-square-o' }}"></i> Cash
                        &nbsp;&nbsp;&nbsp;
                        <i class="fa {{ ($order->payment_method == 'check' ) ? 'fa-check-square-o' : 'fa-square-o' }}"></i> Check
                        &nbsp;&nbsp;&nbsp;
                        <i class="fa {{ ($order->payment_method == 'credit_card') ? 'fa-check-square-o' : 'fa-square-o' }}"></i> CC
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr><td colspan="5">&nbsp;</td></tr>
</table>

<table width="800" class="border-cells small-text">
    <tr>
        <td colspan="3">&nbsp;</td>
        <td valign="center" align="center" width="25%">Transaction ID</td>
        <td></td>
    </tr>
    <tr>
        <td valign="center" align="right" width="14%"><strong style="font-size: 13px !important;">Balance:</strong></td>
        <td valign="center" align="left" width="20%" style="padding-left: 5px;">{{ $order->txt_balance_due ?? ''}}</td>
        <td width="30%"></td>
        <td valign="center" align="center" width="25%" style="padding-left: 5px;">
            {{ $order->transaction_id ?? '' }}
        </td>
        <td width="11%"></td>
    </tr>
</table>

<table width="800" class="border-cells small-text">
    <tr><td>&nbsp;</td></tr>
    <tr>
        <td width="40%" valign="top" class="border-cells small-text">
            <table width="100%" class="border-cells">
                <tr>
                    <td colspan="2" style="font-size: 2.5em; text-align: left;">
                        Questions about your order? Feel free to contact your local dealer:
                    </td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                   <td width="30%">Dealer:</td>
                   <td>{{ $dealer->business_name }}</td>
                </tr>
                <tr>
                    <td>Sales Person:</td>
                    <td>{{ $order->sales_person }}</td>
                </tr>
                <tr>
                    <td>Phone:</td>
                    <td>{{ $dealer->phone ?? '' }}</td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>{{ $dealer->email ?? '' }}</td>
                </tr>
            </table>
        </td>
        <td width="3%"></td>
        <td width="41%">
            <table width="100%" class="text-center">
                <tr>
                    <td>Track your order status online!</td>
                </tr>
                <tr>
                    <td align="center" colspan="2">
                        Coming Soon!
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

@if(!empty($companySettings->footnote) )
    <table width="800" class="border-cells" style="margin-top: 10px;">
        <tr>
            <td class="border-cell" style="padding: 0.5em; font-size: 10px;">
                {{ $companySettings->footnote }}
            </td>
        </tr>
    </table>
@endif