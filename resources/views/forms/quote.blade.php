@extends('forms.quote-layout')

@section('pdf-content')
    <?php

    $buildingLocation['address'] = $orderReference->building_location_address ?? '';
    $buildingLocation['city'] = $orderReference->building_location_city ?? '';
    $buildingLocation['state'] = $orderReference->building_location_state ?? '';
    $buildingLocation['zip'] = $orderReference->building_location_zip ?? '';
    if ($orderReference->building_in_same_address === true) {
        $buildingLocation['address'] = $orderReference->address ?? '';
        $buildingLocation['city'] = $orderReference->city ?? '';
        $buildingLocation['state'] = $orderReference->state ?? '';
        $buildingLocation['zip'] = $orderReference->zip ?? '';
    }

    $orderOptions = [];

    if (count($order->options) > 0) {
        $orderOptionsTotal = 0;

        foreach ($order->options as $option) {
            $hydratedOption = new stdClass();
            $hydratedOption->rto = $option->option->rto ? '<i class="fa fa-check"></i>' : '';
            $hydratedOption->tax =  $option->option->taxable ? '<i class="fa fa-check"></i>' : '';
            $hydratedOption->title = str_limit($option->option->name, 45, '...');
            $hydratedOption->price = '$' . number_format($option->total_price, 2);

            $orderOptionsTotal += $option->total_price;
            $orderOptions[] = $hydratedOption;
        }
    }

    ?>

