<style type="text/css">

    #rto-customer-receipt dl dt {
        font-weight: bold;
    }

    #rto-customer-receipt dl dd {
        margin-bottom: 0.5em;
    }

    #rto-customer-receipt h1 {
        font-size: 52px;
    }

    #rto-customer-receipt h3 {
        margin: 0;
        padding: 0;
    }

</style>

<div id="rto-customer-receipt">
    <h1 class="text-center">CUSTOMER RECEIPT</h1>
    <p class="text-center">
        <strong>(For Security Deposit and First Advance Monthly Rental Renewal Payment)</strong>
    </p>

    <table width="800">
        <tr>
            <td width="20%" align="right" valign="top">Date:</td>
            <td width="30%" valign="top" style="border-bottom: 1px solid #000;">{{ (isset($params['no_dates'])) ? '' : date('F j, Y') }}</td>
            <td width="2%" valign="top"></td>
            <td width="20%" valign="top"></td>
            <td width="28%" valign="top"></td>
        </tr>
        <tr>
            <td align="right" valign="top">Renter:</td>
            <td valign="top" style="border-bottom: 1px solid #000;">{{ $orderReference->first_name ?? '' }} {{$orderReference->last_name ?? '' }}</td>
            <td valign="top"></td>
            <td align="right" valign="top">Phone:</td>
            <td valign="top" style="border-bottom: 1px solid #000;">{{ $orderReference->phone_number  ?? '' }}</td>
        </tr>
        <tr>
            <td align="right" valign="top">Dealer Phone:</td>
            <td valign="top" style="border-bottom: 1px solid #000;">{{ $dealer->phone ?? '' }}</td>
            <td valign="top"></td>
            <td align="right" valign="top">Location:</td>
            <td valign="top" style="border-bottom: 1px solid #000;">{{ $dealer->location->name ?? '' }}</td>
        </tr>
        <tr>
            <td align="right" valign="top">Security Deposit:</td>
            <td valign="top" style="border-bottom: 1px solid #000;">{{ $order->txt_rto_deposit ?? '' }}</td>
            <td valign="top"></td>
            <td align="right" valign="bottom">Advance Monthly Rental:</td>
            <td valign="top" style="border-bottom: 1px solid #000;">{{ $order->txt_rto_advance_monthly_renewal_payment ?? '' }}</td>
        </tr>
        <tr>
            <td align="right" valign="top">Total amount paid:</td>
            <td valign="top" style="border-bottom: 1px solid #000;">{{ $order->txt_amount_received ?? '' }}</td>
            <td valign="top"></td>
            <td align="right" valign="top">Renewal Payment:</td>
            <td valign="top" style="border-bottom: 1px solid #000;">{{ $order->txt_rto_total_advance_monthly_renewal_payment ?? '' }}</td>
        </tr>
        <tr>
            <td align="right" valign="top">Paid by:</td>
            <td valign="top" style="border-bottom: 1px solid #000;">
                @if ($order->payment_method)
                    <ul style="list-style-type:none; padding-left: 0;">
                        <li>Check
                            @if( $order->payment_method == 'check' ) <span style="border-bottom: 1px solid black">&nbsp;&nbsp; &#x2713; {{ ($order->transaction_id) ? $order->transaction_id : ''}} &nbsp;&nbsp;</span> @endif
                        </li>
                        <li>Cash
                            @if( $order->payment_method == 'cash' ) <span style="border-bottom: 1px solid black">&nbsp;&nbsp; &#x2713; &nbsp;&nbsp;</span> @endif
                        </li>
                        <li>Credit Card
                            @if( $order->payment_method == 'credit_card' ) <span style="border-bottom: 1px solid black">&nbsp;&nbsp; &#x2713; {{ ($order->transaction_id) ? $order->transaction_id : ''}} &nbsp;&nbsp;</span> @endif
                        </li>
                    </ul>
                @endif
            </td>
            <td valign="top"></td>
            <td valign="top"></td>
            <td valign="top"></td>
        </tr>
    </table>

    <h3 class="text-center">Frequently Asked Questions</h3>

    <dl>
        <dt>When will I receive my building?</dt>
        <dd>JMAG does not manufacture, schedule delivery, or deliver the buildings. You will need to contact either the lot
            or the manufacturer to find out when the building will be ready for delivery.
        </dd>
        <dt>Do I get a copy of the signed Rental-Purchase Agreement today?</dt>
        <dd>JMAG needs to review the Rental-Purchase Agreement before it is processed. The dealer may be able to provide you
            with a copy, however you will be receiving a signed copy with you payment packet.
        </dd>
        <dt>How do I know when and where to make my payments?</dt>
        <dd>In a few weeks JMAG will send you a Payment Packet that will contain your payment coupons and envelopes. The
            Payment Packet will also contain a signed copy of your Rental-Purchase Agreement.
        </dd>
        <dt>Are early and/or extra payments in my best financial interest; Does JMAG offer "90 Days Same as Cash"?</dt>
        <dd>Your payments are not based on principal and interest - your payments are allocated between (a) rent and (b)
            your purchase of the building. <strong>As such, if you plan on paying off the building early, it is NOT in your
                best financial interest to make any additional, early and/or extra payments in excess of your advance
                monthly rental renewal payment.</strong> JMAG <strong>DOES NOT AND WILL NEVER OFFER "90 DAYS SAME AS
                CASH"</strong>. JMAG buys the building from the manufacturer at the <strong><em>exact</em></strong> same
            price as you would; as such, JMAG cannot pay for the building and then sell it to you 90 days later for the same
            price.
        </dd>
    </dl>
</div>