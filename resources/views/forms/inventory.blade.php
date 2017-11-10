@extends('forms.quote-layout')

@section('pdf-content')
    @include('forms.inventory-p1')

    <div class="page-break"></div>
    <!-- Header -->
    <table width="800">
        <tr>
            <td colspan="6" style="text-align: left;">
                Inventory Form
            </td>
        </tr>
        <tr><td colspan="6">&nbsp;</td></tr>
        <tr>
            <td width="50%" valign="top">
                @if($companySettings->company_logo_public_path)
                    <img src="{{ url($companySettings->company_logo_public_path) }}" alt="{{ config('app.name') }}" style="float: left; height: 120px; margin-right: 15px;" />
                @endif
                <div style="font-size: 10px !important; font-weight: bold; padding-top: 40px;">
                    {{ $companySettings->website_domain ?? config('app.url') }}<br>
                    {{ $dealer->location->name }}<br>
                    {{ $dealer->location->address }},
                    {{ $dealer->location->city }},
                    {{ $dealer->location->state }},
                    {{ $dealer->location->zip }}
                    <br>
                    {{ $dealer->email ?? $companySettings->email }}<br>
                    {{ $dealer->phone ?? $companySettings->phone }}
                </div>
            </td>
            <td align="center" width="10%" valign="center">
                <strong>Tax Rate</strong><br>
                {{ $order->txt_tax_rate }}
            </td>
            <td width="40%">&nbsp;</td>
        <tr>
    </table>

    <br/>

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
                @if ($building->building_package)
                    {{ $building->building_package->name }}
                @endif
            </td>
            <td width="2%"></td>
            <td valign="center" align="center" width="25%" colspan="2" style="border: 1px solid black; background: #dbe7ff">Building Material</td>
            <td valign="center" align="center" style="border: 1px solid black; background: #dbe7ff">Color</td>
            <td valign="center" align="center" width="10%" style="border: 1px solid black; background: #dbe7ff">Price</td>
        </tr>
        <tr>
            <td valign="center" align="right" width="14%">Model & Serial #:</td>
            <td valign="center" align="left" width="28%" class="building-serial-container">

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
                @if( $building->width !== null && $building->length !== null )
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
                @if ( $building->shell_price !== null)
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
            <td width="63%" valign="top">
                <table width="100%" class="border-cells">
                    <tr style="background: #dbe7ff" class="border-cells">
                        <td valign="center" align="center" width="12%">Quantity</td>
                        <td valign="center" align="center" width="55%"><strong>Building Options</strong></td>
                        <td valign="center" align="center" width="15%">Price</td>
                        <td valign="center" align="center" width="17%">Total</td>
                    </tr>

                    <?php $buildingOptionsTbl = $building->options_without_materials ?? collect(); ?>
                    @for( $i = 0; $i<=25; $i++ )
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
                                    <td width="83%" class="text-right">
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
            <td width="36%" valign="top">
                <table width="100%" class="border-cells">
                    <!-- Building Price -->
                    <tr>
                        <td></td>
                        <td align="center" style="padding: 1em 0 0 0">
                            <div style="display: inherit; font-size: 13px !important; font-weight: bold">
                                Building Price *
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="55%" class="dl-form-title">Shell Price:</td>
                        <td width="45%" class="border-cell text-center">
                            @if ($building->shell_price)
                                ${{ number_format($building->shell_price, 2) }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td width="55%" class="dl-form-title">Building Materials:</td>
                        <td width="45%" class="border-cell text-center">
                            {{ $building->txt_material_sub_total ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td width="55%" class="dl-form-title">Building Options:</td>
                        <td width="45%" class="border-cell text-center">
                            {{ $building->txt_options_sub_total_without_materials ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td width="55%" class="dl-form-title"><strong>Building Total:</strong></td>
                        <td width="45%" class="border-cell text-center">
                            @if ($building->total_price)
                                ${{ number_format($building->total_price, 2) }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td width="55%" class="dl-form-title">Tax:</td>
                        <td width="45%" class="border-cell text-center">
                            {{ $order->txt_sales_tax ?? ''}}
                        </td>
                    </tr>
                    <tr>
                        <td width="55%" class="dl-form-title"><strong>Total Amount:</strong></td>
                        <td width="45%" class="border-cell text-center">
                            {{ $order->txt_total_amount ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center" width="100%">
                            <div style=" font-size: 12px !important; padding: 0.5em 0 0.5em 0; color: red;">
                                Purchase this building outright or using rent-to-own!
                            </div>
                        </td>
                    </tr>

                    <!-- Purchase outright -->
                    <tr>
                        <td></td>
                        <td align="center" style="padding: 1em 0 0 0">
                            <div style="display: inherit; font-size: 13px !important; font-weight: bold">
                                Purchase Outright
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="55%" class="dl-form-title">Deposit:</td>
                        <td width="45%" class="border-cell text-center">
                            {{ $order->txt_po_deposit_amount ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right" width="100%">
                            <div style=" font-size: 9px !important; padding: 0.5em 4.5em 0.5em 0">
                                Balance due upon delivery.
                            </div>
                        </td>
                    </tr>

                    <!-- RTO -->
                    <tr>
                        <td></td>
                        <td align="center" style="padding: 1em 0 0 0">
                            <div style="display: inherit; font-size: 13px !important; font-weight: bold">
                                RTO Options

                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="55%" class="dl-form-title">Security Deposit:</td>
                        <td width="45%" class="border-cell text-center">{{ $order->txt_security_deposit }}</td>
                    </tr>
                    <?php
                    foreach($rtoTerms as $modelId => $model) :
                    $rtoAdvanceMonthlyRenewalPayment = ($order->rto_amount / (float) $model['rto_factor']);
                    $rtoSalesTax = $rtoAdvanceMonthlyRenewalPayment * $order->tax_factor;
                    $rtoTotalAdvanceMonthlyRenewalPayment = $rtoAdvanceMonthlyRenewalPayment + $rtoSalesTax;
                    ?>
                    <tr>
                        <td width="55%" class="dl-form-title"><small>{{$model['value']}} Month:</small></td>
                        <td width="45%" class="border-cell text-center">${{ number_format($rtoTotalAdvanceMonthlyRenewalPayment, 2) }}</td>
                    </tr>
                    <?php endforeach; ?>
                    @if(isset($params['no_tax']))
                        <tr>
                            <td colspan="2" align="center">
                                <div style="display: inherit; font-size: 9px !important;">
                                    (taxes not included)
                                </div>
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td colspan="2" align="center">
                            <div style="display: inherit; font-size: 9px !important; padding: 2em 0 0.5em 0">
                               Rent-to-own purchases require a security deposit and first month's rent.
                            </div>
                        </td>
                    </tr>
                    @if($building->qrcode)
                    <tr>
                        <td colspan="2" align="center" class="border-cell">
                            Order this building online!
                        </td>
                    </tr>
                    <tr>
                        <td align="center" colspan="2">
                            <img src="{{ url($building->qrcode->path) }}" alt="USC Logo" style="height: 130px; margin-top: 10px;" />
                        </td>
                    </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    <table width="800" class="small-text">
        <tr>
            <td>*Prices subject to change. Please contact your dealer for current prices.</td>
        </tr>
    </table>


@endsection