
    <style>
        @font-face {
            font-family: 'BrandonGrotesque-Bold';
            src: url({{ url('fonts/brandon_bld.ttf')}}) format('truetype');
        }

        #inventory-form-p1 {
            font-family: BrandonGrotesque-Bold, Sans-serif !important;
        }
        #inventory-form-p1 .big-font {
            font-family: BrandonGrotesque-Bold, Sans-serif !important;
            font-size: 26px !important;
            font-weight: bold;
        }
        #inventory-form-p1 .increased-height {
            font-family: BrandonGrotesque-Bold, Sans-serif !important;
            height: 42px;
        }
        #inventory-form-p1 .company-info {
            font-family: BrandonGrotesque-Bold, Sans-serif !important;
            font-size: 16px !important;
            line-height: 1;
        }
    </style>

<div id="inventory-form-p1">
    <table width="800">
        <tr>
            <td colspan="2" class="form-header">

            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                @if($companySettings->company_logo_public_path)
                    <img src="{{ url($companySettings->company_logo_public_path) }}" alt="{{ config('app.name') }}" style="height: 180px; " />
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center" style="padding-top: 10px;">
                <strong class="big-font">PURCHASE OUTRIGHT OR RENT TO OWN</strong>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 1em 4em 1em 4em;">
                <hr style="border: 3px solid orange;">
            </td>
        </tr>
        <tr align="center" class="increased-height">
            <td width="50%">
                <strong class="big-font">
                    <i class="fa {{ ($building->used_rto_owner == 0 ) ? 'fa-check-square-o' : 'fa-square-o' }}"></i> NEW
                </strong>
            </td>
            <td width="50%">
                <strong class="big-font">
                    <i class="fa {{ ($building->used_rto_owner != 0 ) ? 'fa-check-square-o' : 'fa-square-o' }}"></i> USED
                </strong>
            </td>
        </tr>
        <tr class="increased-height">
            <td class="big-font" style="padding-left: 1em;">BUILDING PACKAGE.................</td>
            <td class="big-font" style="padding-right: 1em;">
                <div style="border-bottom: 2px solid black; padding-bottom: 2px;">&nbsp;
                    <span class="big-font">&nbsp;
                        @if ($building->building_package)
                            {{ $building->building_package->name }}
                        @endif
                    </span>
                </div>
            </td>
        </tr>
        <tr class="increased-height">
            <td class="big-font" style="padding-left: 1em;">MODEL.............................................</td>
            <td class="big-font" style="padding-right: 1em;">
                <div style="border-bottom: 2px solid black; padding-bottom: 2px;">
                    <span class="big-font">&nbsp;{{ $building->building_model->name ?? '' }}</span>
                </div>
            </td>
        </tr>
        <tr class="increased-height">
            <td class="big-font" style="padding-left: 1em;">DESCRIPTION.................................</td>
            <td class="big-font" style="padding-right: 1em;">
                <div style="border-bottom: 2px solid black; padding-bottom: 2px;">
                    <span class="big-font">
                        &nbsp;{{ !empty($building->building_model->description) ? substr($building->building_model->description, 0, 40) : '' }}
                    </span>
                </div>
            </td>
        </tr>
        <tr class="increased-height">
            <td class="big-font" style="padding-left: 1em;">&nbsp;</td>
            <td class="big-font" style="padding-right: 1em;">
                <div style="border-bottom: 2px solid black; padding-bottom: 2px;">
                    <span class="big-font">
                        &nbsp;{{ !empty($building->building_model->description) ? substr($building->building_model->description, 41, 76) . '...' : '' }}
                    </span>
                </div>
            </td>
        </tr>
    </table>

    <table width="800">
        <tr class="increased-height">
            <td class="big-font" style="padding-left: 1em;" width="60%">
                PURCHASE OUTRIGHT......................
            </td>
            <td width="40%" colspan="2" style="padding-right: 2em;">
                <p style="border-bottom: 2px solid black;">&nbsp;</p>
            </td>
        </tr>
        <tr class="increased-height">
            <td class="big-font" style="padding-left: 1em;" colspan="3">
                RENT TO OWN STARTING AT___________PER MONTH
            </td>
        </tr>
        <tr style="height: 100px;" align="center">
            <td class="big-font" style="padding-left: 1em; padding-right: 1em; line-height: 36px;" colspan="3">
                FREE DELIVERY AND SETUP WITHIN <br>30 MILES OF DEALERSHIP
            </td>
        </tr>
        <tr class="increased-height" align="center">
            <td class="big-font" style="padding-left: 1em; line-height: 36px;" colspan="3">
                CONTACT: {{ $dealer->phone ?? ''}}
            </td>
        </tr>
    </table>

    <table width="800">
        <tr>
            @if($footer1)
            <td width="30%" align="center" style="text-align: center; vertical-align: top;">
                <img src="{{ url($footer1->public_path) }}" height="110">
            </td>
            @endif

            @if($footer2)
            <td width="30%" align="center" style="vertical-align: bottom;">
                <img src="{{ url($footer2->public_path) }}" height="110">
            </td>
            @endif

            <td width="30%" align="center" class="company-info">
                @if($companySettings->website)
                    {{ $companySettings->website }}
                    <br>
                @endif

                @if($companySettings->email)
                    {{ $companySettings->email }}
                    <br>
                @endif

                @if($companySettings->address)
                    <br>
                        {{ $companySettings->address }}<br>
                        {{ $companySettings->city }}, {{ $companySettings->state }} {{ $companySettings->zip }}
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="3" class="footer">

            </td>
        </tr>
    </table>
</div>