@extends('forms.quote-layout')

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
            Work Order
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
                            @endif
                        </table>
                    </td>
                    <td valign="top" width="47%">
                        @if($companySettings->company_logo_public_path)
                            <img src="{{ url($companySettings->company_logo_public_path) }}" alt="{{ config('app.name') }} Logo" style="width: 65px; float: left"/>
                        @endif
                        <div style="display: inherit; font-size: 10px !important; height: 68px; vertical-align: middle; padding-left: 0.7em">
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
        <td valign="center" align="left" width="21%" style="border: 1px solid black;">{{ $buildingLocation['address'] ?? '' }}</td>
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

    <?php
    $orderOptions = [];
    if (count($order->options) > 0) {
        foreach ($order->options as $option) {
            $hydratedOption = new stdClass();
            $hydratedOption->rto = $option->option->rto ? '<i class="fa fa-check"></i>' : '';
            $hydratedOption->tax =  $option->option->taxable ? '<i class="fa fa-check"></i>' : '';
            $hydratedOption->title = str_limit($option->option->name, 60, '...');
            $hydratedOption->price = '$' . number_format($option->total_price, 2);

            $orderOptions[] = $hydratedOption;
        }
    }
    ?>

<!-- Building Information -->
<table width="800" class="border-cells small-text">
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
        <td valign="center" align="center" style="font-weight: bold; border: 1px solid black;background: #dbe7ff" colspan="2">Building Material</td>
        <td valign="center" align="center" style="border: 1px solid black; background: #dbe7ff">Color</td>
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
    </tr>
    <tr>
        <td valign="center" align="right" width="14%">Size:</td>
        <td valign="center" align="left" width="28%" style="border: 1px solid black;">
            @if( $building->width !== null && $building->length !== null )
                {{ $building->width }}' x {{ $building->length }}'
            @endif
        </td>
        <td></td>
        <td valign="center" align="center" style="border: 1px solid black; background: #dbe7ff">Trim</td>
        <td valign="center" align="center" style="border: 1px solid black;">{{ $building->trim ? $building->trim->option->name : '' }}</td>
        <td valign="center" align="center" style="border: 1px solid black;">{{ $building->trim ? $building->trim->color->name : '' }}</td>
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
    </tr>

</table>

<table width="800" class="border-cells small-text">
    <tr>
        <td width="55%" valign="top">
            <table width="100%" class="border-cells">
                <tr style="background: #dbe7ff" class="border-cells">
                    <td valign="center" align="center" width="12%">Quantity</td>
                    <td valign="center" align="center" width="55%"><strong>Building Options</strong></td>
                </tr>

                <?php $buildingOptionsTbl = $building->options_without_materials ?? collect(); ?>
                @for( $i = 0; $i<=23; $i++ )
                    <tr class="border-cells">
                        @if( ( $option = $buildingOptionsTbl->shift()))
                            <td class="with-borders" valign="center" align="center" width="27%">{{ $option->quantity }}</td>
                            <td class="with-borders" valign="center" align="center" width="73%">{{ str_limit($option->option->name, 60, '...') }}</td>
                        @else
                            <td valign="center" align="center" width="27%">&nbsp;</td>
                            <td valign="center" align="center" width="73%"></td>
                        @endif
                    </tr>
                @endfor

                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
            </table>
        </td>
        <td width="1%"></td>
        <td width="44%" valign="top">
            <table width="100%">
                <tr>
                    <td>
                        <table width="100%" class="border-cells" style="margin-top: -2px;">
                            <tr style="background: #dbe7ff" class="border-cells">
                                <td style="text-align: center;"><strong>Order Options</strong></td>
                            </tr>
                            @for( $i = 0; $i<=6; $i++ )
                                <tr class="border-cells" style="height: 16px;">
                                    @if( ( $option = array_shift($orderOptions)))
                                        <td class="with-borders" valign="center" align="center" width="100%">{{ $option->title }}</td>
                                    @else
                                        <td valign="center" align="center" width="100%"></td>
                                    @endif
                                </tr>
                            @endfor
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="border-cell" align="left" valign="top" style="height: 75px; ">
                        <strong>Notes:</strong><br>
                        {{ $order->dr_notes ?? '' }}
                    </td>
                </tr>

                @if($building->qr_code_build_status)
                    <tr><td>&nbsp;</td></tr>
                    <tr class="border-cells"><td align="center">Build Status QR</td></tr>
                    <tr>
                        <td>
                            <table width="100%" class="border-cells" style="margin-top: -2px;">
                                <tr>
                                    <td width="25%">Created on:</td>
                                    <td width="25%">{{ $building->qr_code_build_status->created_at->format('m/d/Y')  }}</td>
                                    <td rowspan="2" style="vertical-align: top;" align="center">
                                        <img src="{{ url($building->qr_code_build_status->public_path) }}" alt="QR Code" style="height: 140px; margin-top: 10px;" />
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: top;">Expires on:</td>
                                    <td style="vertical-align: top;">{{ $building->qr_code_build_status->expire_on->format('m/d/Y') }}</td>
                                    <td></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endif
            </table>
        </td>
    </tr>
</table>
@if($sections->contains('order-threed-model'))
    <div class="page-break"></div>
    @include('forms.order-3d')
@endif
