<!-- Header -->
<style>
    .text-right {
        text-align: right;
        padding-right: 5px;
    }
    .text-center {
        text-align: center;
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
            Building Configuration
        </td>
    </tr>
    <tr><td colspan="6">&nbsp;</td></tr>
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
                                    {{ $order->txt_tax_rate }}
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
                                <tr><td><strong>Fees:</strong> {{ $order->txt_change_order_fee ?? ''  }}</td></tr>
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
        <td></td>
        <td colspan="6">*Special orders may extend beyond the estimated delivery period</td>
    </tr>
</table>