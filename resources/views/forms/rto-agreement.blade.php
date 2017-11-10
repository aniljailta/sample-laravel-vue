<style type="text/css">
    #rto-agreement ol.list-alpha {
        list-style-type: lower-alpha;
    }
</style>

<?php $empty64 = '________________________'; ?>
<?php $empty14 = '______________'; ?>
<?php $empty10 = '__________'; ?>
<?php $empty6 = '______'; ?>

<div id="rto-agreement">

    <p class="text-center">
        <strong>RENTAL-PURCHASE AGREEMENT</strong>
        <br>
        FOR USE IN ILLINOIS, INDIANA, KENTUCKY, MICHIGAN, ARIZONA
    </p>

    <p>This Agreement, made and entered into this {{ (isset($params['no_dates'])) ? $empty14 : date('j') }} day of {{ (isset($params['no_dates'])) ? $empty64 : date('F') }}, {{ (isset($params['no_dates'])) ? $empty14 : date('Y') }}, by and between
        JMAG, LLC, P.O. Box 282, Harrisburg, Illinois, 62946, phone (618) 294-9494, fax (618) 294-9497 hereinafter referred
        to as "Owner" and
        <strong>
            {!! $orderReference->first_name ?? $empty64 !!}
            {{ $orderReference->last_name ?? $empty64 }}</strong>,
        whose address is
        <strong>{{ $orderReference->address ?? $empty64 }}</strong>,
        <strong>{{ $orderReference->city ?? $empty64 }}</strong>,
        <strong>{{ $orderReference->state ?? $empty64 }}</strong>
        <strong>{{ $orderReference->zip ?? $empty14 }}</strong>
        hereinafter referred to as
        "Renter". <strong>THE RENTED PROPERTY WILL BE LOCATED AT THE FOLLOWING ADDRESS:</strong>
        {{ $buildingLocation['address'] ?? $empty64 }},
        {{ $buildingLocation['city'] ?? $empty64 }},
        {{ $buildingLocation['state'] ?? $empty64 }},
        {{ $buildingLocation['zip'] ?? $empty14 }}.
    </p>
    <p class="text-center"><strong><em>NOTICE</em></strong></p>
    <p>You, the undersigned Renter, is renting from JMAG, Owner, the property described below at the advance monthly rental
        renewal rate set forth below. JMAG owns the rented property described herein; you do not own the rented property and
        you will NOT acquire any ownership rights in the rented property unless you have, at your option, complied with the
        ownership terms set forth herein. "The rented property" means the property described below. "Agreement" means this
        Rental-Purchase Agreement. <strong>Any charge in addition to the advance monthly rental renewal rate must reasonably
            relate to the cost of the service performed.</strong></p>
    <ol>
        <li>
            GRANTING CLAUSE and DESCRIPTION OF RENTED PROPERTY: For and in consideration of the mutual covenants and
            agreements hereinafter set forth, Owner hereby rents to Renter and Renter hereby rents from Owner that certain
            portable warehouse and equipment hereinafter referred to as "the rented property", associated with unique manufacturer order
            #{{ $order->id ?? $empty6 }}, and identified as follows:
            <br>

            @if ($building->serial_number)
                <strong>{{ $building->serial_number }}</strong>
            @elseif ($building->serial_short_code && $building->serial_sizes)
                <strong>{{ $building->serial_short_code }}-{{ $building->serial_sizes }}</strong>
            @else
                ___________________________________________________________________________________________________________
            @endif
        </li>
        <li>SECURITY DEPOSIT: At the time of the execution of this Agreement, Renter shall pay to Owner a security deposit
            in the amount set forth in Paragraph 3(g) below to be held by Owner as security for the performance of all of
            the terms of this Agreement including, but not limited to, the payment of a redelivery charge. Said deposit, or
            so much thereof that has not been applied to remedy defaults of Renter, shall be refunded, without interest,
            only on the expiration of this Agreement if all the obligations of the Renter have been performed or discharged.
            There is a charge to pick up and/or redeliver the rented property. Owner may from time to time apply the deposit
            towards any breach by Renter of the terms of this Agreement; and, in the event of such application, upon demand
            by the Owner, Renter shall restore the deposit to its original amount.
        </li>
        <li>
            RENTAL PURCHASE AGREEMENT DISCLOSURES: The following information is hereby disclosed to the Renter pursuant to
            State Law and does hereby set forth the terms and conditions of this Agreement:
            <ol class="list-alpha">
                <li>The property to be rented, and which is the subject of this Agreement, is described in paragraph 1
                    above.
                </li>
                <li>The initial term of this Agreement is for four (4) months from the date of the Rental-Purchase
                    Agreement.
                </li>
                <li>Renter may renew this Agreement for consecutive one (1) month periods by making advance monthly rental
                    renewal payments for each additional month after the initial term that Renter wishes to rent the
                    property.
                </li>
                <li>Payments are due monthly in advance.</li>

                <li>
                    The rented property is
                    @if ($order->building_condition)
                        @if ($order->building_condition == 'new') <strong>new</strong>.@endif
                        @if ($order->building_condition == 'used') <strong>used</strong>. @endif
                    @else
                        _________
                    @endif
                </li>
                <!-- rto type -->
                    <li>
                        The Sales Price of the rented property is
                        <strong>{{ $order->txt_total_sales_price ?? $empty10 }}</strong> + sales tax.
                        <br>
                        Renter DOES elect to buydown the Sales Price, Renter elects to pay a Gross Buydown of
                        <strong>{{ $order->txt_gross_buydown ?? $empty10 }}</strong>, which includes a Security
                        Deposit of <strong>{{ $order->txt_security_deposit ?? $empty10 }}</strong>, leaving a Buydown
                        Balance of <strong>{{ $order->txt_net_buydown ?? $empty10 }}</strong> (gross buydown - security deposit). Which
                        consists of a Net Buydown of <strong>{{ $order->txt_rto_net_buydown ?? $empty10 }}</strong> (buydown
                        balance &divide;
                        {{ $order->txt_tax_rate ?? $empty6 }}
                        tax rate) and Sales Tax of
                        <strong>{{ $order->txt_buydown_tax ?? $empty10 }}</strong> (buydown balance - net buydown). Leaving an Adjusted
                        Rent-To-Own amount of <strong>{{ $order->txt_rto_amount ?? $empty10 }}</strong> (sales price -
                        net buydown) being the amount upon which the advance monthly rental renewal payments are based.
                    </li>
                    <li>
                        The total advance monthly renewal payment is
                        <strong>{{ $order->txt_rto_total_advance_monthly_renewal_payment ?? $empty10 }}</strong> (monthly
                        rent + sales tax).
                        <br>
                        The advance monthly renewal payment consists of
                        <strong>{{ $order->txt_rto_advance_monthly_renewal_payment ?? $empty10 }}</strong> rent and <strong>{{ $order->txt_rto_sales_tax ?? $empty10 }}</strong>
                        sales tax at <strong>{{ $order->txt_tax_rate ?? $empty6 }}</strong>.
                        <br>
                        The advance monthly rental renewal payment may change to reflect any sales tax rate changes enacted
                        by applicable governmental taxing authorities. If Renter does not buydown the Sales Price, the first
                        advance monthly rental renewal payment, in the amount set forth above, is due upon execution of this
                        Agreement.
                        <br>
                        Renter's advance monthly renewal payments will be due on the <strong>{{ (isset($params['no_dates'])) ? $empty6 : date('jS') }}</strong> day
                        of each month, beginning on <strong>{{ (isset($params['no_dates'])) ? $empty64 : date('F j, Y', strtotime('+1 month')) }}</strong>.
                        <br>
                        If Renter makes <strong>{{ $order->rto_term_params['value'] ?? $empty6 }}</strong> consecutive advance monthly
                        rental renewal payments, which would total
                        <strong>{{ $order->txt_rto_total_days_advance_monthly_renewal_payment ?? $empty10 }}</strong>,
                        the Total Rent to Own Price, and otherwise complies with this Agreement, Renter (you) will acquire
                        ownership of the rented property. The Total Rent to Own Price does NOT include any other fees that
                        may be charged. If you are current on your payments and you wish to purchase the rented property,
                        you may do so at any time by paying <strong>{{ $order->rto_term_params['remaining_percentage'] ?? $empty6 }}%</strong>
                        of the remaining Total Rent to Own Price plus sales tax and other fees and charges.
                    </li>
                <!-- rto type always buydown -->
                <li>Renter will not acquire any ownership in the rented property until Renter has made the requisite number
                    of advance monthly rental renewal payments set forth above, which payments total the amount needed to
                    acquire ownership of the rented property (The Total Rent to Own Price).
                </li>
                <li>Said advance monthly rental renewal payment(s) do not include other charges such as, but not limited to,
                    penalties, late payment fees, default, pick-up, delivery, redelivery or reinstatement fees.
                </li>
                <li>Renter shall be liable for the Total Rent to Own Price of the rented property, less advance monthly
                    rental renewal payments that have been made, if the rented property is lost, stolen, damaged,
                    vandalized, destroyed or mysteriously disappears.
                </li>
                <li>Renter is responsible for maintaining the rented property during the term of this Agreement.</li>
                <li>Renter shall not permit the rented property to be altered for the addition of, but not limited to,
                    shelves, equipment or any other accessories nor shall Renter paint or place any signs thereon. Renter
                    shall not permit the rented property to be tied to or otherwise affixed to any real estate in such a
                    manner that the rented property cannot be removed without damage to the rented property. Renter shall
                    not obstruct access to the rented property so as to prevent removal in the event of termination or
                    expiration of this Agreement. Renter shall not reside in nor shall Renter use the rented property as a
                    dwelling.
                </li>
                <li>If you want to purchase the rented property you may be able to get cash or credit terms from other
                    sources which may result in a lower total cost than the rental payments we require.
                </li>
            </ol>
        </li>
        <li>TERMINATION BY RENTER\OWNER RIGHT TO PICKUP RENTED PROPERTY: Renter is not obligated to renew this Agreement
            after the initial term. <strong>Renter may terminate this Agreement without penalty by notifying Owner, in
                writing at Owner's above address at least fifteen (15) days prior to the expiration of the initial or
                advance monthly term, that Renter is terminating this Agreement AND</strong> by voluntarily surrendering the
            rented property to Owner by 8:00 a.m. on the expiration of the initial or advance monthly term. If Owner is not
            able to pick up the rented property upon the termination or expiration date after proper notice from Renter,
            Renter shall not be responsible for any additional advance monthly rental renewal payments following Renter's
            termination of this Agreement; however, Renter agrees to safeguard the rented property until Owner picks up the
            rented property and Renter agrees that Owner shall have permission and access to the rented property following
            the expiration or termination of this Agreement. In the event Renter surrenders the rented property to Owner,
            Renter agrees the rented property shall be in the same condition that it was on the date of this Agreement,
            normal wear and tear excepted. In the event of termination by Renter, Renter will still owe Owner any past-due
            advance monthly rental renewal payments.
        </li>
        <li><strong>REINSTATEMENT: Renter shall have the right to reinstate this Agreement without losing any rights or
                options previously acquired by paying, but not limited to, any and all past-due advance monthly rental
                renewal payments, late fees, reasonable cost of pick-up and/or redelivery, and refurbishing, within fifteen
                (15) days of the due date of the payment. If Renter fails to make a delinquent advance monthly rental
                renewal payment within three (3) days of the due date, Renter will be required to pay a reinstatement fee of
                $5.00 in addition to the delinquent advance monthly rental renewal payment and any additional advance
                monthly rental renewal payment that has come due in order to reinstate this Agreement. If Renter does not
                reinstate a terminated Agreement within fifteen (15) days of the due date, Renter loses its right to
                reinstate this Agreement. In the case where Renter has returned or voluntarily surrendered the property,
                other than through judicial process, Renter's right to reinstate this Agreement shall be extended for a
                period of not less than thirty (30) days if the Renter has paid less than sixty percent (60%) of the total
                amount to be paid to acquire ownership of the rented property and shall be extended for a period of not less
                than sixty (60) days if the Renter has paid sixty percent (60%) or more of the total amount to be paid to
                acquire ownership of the rented property. Owner is not prevented from repossessing the rented property
                during the reinstatement period. Upon reinstatement, Owner shall provide Renter with the same rented
                property or with substitute property of comparable quality and condition. If substitute property is
                provided, Owner shall provide Renter with the disclosures required by the Rental-Purchase Agreement
                Act.</strong></li>
        <li>LOCATION OF RENTED PROPERTY: The rented property shall be kept in your\Renter's possession at the address shown
            above. The rented property may not be moved from the above address without the written consent of Owner;
            however, the rented property may only be moved by a carrier authorized, in writing, by Owner. Owner shall have
            sole discretion in determining authorized carriers. There will be a charge to move the rented property. If
            Renter moves the rented property without Owner's written consent, Renter shall be deemed to have breached this
            Agreement and Owner shall have, at Owner's sole discretion, the immediate right to possession of the rented
            property, the right to accelerate and demand the balance of the Total Rent to Own Price or waive the breach.
        </li>
        <li>TITLE AND MAINTENANCE: Owner retains title to the rented property at all times. Renter agrees to maintain,
            safeguard and protect the rented property until it is returned to Owner's actual possession.
        </li>
        <li>ASSIGNABILITY: Renter may not sell, transfer, subagreement or assign any of Renter's rights under this Agreement
            to any third party without the written consent of Owner, which consent shall not be unreasonably withheld.
        </li>
        <li>FORBIDDEN ACTS: Renter may not pawn, sell, lease, sublease or otherwise dispose of the rented property. If
            Renter does pawn, sell, lease, sublease or otherwise dispose of the rented property this Agreement will
            automatically terminate and Renter must pay Owner the balance of the Total Rent to Own Price. The rented
            property shall only be used for personal, family or household purposes.
        </li>
        <li>INSPECTION: Owner shall have the right to examine and inspect the rented property at all reasonable times. Owner
            shall have the right to remove the rented property in the event of non-payment, expiration, termination and/or
            default under this Agreement.
        </li>
        <li><strong>LIMITATION OF OWNER LIABILITY: Owner shall not be liable to Renter, nor any other person, firm or
                corporation, by reason of the loss of, damage to or destruction of any contents contained in the rented
                property unless such loss, damage or destruction is due to negligence of the Owner, its agents, servants or
                employees. In the event, and whether or not such loss, damage or destruction of the property kept in the
                rented property is due to the negligence of the Owner, its agents, servants, employees, or otherwise, the
                liability of the Owner shall not exceed the value of the rented property. Renter warrants and guarantees to
                Owner that no property in excess of the Total Rent to Own Price shall be placed in or stored in the rented
                property other than at the sole peril of the Renter.</strong></li>
        <li>HOLDER NOTICE: To the extent this Agreement may be deemed to be a consumer credit contract, you are notified of
            the following: ANY HOLDER OF THIS CONSUMER CONTRACT IS SUBJECT TO ALL CLAIMS AND DEFENSES WHICH THE DEBTOR COULD
            ASSERT AGAINST THE SELLER OF GOODS AND SERVICES OBTAINED HERETO OR WITH THE PROCEEDS HEREOF; however, recovery
            by the holder is limited to the amount paid by the holder hereunder.
        </li>
        <li>RENTER DUTY TO VACATE: Renter agrees to promptly remove all of Renter's personal belongings and property at the
            termination and/or expiration of this Agreement, whether such termination or expiration is caused by Renter's
            default, notice or by lapse of time. If Renter fails to remove any personal belongings or property after
            termination or expiration, said personal belongings or property shall be deemed abandoned. Owner shall not be
            responsible for said personal belongings or property and Owner may either discard, store at Renter's risk and
            expense and/or seize all or part of same without any payment or offset to Renter therefore.
        </li>
        <li><strong>RIGHT OF ACCESS\NO TRESPASS: This Agreement constitutes irrevocable written permission for Owner to
                enter Renter's property, open gates, move obstacles or take any reasonable means necessary to recover the
                rented property in the event of expiration, termination or default by Renter. By signing this Agreement,
                Renter hereby authorizes Owner, its agents, servants or employees, to enter upon the real estate upon which
                the rented property is located, and hereby authorizes Renter's, but not limited to, landlord, owner of the
                real estate where the rented property is located, and/or co-Renter to allow Owner, its agents, servants or
                employees, to enter the real estate where the rented property is located for the purpose of picking up or
                repossessing the rented property. Renter agrees that, until possession of the rented property is returned to
                Owner, Owner shall retain all rights set forth in this Paragraph regarding access regardless of whether this
                Agreement is surrendered, terminated, canceled or expires.</strong></li>
        <li>ATTORNEYS FEES, COSTS and EXPENSES: In the event Owner shall incur costs and expenses in enforcing the terms of
            this Agreement because of a breach thereof by Renter or by the agents, servants, employees, conspirators or
            co-conspirators of the Renter, the Owner shall recover from, and the Renter shall pay to Owner, all of the
            Owner's costs and expenses by reason thereof, including, but not limited to, Owner's reasonable attorney's fees.
            In the event Renter defaults in complying with the terms of this Agreement and Owner proceeds to repossess the
            rented property, <strong>if Renter pays the amount in arrears after Owner's repossessor has made the trip to
                repossess same, then Renter shall pay Owner, in addition to the payments in arrears, the sum of $200.00 in
                repossession fees; to clarify, if Owner has contracted with a repossessor to repossess the rented property,
                if repossessor has traveled to the location of the rented property before Renter pays the amount in arrears,
                repossessor will have earned a part of his fees.</strong></li>
        <li>NO OWNER WARRANTY: Renter acknowledges that Owner has made no representations, warranties, or promises of any
            kind or nature, either expressed or implied, as to the condition, quality, suitability, or fitness of the rented
            property for a particular purpose.
        </li>
        <li>JURISDICTION and GOVERNING LAW: This Agreement shall be construed and enforced in accordance with the laws of
            the State of Illinois: and, it is agreed between Renter and Owner that venue shall be in Saline County,
            Illinois.
        </li>
        <li>ENTIRETIES CLAUSE: This Agreement and Renter's Disclosure\Application contains the entire Agreement between
            Renter and Owner and supersedes and replaces any and all other agreements, written or oral, made at any time
            between Renter and Owner. This Agreement shall not be altered or amended absent a writing executed by all of the
            parties.
        </li>
        <li>SEVERABILITY: In the event that any clause or provision of this Agreement is illegal, invalid or unenforceable
            under present or future laws effective during the term of this Agreement, then, in such event, it is the
            intention of Renter and Owner that the remainder of this Agreement shall not be affected thereby.
        </li>
        <li>ACKNOWLEDGMENT: By executing this Agreement, Renter acknowledges and agrees that Renter has read and understands
            this Agreement, or that someone has read and\or interpreted this Agreement for Renter; and, that Renter fully
            understands the terms, conditions and consequences of this Agreement. <strong>Owner represents to Renter that
                Owner will forward a FULLY signed copy of this Rental Purchase Agreement to Renter when Owner forwards the
                upcoming payment coupons and rental packet to Renter.</strong></li>
    </ol>
    <p>IN WITNESS WHEREOF, Renter and Owner have hereunto affixed their signatures as of the day and year first above
        written.</p>
    <table width="800">
        <tr>
            <td width="45%">
                <table width="100%">
                    <tr>
                        <td style="border-bottom: 1px solid #000;">
                            <br>
                            <br>
                            <span style="color: #ffffff;">[sig|req|signer2|RTO Company Signature]</span>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            JMAG, LLC
                            <br>
                            (618) 294-9494
                        </td>
                    </tr>
                </table>
            </td>
            <td width="10%"></td>
            <td width="45%">
                <table width="100%">
                    <tr>
                        <td width="10%" valign="bottom">Renter:</td>
                        <td width="5%"></td>
                        <td width="85%" style="border-bottom: 1px solid #000">
                            <br>
                            <br>
                            <span style="color: #ffffff;">[sig|req|signer1|Customer Signature]</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"><br></td>
                    </tr>
                    <tr>
                        <td width="10%" valign="bottom">Renter:</td>
                        <td width="5%"></td>
                        <td width="85%" style="border-bottom: 1px solid #000">
                            <br>
                            <br>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</div>