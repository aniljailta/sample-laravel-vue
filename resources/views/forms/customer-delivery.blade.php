<style>
    #delivery-info-table td, #delivery-info-table tr {
        line-height: 14px;
    }
</style>

<table id="delivery-info-table" width="800" cellspacing="0">

    <tr>
        <td>

            <table width="800" cellspacing="0">
                <tr>
                    <td>
                        <h3>{{ config('app.name') }} Dispatch and Delivery Procedures for Your New Portable Building</h3>
                        <h4>For questions concerning your delivery your contact is:</h4>
                        @if($companySettings->delivery_dispatch === 'dispatch')
                            Name: {{ $companySettings->delivery_contact_name }}
                            <br> Phone: {{ $companySettings->delivery_contact_phone }}
                            <br> Email: {{ $companySettings->delivery_contact_email }}
                        @endif
                    </td>
                    <td width="33%">
                        <img src="{{ url('images/delivery.jpg') }}" alt="Delivery preview" style="width: 100%; float: none; clear: both" />
                    </td>
                </tr>
            </table>

        </td>
    </tr>

    <tr>
        <td>
            <br>
            Congratulations on the purchase of your {{ config('app.name') }} portable building! The final stage in the ordering process is the delivery of your
            building. <u>This is what you can expect:</u>
            <ul>
                <li>An <strong>estimated</strong> delivery period is indicated on your order.</li>
                <li>You will receive an email once your order has been processed.</li>
                <li>You will recceive a call from {{ config('app.name') }} Dispatch once a completion date has been determined for your structure to setup and confirm a date of delivery.</li>
            </ul>

            Your structure will be delivered on a truck and trailer similar to the truck pictured to above. If access to your property is limited, please inform
            dispatch. We use only specialized equipment for transporting backyard structures.
            If you are unable to accept delivery on the scheduled delivery day, please inform {{ config('app.name') }} Dispatch at your earliest convenience.
            This allows them to schedule other deliveries and also promptly reschedule your delivery. In the event that your property is too wet for delivery,
            please call the day prior to delivery to postpone. We will be entering your property at your request and will not be responsible if weather or site
            conditions are not favorable for delivery. If you postpone your delivery date or if weather conditions do not allow delivery on the scheduled day,
            please understand that we will need to reschedule the next available day.

            <br><u>You are required to provide:</u>

            <ul>
                <li>
                    Adequate access to the proposed building location.
                    <ul>
                        <li>Ensure that the delivery path is wide and high enough for the building (2' clearance for each dimension is suggested).</li>
                        <li>Ensure that the delivery path ground conditions are adequate to support the weight of the delivery equipment and portable
                            building</li>
                    </ul>
                </li>
                <li>
                    A level pad
                    <ul>
                        <li>This can be concrete, gravel or bare ground. If blocks are required for level building placement they must be provided by the customer</li>
                        <li>Placing the building on blocks voids the warranty</li>
                    </ul>
                </li>
            </ul>

            Setup includes 45 minutes of setup time. If the setup requires more time due to site accessibility or other issues, setup fees will be billed at the
            rate of $75/hour and payable at the time of delivery.

            <br>
            <u>{{ config('app.name') }} is not responsible for the following should they occur during the installation process of your structure:</u>
            <ul>
                <li>Tracks, ruts, damage to lawns, trees and/or branches, sidewalks, driveways, or buildings while attempting to install the structure.</li>
                <li>Damage to bordering property not owned by the customer, should access to customer's property require driving through privately
                    owned property.</li>
                <li>Damages to the structure during and after installation on customer's property due to site not being properly prepared and or
                    obstructions not being removed.</li>
            </ul>

            Should the truck and/or trailer and/or delivery equipment become stuck on customer's property, it will be the customer's responsibility to pay
            any fees for tow truck or other required equipment needed to free delivery equipment and/or place building in desired location. Customer is
            required to notify driver of any underground lines, cables, pipes, septic systems, etc. before delivery equipment enter your property. If delivery
            requires driving on neighboring property, written consent must be obtained before crossing the neighbor's property.
        </td>
    </tr>

    <tr>

        <td>
            <br>
            <span style="border-bottom: 1px solid #000000">
                <strong>
                I, {{ $orderReference->first_name or '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' }}
                    {{ $orderReference->last_name or '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' }}, agree to these terms, prior to installation of my structure.
                </strong>
            </span>
        </td>

    </tr>

    <tr>

        <td>
            <table width="100%" style="font-weight: bold">
                <tr>
                    <td width="5%" class="text-right" valign="bottom">Signature:</td>
                    <td width="40%" style="border-bottom: 1px solid #000">
                        <br>
                        <br>
                        <span style="color: #ffffff;">[sig|req|signer1|Customer Signature]</span>
                    </td>
                    <td width="5%" class="text-right" valign="bottom">Date:</td>
                    <td width="15%" style="border-bottom: 1px solid #000">
                        <br>
                        <br>
                        <span style="color: #ffffff;">[date|req|signer1|Date]</span>
                    </td>
                    <td width="15%"></td>
                </tr>
                <tr>
                    <td colspan="6"><br></td>
                </tr>

                <tr>
                    <td width="5%" class="text-right">Serial #:</td>
                    <td width="40%" style="border-bottom: 1px solid #000">
                        @if ($order->sale_type && $building->serial_number)
                            {{ $building->serial_number }}
                        @endif
                    </td>
                    <td colspan="3"></td>
                </tr>

                <tr>
                    <td colspan="5" style="">Will it be necessary to cross onto a neighbor's property: &nbsp; YES &nbsp; NO</td>
                </tr>
                <tr>
                    <td colspan="5" style="">{{ config('app.name') }} may use pictures of the delivery and setup for advertising: &nbsp; YES &nbsp; NO</td>
                </tr>
            </table>
        </td>

    </tr>


</table>