<!-- Header -->
<style>
    .text-right {
        text-align: right;
        padding-right: 5px;
    }
    .text-left {
        text-align: left;
        padding-left: 5px;
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
            Quote Form
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
                    <td width="40%" class="text-right">Quote Date:</td>
                    <td width="60%" class="border-box-field">{{ $order->order_date ?? '' }}</td>
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

<!-- Building Information -->
<table width="800" class="border-cells small-text">
    <tr>
        <td colspan="7" style="text-align: left;">
            Quote Form
        </td>
    </tr>
    <tr><td colspan="7">&nbsp;</td></tr>
    <tr>
        <td colspan="7" valign="center" align="center" style="border: 1px solid black;" class="header">
            <strong>Building Information</strong>
        </td>
    </tr>
    <tr>
        <td valign="center" align="right" width="14%" style="font-weight: bold">Building Package:</td>
        <td valign="center" align="left" width="28%" style="border: 1px solid black;">
            {{ $building->building_package->name ?? ''}}
        </td>
        <td width="2%"></td>
        <td valign="center" align="center" style="font-weight: bold; border: 1px solid black; background: #dbe7ff" colspan="2">Building Material</td>
        <td valign="center" align="center" style="border: 1px solid black; background: #dbe7ff">Color</td>
        <td valign="center" align="center" style="border: 1px solid black; background: #dbe7ff">Price</td>
    </tr>
    <tr>
        <td valign="center" align="right" width="14%">Model & Serial #:</td>
        <td valign="center" align="left" width="28%" class="building-serial-container">

            @if ($order->sale_type)
                <table class="building-serial" cellspacing="0">
                    <tr>
                        <td style="border-right: 1px solid black; white-space: nowrap">{{ $building->serial_short_code }}-{{ $building->serial_sizes }}</td>
                        <td>
                            @if($building->serial_ident)
                                {{ $building->serial_ident }}
                            @else
                                &nbsp;
                            @endif
                        </td>
                    </tr>
                </table>
            @endif

        </td>
        <td></td>
        <td valign="center" align="center" style="border: 1px solid black; background: #dbe7ff; width: 10%;">Siding</td>
        <td valign="center" align="center" style="border: 1px solid black;">{{ $building->siding ? $building->siding->option->name : '' }}</td>
        <td valign="center" align="center" style="border: 1px solid black; width: 17%;">{{ $building->siding ? $building->siding->color->name : '' }}</td>
        <td valign="center" align="center" style="border: 1px solid black; width: 10%;">@if($building->siding) ${{ number_format($building->siding->total_price, 2) }} @endif</td>
    </tr>
    <tr>
        <td valign="center" align="right" width="14%">Size:</td>
        <td valign="center" align="left" width="28%" style="border: 1px solid black;">
            @if( $building->width !== null && $building->length !== null)
                {{ $building->width }}' x {{ $building->length }}'
            @endif
        </td>
        <td></td>
        <td valign="center" align="center" style="border: 1px solid black; background: #dbe7ff">Trim</td>
        <td valign="center" align="center" style="border: 1px solid black;">{{ $building->trim ? $building->trim->option->name : '' }}</td>
        <td valign="center" align="center" style="border: 1px solid black;">{{ $building->trim ? $building->trim->color->name : '' }}</td>
        <td valign="center" align="center" style="border: 1px solid black;">@if($building->trim) ${{ number_format($building->trim->total_price, 2) }} @endif</td>
    </tr>
    <tr>
        <td valign="center" align="right" width="14%">Shell Price:</td>
        <td valign="center" align="left" width="28%" style="border: 1px solid black;">
            @if ($building->shell_price !== null)
                ${{ number_format($building->shell_price, 2) }}
            @endif
        </td>
        <td></td>
        <td valign="center" align="center" style="border: 1px solid black; background: #dbe7ff">Roof</td>
        <td valign="center" align="center" style="border: 1px solid black;">{{ $building->roof ? $building->roof->option->name : '' }}</td>
        <td valign="center" align="center" style="border: 1px solid black;">{{ $building->roof ? $building->roof->color->name : '' }}</td>
        <td valign="center" align="center" style="border: 1px solid black;">@if($building->roof) ${{ number_format($building->roof->total_price, 2) }} @endif</td>
    </tr>
</table>

<table width="800" class="border-cells small-text">
    <tr>
        <td width="55%" valign="top">
            <table width="100%" class="border-cells">
                <tr style="background: #dbe7ff" class="border-cells">
                    <td valign="center" align="center" width="12%">Quantity</td>
                    <td valign="center" align="center" width="55%"><strong>Building Options</strong></td>
                    <td valign="center" align="center" width="15%">Price</td>
                    <td valign="center" align="center" width="17%">Total</td>
                </tr>

                <?php $buildingOptionsTbl = $building->options_without_materials ?? collect(); ?>
                @for( $i = 0; $i<=23; $i++ )
                    <tr class="border-cells">
                        @if( ($option = $buildingOptionsTbl->shift()) )
                            <td class="with-borders" valign="center" align="center" width="12%">{{ $option->quantity }}</td>
                            <td class="with-borders" valign="center" align="center" width="55%">{{ str_limit($option->option->name, 45, '...') }}</td>
                            <td class="with-borders" valign="center" align="center" width="15%">${{ number_format($option->unit_price, 2) }}</td>
                            <td class="with-borders" valign="center" align="center" width="17%">${{ number_format($option->total_price, 2) }}</td>
                        @else
                            <td valign="center" align="center" width="12%">&nbsp;</td>
                            <td valign="center" align="center" width="55%"></td>
                            <td valign="center" align="center" width="15%"></td>
                            <td valign="center" align="center" width="17%"> </td>
                        @endif
                    </tr>
                @endfor
                <tr><td>&nbsp;</td></tr>
                <tr>
                    <td colspan="4">
                        <table width="100%" class="border-cells">
                            <tr>
                                <td width="80%" class="text-right">
                                    <strong>Subtotal:</strong>
                                </td>
                                <td style="border: 1px solid #000; text-align: center; background: lightgrey;">
                                    {{ $building->txt_options_sub_total_without_materials ?? ''}}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
        <td width="1%"></td>
        <td width="44%" valign="top">
            <table width="100%">
                <tr>
                    <td colspan="2">
                        <table width="100%" class="border-cells" style="margin-top: -2px;">
                            <tr style="background: #dbe7ff" class="border-cells">
                                @if($order->payment_type == 'rto')<td width="7%"><small>RTO?</small></td>@endif
                                <td width="7%"><small>Tax?</small></td>
                                <td style="text-align: center;"><strong>Order Options</strong></td>
                                <td style="text-align: center;">Price</td>
                            </tr>
                            @for( $i = 0; $i<=6; $i++ )
                                <tr class="border-cells" style="height: 16px;">
                                    @if( ( $option = array_shift($orderOptions)))
                                        @if($order->payment_type == 'rto')
                                            <td class="with-borders" valign="center" align="center" width="5%">{!! $option->rto !!}</td>
                                        @endif
                                        <td class="with-borders" valign="center" align="center" width="5%">{!! $option->tax !!}</td>
                                        <td class="with-borders" valign="center" align="center" width="65%">{{ $option->title }}</td>
                                        <td class="with-borders" valign="center" align="center" width="25%">{{ $option->price }}</td>
                                    @else
                                        @if($order->payment_type == 'rto')
                                            <td valign="center" align="center" width="5%">&nbsp;</td>
                                        @endif
                                        <td valign="center" align="center" width="5%"></td>
                                        <td valign="center" align="center" width="65%"></td>
                                        <td valign="center" align="center" width="25%"> </td>
                                    @endif
                                </tr>
                            @endfor
                        </table>
                    </td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr>
                    <td colspan="2">
                        <table width="100%" class="border-cells">
                            <tr>
                                <td width="78%" class="text-right">
                                    <strong>Subtotal:</strong>
                                </td>
                                <td style="border: 1px solid #000; text-align: center; background: lightgrey;">
                                    {{ $order->txt_total_options ?? '' }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td class="border-cell" align="left" valign="top" style="height: 75px; " colspan="2">
                        <strong>Notes:</strong><br>
                        {{ $order->dr_notes ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table class="border-cells" width="100%">
                            <tr>
                                <td width="65%" class="dl-form-title">Shell Price:</td>
                                <td width="35%" class="text-left">
                                    @if ($building->shell_price)
                                        ${{ number_format($building->shell_price, 2) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td width="65%" class="dl-form-title">Building Materials:</td>
                                <td width="35%" class="text-left">
                                    {{ $building->txt_material_sub_total ?? ''}}
                                </td>
                            </tr>
                            <tr>
                                <td width="65%" class="dl-form-title">Building Options:</td>
                                <td width="35%" class="text-left">
                                    {{ $building->txt_options_sub_total_without_materials ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="65%" class="dl-form-title"><strong>Building Total:</strong></td>
                                <td width="35%" class="text-left">
                                    @if ($building->total_price)
                                        ${{ number_format($building->total_price, 2) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td width="65%" class="dl-form-title">Order Options:</td>
                                <td width="35%" class="text-left">
                                    {{ $order->txt_total_options ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="65%" class="dl-form-title"><strong>Order Total:</strong></td>
                                <td width="35%" class="text-left">
                                    {{ $order->txt_total_order ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td width="65%" class="dl-form-title">Tax:</td>
                                <td width="35%" class="text-left">
                                    {{ $order->txt_sales_tax ?? ''}}
                                </td>
                            </tr>
                            <tr>
                                <td width="65%" class="dl-form-title">Total Amount:</td>
                                <td width="35%" class="text-left">
                                    {{ $order->txt_total_amount ?? ''}}
                                </td>
                            </tr>
                            @if ($order->payment_type == 'rto')
                                <tr>
                                    <td width="65%" class="dl-form-title">RTO Amount:</td>
                                    <td width="35%" class="text-left">
                                        {{ $order->txt_rto_amount ?? '' }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td width="65%" class="dl-form-title"><strong>Amount Due:</strong></td>
                                <td width="35%" class="text-left">
                                    @if ($order->payment_type == 'rto')
                                        RTO
                                    @else
                                        {{ $order->txt_total_deposit_amount_due ?? '' }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <div style="display: inherit; font-size: 9px !important; padding: 1em 0 0.5em 0">
                                        *Prices subject to change. All quotes are valid for 7 days from date of quote.
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- RTO -->

            </table>
        </td>
    </tr>
</table>

<table width="800" class="border-cells small-text">
    <tr>
        <td width="55%" valign="top" class="border-cells small-text">
            <table width="100%" class="border-cells">
                <tr>
                    <td colspan="4" style="font-size: 2.5em; text-align: center;">
                        <strong>Purchase this building outright or use rent-to-own!</strong>
                    </td>
                </tr>
                <tr class="border-collapse-table">
                    <td colspan="2" width="60%" style="border-collapse: collapse;">
                        <table class="small-text" width="100%">
                            <tr>
                                <td colspan="2" align="center" width="100%">
                                    <strong>RTO Options</strong>
                                </td>
                            </tr>
                            <tr>
                                <td width="30%" class="dl-form-title">Security Deposit:</td>
                                <td width="70%" class="text-center">
                                    {{ $order->txt_security_deposit }}
                                </td>
                            </tr>
                            <?php
                            foreach(\App\Models\Order::$rtoTerms as $modelId => $model) :
                            $rtoAdvanceMonthlyRenewalPayment = ($order->rto_amount / (float) $model['rto_factor']);
                            $rtoSalesTax = $rtoAdvanceMonthlyRenewalPayment * $order->tax_factor;
                            $rtoTotalAdvanceMonthlyRenewalPayment = $rtoAdvanceMonthlyRenewalPayment + $rtoSalesTax;
                            ?>
                            <tr>
                                <td width="30%" class="dl-form-title"><small>{{$model['value']}} Month:</small></td>
                                <td width="70%" class="text-center">${{ number_format($rtoTotalAdvanceMonthlyRenewalPayment, 2) }}</td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </td>
                    <td colspan="2" width="39%" style="border-collapse: collapse;">
                        <table class="small-text" width="100%">
                            <tr>
                                <td colspan="2" align="center" width="100%">
                                    <strong>Purchase Outright</strong>
                                </td>
                            </tr>
                            <tr>
                                <td width="30%" class="dl-form-title">Deposit:</td>
                                <td width="70%" class="text-center">
                                    {{ $order->txt_po_deposit_amount ?? '' }}
                                </td>
                            </tr>
                            @for($i = 0; $i < 4; $i++)
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                </tr>
                            @endfor
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">Rent-to-own purchases require a security deposit and first month's rent.</td>
                </tr>
            </table>
        </td>
        <td width="3%"></td>
        <td width="41%">
            <table width="100%" class="text-center">
                <tr>
                    <td><strong>Free delivery and setup within 30 miles!</strong></td>
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

@if($sections->contains('order-threed-model'))
    <div class="page-break"></div>
    @include('forms.order-3d')
@endif

@endsection
