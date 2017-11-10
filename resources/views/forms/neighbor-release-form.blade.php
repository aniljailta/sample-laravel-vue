<style type="text/css">
    #delivery-neighbor-release-form h2 {
        font-size: 26px;
        font-weight: normal;
    }
    #delivery-neighbor-release-form .label-form {
        font-size: 16px !important;
        white-space: nowrap;
        font-weight: bold;
    }

    #delivery-neighbor-release-form td, #rto-promo99 tr {
        line-height: 22px;
    }
</style>

<table id="delivery-neighbor-release-form" width="800" cellspacing="0" style="border-spacing: 20px 0;">

    <tr>
        <td>
            <table width="800" cellspacing="0">
                <td width="70%" align="right"></td>
                <td width="15%" align="right" class="label-form">Date:</td>
                <td width="15%" align="right" style="border-bottom: 1px solid #000">
                    <span style="color: #ffffff;">[date|req|signer1|Date]</span>
                </td>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="800" cellspacing="0">
                <tr>
                    @if($companySettings->company_logo_public_path)
                    <td align="center" width="40%">
                        <img src="{{ url($companySettings->company_logo_public_path) }}" alt="{{ config('app.name') }}" style="width: 55%; float: none; clear: both" />
                        <div style="display: inherit; font-size: 9px !important; font-weight: bold">
                            HIGH QUALITY PORTABLE BUILDINGS
                        </div>
                    </td>
                    @endif
                    <td align="left" width="40%">
                        <br>
                        <h2 style="text-align: center;">{{ config('app.name') }} <br> Neighbor Release Form</h2>
                    </td>
                    <td align="left" width="10%"></td>
                </tr>
            </table>

        </td>
    </tr>

    <tr>
        <td>
            <br>
            <br>
            <br>
            <br>
            <table width="100%">
                <tr>
                    <td class="label-form">Customer Name:</td>
                    <td width="100%" style="border-bottom: 1px solid #000"></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <br>
            <table width="100%">
                <tr>
                    <td class="label-form">Address:</td>
                    <td width="100%" style="border-bottom: 1px solid #000"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <br>
            <table width="100%">
                <tr>
                    <td width="15%" class="label-form">Phone Number:</td>
                    <td width="38%" align="left">
                        <table width="100%">
                            <tr>
                                <td>(</td>
                                <td width="25%" style="border-bottom: 1px solid #000"></td>
                                <td>)</td>
                                <td width="25%" style="border-bottom: 1px solid #000"></td>
                                <td>-</td>
                                <td width="48%" style="border-bottom: 1px solid #000"></td>
                            </tr>
                        </table>
                    </td>
                    <td width="2%"></td>
                    <td width="15%" class="label-form">Product Number:</td>
                    <td width="30%" style="border-bottom: 1px solid #000"></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <br>
            <table width="100%">
                <tr>
                    <td class="label-form">Customer Signature:</td>
                    <td width="100%" style="border-bottom: 1px solid #000"></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <br>
            <table width="100%">
                <tr>
                    <td width="100%" align="center" style="text-decoration: underline; font-size: 18px; font-weight: bold">
                        Homeowner's Information for Neighbors Property
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <br>
            <table width="100%">
                <tr>
                    <td class="label-form">Name(s):</td>
                    <td width="100%" style="border-bottom: 1px solid #000"></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <br>
            <table width="100%">
                <tr>
                    <td class="label-form">Address:</td>
                    <td width="100%" style="border-bottom: 1px solid #000"></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <br>
            <table width="100%">
                <tr>
                    <td width="5%" class="label-form">Phone:</td>
                    <td width="38%" align="left">
                        <table width="100%">
                            <tr>
                                <td>(</td>
                                <td width="25%" style="border-bottom: 1px solid #000"></td>
                                <td>)</td>
                                <td width="25%" style="border-bottom: 1px solid #000"></td>
                                <td>-</td>
                                <td width="48%" style="border-bottom: 1px solid #000"></td>
                            </tr>
                        </table>
                    </td>
                    <td width="2%"></td>
                    <td width="55%">
                        ** If more than one property must be crossed please include
                        additional homeowner information on a separate sheet of paper.<br>
                        Make sure to include signature of additional homeowners.
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <br>
            <table width="100%">
                <tr>
                    <td width="100%" align="left" style="font-size: 16px; font-weight: bold">
                        We hereby agree to allow {{ config('app.name') }} LLC to cross our property to deliver a storage structure to their
                        customer's property. {{ config('app.name') }} will not be responsible to pay any property damages that occur on my property.
                        This will be the responsibility of the customer named above.
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <br>
            <table width="100%">
                <tr>
                    <td width="5%" class="label-form" style="font-size: 14px !important;">Home Owners Signature:</td>
                    <td width="95%" style="border-bottom: 1px solid #000"></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td width="5%" class="label-form" style="font-size: 12px">&nbsp;</td>
                    <td width="95%" style="border-bottom: 1px solid #000"></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <br>
            <table width="100%">
                <tr>
                    <td width="100%" align="center" style="font-size: 16px; ">
                        If there is more than one homeowner's name on the property, all must sign.
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <br>
            <table width="100%">
                <tr>
                    <td width="100%" align="center">
                       <span style="background: yellow; font-size: 18px; font-weight: bold;">
                           After completion of this form please mail or hand deliver this form back to the location where your structure was purchased
                       </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>