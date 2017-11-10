    <!-- Header -->
    <style>
        .text-right {
            text-align: right;
            padding-right: 5px;
        }
        .text-center {
            text-align: center;
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
                Order Form
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
                                <img src="{{ url($companySettings->company_logo_public_path) }}" alt="{{ config('app.name') }} Logo" style="width: 68px; float: left; "/>
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
            $hydratedOption->title = str_limit($option->option->name, 45, '...');
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
                            @if( ( $option = $buildingOptionsTbl->shift()))
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

                    <tr>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                    <tr>
                        <td valign="center" align="right" colspan="2"></td>
                        <td valign="center" align="right" colspan="2">
                            <table width="100%" class="border-cells">
                                <tr>
                                    <td width="45%" class="text-right">
                                        <strong>Subtotal:</strong>
                                    </td>
                                    <td style="border: 1px solid #000; text-align: center; background: lightgrey;">
                                        {{ $building->txt_options_sub_total_without_materials ?? '' }}
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
                        <td>
                            <table width="100%" class="border-cells" style="margin-top: -2px;">
                                <tr style="background: #dbe7ff" class="border-cells">
                                    @if($order->payment_type == 'rto')<td width="7%" class="small-font"><small>RTO?</small></td>@endif
                                    <td width="7%" class="small-font text-center"><small>Tax?</small></td>
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
                                            <td class="with-borders" valign="center" align="center" width="65%">{{ str_limit($option->title, 45, '...') }}</td>
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
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                        <td>
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
                        <td class="border-cell" align="left" valign="top" style="height: 75px; ">
                            <strong>Notes:</strong><br>
                            {{ $order->dr_notes ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td>
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
                                        {{ $building->txt_material_sub_total ?? ''  }}
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
                                    <td width="60%" class="dl-form-title"><strong>Order Total:</strong></td>
                                    <td width="40%" class="text-left">
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
                                    <td width="65%" class="dl-form-title"><strong>Total Amount:</strong></td>
                                    <td width="35%" class="text-left">
                                        {{ $order->txt_total_amount ?? '' }}
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

                                <tr><td>&nbsp;</td></tr>
                                <tr>
                                    <td colspan="2">
                                        <table width="100%" class="border-cells">
                                            <tr>
                                                <td width="40%" class="text-right">
                                                    <strong>Purchase Method:</strong>
                                                </td>
                                                <td style="text-align: center;">
                                                    <i class="fa {{ ($order->payment_type == 'cash' ) ? 'fa-check-square-o' : 'fa-square-o' }}"></i> <strong>Outright</strong>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <i class="fa {{ ($order->payment_type == 'rto' && (!isset($isEmptyForm) || !$isEmptyForm)) ? 'fa-check-square-o' : 'fa-square-o' }}"></i> <strong>RTO</strong>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <i class="fa {{ ($order->payment_type == 'other')
                                                        ? 'fa-check-square-o' : 'fa-square-o' }}"></i> <strong>Other</strong>
                                                </td>
                                            </tr>
                                            @if($order->payment_type == 'rto')
                                                <tr>
                                                    <td class="text-right">RTO Company:</td>
                                                    <td style="text-align: left;">{{ $order->rto_company->name ?? '' }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table width="800" class="border-cells">
        @if(!empty($companySettings->footnote) )
        <tr>
            <td class="border-cell" colspan="3" style="padding: 0.5em; font-size: 10px; ">
                {{ $companySettings->footnote }}
            </td>
        </tr>
        @endif
        <tr>
            <td colspan="3" height="15px"></td>
        </tr>
    </table>

    <table width="800" class="border-cells">
        <tr>
            <td width="50%" style="border-bottom: 1px solid black;">
                <br>
                <br>
                <span style="color: #ffffff;">[sig|req|signer1|Customer Signature]</span>
            </td>
            <td width="5%"></td>
            <td width="15%" style="border-bottom: 1px solid black;">
                <br>
                <br>
                <span style="color: #ffffff;">[date|req|signer1|Date]</span>
            </td>
            <td></td>
        </tr>
        <tr>
            <td width="50%" valign="top">Customer Signature</td>
            <td width="5%" valign="top"></td>
            <td width="15%" valign="top">Date</td>
            <td width="25%" valign="top">
                <table width="100%" class="text-center">
                    {{--<tr>--}}
                        {{--<td><strong>Track your order status online!</strong></td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td align="center" colspan="2">--}}
                            {{--@if(isset($order->qrcode))--}}
                                {{--<img src="{{ url($order->qrcode->path) }}" alt="Order QR Code" style="height: 100px; margin-top: 10px;" />--}}
                            {{--@endif--}}
                        {{--</td>--}}
                    {{--</tr>--}}
                </table>
            </td>
        </tr>
    </table>
