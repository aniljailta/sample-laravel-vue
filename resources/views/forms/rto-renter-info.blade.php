<style type="text/css">

    #rto-renter-info-table table.bordered-cells {
        margin: 0;
        padding: 0;
        border-collapse: collapse;
        border-spacing: 0;
    }

    #rto-renter-info-table * {
        font-size: 11px !important;
        letter-spacing: -0.5px;
    }

    #rto-renter-info-table h3 {
        margin: 0.5em 0 0;
        padding: 0;
        font-size: 14px;
    }

    #rto-renter-info-table td {
        padding: 2px 1px 2px 0;
    }

    #rto-renter-info-table .field-title {
        font-weight: bold;
        white-space: nowrap;
    }

    #rto-renter-info-table .input-form {
        display: inline-block;
        width: 100%;
        border: 1px outset #000;
        padding: 3px;
    }

    #rto-renter-info-table .input-form::after {
        content: ".";
        visibility: hidden;
    }

</style>

<table id="rto-renter-info-table" width="800" cellspacing="0">
    <!-- Main -->
    <tr>
        <td>
            <table class="bordered-cells" width="100%">
                <tr>
                    <td width="20%" class="field-title">*Renter:</td>
                    <td width="30%"><span class="input-form">{{ $orderReference->first_name ?? '' }} {{ $orderReference->last_name  ?? ''}}</span></td>
                    <td width="20%" class="text-right">Co-Renter:</td>
                    <td width="30%">
                        <span class="input-form">
                            {{ $orderReference->co_renter_first_name ?? '' }} {{ $orderReference->co_renter_last_name  ?? ''}}
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="bordered-cells" width="100%">
                <tr>
                    <td width="20%" class="field-title">*Mailing Address:</td>
                    <td width="80%">
                        <span class="input-form">
                            {{ $orderReference->address ?? '' }}
                            @if(!empty($orderReference->apartment_number))
                                 (apt. {{ $orderReference->apartment_number }})
                            @endif
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="bordered-cells" width="100%">
                <tr>
                    <td width="20%" class="field-title">*City / State / Zip:</td>
                    <td width="80%">
                        <span class="input-form">
                            @if($orderReference->city)
                            {{ $orderReference->city }},
                            @endif

                            @if($orderReference->state)
                                {{ $orderReference->state }},
                            @endif

                            @if($orderReference->zip)
                                {{ $orderReference->zip }},
                            @endif
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="bordered-cells" width="100%">
                <tr>
                    <td width="20%" class="field-title">*Building Located at:</td>
                    <td width="80%">
                        <span class="input-form">
                            @if($orderReference->building_in_same_address)
                                {{ $orderReference->address }}
                            @else
                                {{ $orderReference->building_location_address }}
                            @endif
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="bordered-cells" width="100%">
                <tr>
                    <td width="20%" class="field-title">*City / State / Zip:</td>
                    <td width="80%">
                        <span class="input-form">
                                @if($orderReference->building_in_same_address)
                                    @if(!empty($orderReference->city)) {{ $orderReference->city }}, @endif
                                    @if(!empty($orderReference->state)) {{ $orderReference->state }}, @endif
                                    @if(!empty($orderReference->zip)) {{ $orderReference->zip }} @endif
                                @else
                                    @if(!empty($orderReference->building_location_city)) {{ $orderReference->building_location_city }}, @endif
                                    @if(!empty($orderReference->building_location_state)) {{ $orderReference->building_location_state }}, @endif
                                    @if(!empty($orderReference->building_location_zip)) {{ $orderReference->building_location_zip }} @endif
                                @endif
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="bordered-cells" width="100%">
                <tr>
                    <td width="20%" class="field-title">*Own or Rent?</td>
                    <td width="18%">
                        <span class="input-form">
                            @if($orderReference->property_ownership)
                                {{ ucfirst($orderReference->property_ownership) }}
                            @endif
                        </span>
                    </td>
                    <td width="12%" class="text-right">Landlord:</td>
                    <td width="25%">
                        <span class="input-form">
                             @if($orderReference->property_ownership)
                                @if($orderReference->property_ownership === 'rent')
                                    {{ $orderReference->landlord_full_name }}
                                @endif
                             @endif
                        </span>
                    </td>
                    <td width="10%" class="text-right">Phone:</td>
                    <td width="15%">
                        <span class="input-form">
                            @if($orderReference->property_ownership)
                                @if($orderReference->property_ownership === 'rent')
                                    {{ $orderReference->landlord_phone_number }}
                                @endif
                            @endif
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="bordered-cells" width="100%">
                <tr>
                    <td width="20%">Cell #:</td>
                    <td width="18%"><span class="input-form">{{ $orderReference->phone_number ?? '' }}</span></td>
                    <td width="12%" class="text-right">Accept Text?</td>
                    <td width="15%"><span class="input-form">
                            @if($orderReference->text_allowed1)
                                {{ ucfirst($orderReference->text_allowed1) }}
                            @endif
                        </span>
                    </td>
                    <td rowspan="2" width="35%" style="padding: 0.5em; font-size: 10px">
                        By indicating 'Yes', you consent to receive text messages sent
                        by an automatic telephone dialing system.
                    </td>
                </tr>
                <tr>
                    <td width="20%">Cell #:</td>
                    <td width="18%"><span class="input-form">{{ $orderReference->cell_phone_number2 ?? '' }}</span></td>
                    <td width="12%" class="text-right">Accept Text?</td>
                    <td width="15%"><span class="input-form">
                            @if($orderReference->text_allowed2)
                                {{ ucfirst($orderReference->text_allowed2) }}
                            @endif
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="bordered-cells" width="100%">
                <tr>
                    <td width="20%">Home #:</td>
                    <td width="18%"><span class="input-form">{{ $orderReference->home_phone_number ?? '' }}</span></td>
                    <td width="12%" class="text-right">Email:</td>
                    <td width="50%"><span class="input-form">{{ $orderReference->email ?? '' }}</span></td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- /Main-->

    <!-- Renter Information -->

    <!-- /Renter Information -->

    <!-- Co-Renter Information -->

    <!-- /Co-Renter Information -->

    <!-- Work Information -->
    <tr><td><h3>Work Information</h3></td></tr>
    <!-- Renter Work Info -->
    <tr>
        <td>
            <table class="bordered-cells" width="100%">
                <tr>
                    <td width="20%">Renter's Employer:</td>
                    <td width="45%"><span class="input-form">{{ $orderReference->renter_employer ?? '' }}</span></td>
                    <td width="10%" class="text-right">Phone:</td>
                    <td width="15%"><span class="input-form">{{ $orderReference->renter_employer_phone_number ?? '' }}</span></td>
                    <td width="3%" class="text-right">Ext:</td>
                    <td width="7%"><span class="input-form">{{ $orderReference->renter_employer_phone_extension ?? '' }}</span></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="bordered-cells" width="100%">
                <tr>
                    <td width="20%">Renter's Supervisor:</td>
                    <td width="45%"><span class="input-form">{{ $orderReference->renter_supervisor ?? '' }}</span></td>
                    <td width="10%" class="text-right">Occupation:</td>
                    <td width="25%"><span class="input-form">{{ $orderReference->renter_supervisor_occupation ?? '' }}</span></td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- Co-Renter Work Info -->
    <tr>
        <td>
            <table class="bordered-cells" width="100%">
                <tr>
                    <td width="20%">Co-Renter's Employer:</td>
                    <td width="45%"><span class="input-form">{{ $orderReference->co_renter_employer ?? '' }}</span></td>
                    <td width="10%" class="text-right">Phone:</td>
                    <td width="15%"><span class="input-form">{{ $orderReference->co_renter_employer_phone_number ?? '' }}</span></td>
                    <td width="3%" class="text-right">Ext:</td>
                    <td width="7%"><span class="input-form">{{ $orderReference->co_renter_employer_phone_extension ?? '' }}</span></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="bordered-cells" width="100%">
                <tr>
                    <td width="20%">Co-Renter's Supervisor:</td>
                    <td width="45%"><span class="input-form">{{ $orderReference->co_renter_supervisor ?? '' }}</span></td>
                    <td width="10%" class="text-right">Occupation:</td>
                    <td width="25%"><span class="input-form">{{ $orderReference->co_renter_supervisor_occupation ?? '' }}</span></td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- /Work Information -->

    <!-- References -->
    <tr><td><h3>References <small>(Every application requires two references)</small></h3></td></tr>
    <tr>
        <td>
            <table class="bordered-cells" width="100%">
                <tr>
                    <td width="20%">Name:</td>
                    <td width="25%"><span class="input-form">{{ $orderReference->reference1_name ?? '' }}</span></td>
                    <td width="15%" class="text-right">Relationship:</td>
                    <td width="15%"><span class="input-form">{{ $orderReference->reference1_relationship ?? '' }}</span></td>
                    <td width="10%" class="text-right">Phone #:</td>
                    <td width="15%"><span class="input-form">{{ $orderReference->reference1_phone_number ?? '' }}</span></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="bordered-cells" width="100%">
                <tr>
                    <td width="20%">Address:</td>
                    <td width="25%"><span class="input-form">{{ $orderReference->reference1_address ?? '' }}</span></td>
                    <td width="15%" class="text-right">City, State, Zip:</td>
                    <td width="40%">
                        <span class="input-form">
                            @if(!empty($orderReference->reference1_city))
                                {{ $orderReference->reference1_city }},
                            @endif
                            @if(!empty($orderReference->reference1_state))
                                {{ $orderReference->reference1_state }},
                            @endif
                            @if(!empty($orderReference->reference1_zip) )
                                {{ $orderReference->reference1_zip }},
                            @endif
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="bordered-cells" width="100%">
                <tr>
                    <td width="20%">Name:</td>
                    <td width="25%"><span class="input-form">{{ $orderReference->reference2_name ?? '' }}</span></td>
                    <td width="15%" class="text-right">Relationship:</td>
                    <td width="15%"><span class="input-form">{{ $orderReference->reference2_relationship ?? '' }}</span></td>
                    <td width="10%" class="text-right">Phone #:</td>
                    <td width="15%"><span class="input-form">{{ $orderReference->reference2_phone_number ?? '' }}</span></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="bordered-cells" width="100%">
                <tr>
                    <td width="20%">Address:</td>
                    <td width="25%"><span class="input-form">{{ $orderReference->reference2_address ?? '' }}</span></td>
                    <td width="15%" class="text-right">City, State, Zip:</td>
                    <td width="40%">
                        <span class="input-form">
                                @if(!empty($orderReference->reference2_city))
                                    {{ $orderReference->reference2_city }},
                                @endif
                                @if(!empty($orderReference->reference2_state))
                                    {{ $orderReference->reference2_state }},
                                @endif
                                @if(!empty($orderReference->reference2_zip) )
                                    {{ $orderReference->reference2_zip }},
                                @endif
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- /References -->

    <!-- Signatures -->
    <tr><td><h3>Signatures</h3></td></tr>
    <tr>
        <td>
            <table class="bordered-cells" width="100%" style="margin-top: 3em">
                <tr>
                    <td width="55%" style="border-bottom: 1px solid #000">
                        <span style="color: #ffffff;">[sig|req|signer1|Customer Signature]</span>
                    </td>
                    <td width="15%"></td>
                    <td width="15%" style="border-bottom: 1px solid #000">
                        <span style="color: #ffffff;">[date|req|signer1|Date]</span>
                    </td>
                    <td width="15%"></td>
                </tr>
                <tr>
                    <td width="55%">Renter</td>
                    <td width="15%"></td>
                    <td width="15%">Date</td>
                    <td width="15%"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table class="bordered-cells" width="100%" style="margin-top: 3em">
                <tr>
                    <td width="55%" style="border-bottom: 1px solid #000"></td>
                    <td width="15%"></td>
                    <td width="15%" style="border-bottom: 1px solid #000"></td>
                    <td width="15%"></td>
                </tr>
                <tr>
                    <td width="55%">Co-Renter</td>
                    <td width="15%"></td>
                    <td width="15%">Date</td>
                    <td width="15%"></td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- /Signatures -->

    <!-- Notice -->
    <tr>
        <td>
            <table class="bordered-cells" width="100%" style="margin-top: 3em; border: 1px inset #000">
                <tr>
                    <td class="text-center"><strong>What to Expect</strong></td>
                </tr>
                <tr>
                    <td class="text-center">
                        This is not a credit check application, it is used by JMAG LLC to setup your RTO account. After your account is created, you will receive a
                        coupon book with payment instructions via mail. This will generally be within two weeks of the date of this application. JMAG LLC offers
                        multiple payment options, including paying by credit card, paying by text, and automatic debits. If you have any questions about RTO
                        account setup or payments, feel free to contact JMAG LLC via the information listed at the top of this application. If you have questions
                        about your building or delivery, please contact your dealer or the manufacturer.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- /Notice -->
</table>