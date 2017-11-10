<!doctype html>
{{-- <html class="fixed js header-dark sidebar-left-xs darkfixed js flexbox flexboxlegacy no-touch csstransforms csstransforms3d no-overflowscrolling no-mobile-device custom-scroll sidebar-left-xs1 dark1"> --}}
<html class="fixed header-dark">
@include('partials.htmlheader')
<style>
    ol {
        counter-reset: item;
    }

    .sub-list {
        margin-top: 15px;
    }

    .sub-list > ol {
        margin-top: 7px;
    }

    .sub-list > ol > li {
        margin-bottom: 3px;
    }

    .no-border > tbody > tr > td {
        width: 50%;
        border: none;
    }

    ol li {
        display: block
    }

    ol li:before {
        content: counters(item, ".") ". ";
        counter-increment: item
    }
</style>
<body class="loading-overlay-showing" data-loading-overlay>
<!-- Start: Loading Overlay -->
<div class="loading-overlay dark">
    <div class="loader white"></div>
</div>
<!-- End: Loading Overlay -->

<section class="body">

    <!-- start: main header -->
@include('partials.mainheader')
<!-- end: main header -->

    <div class="inner-wrapper">

        <div class="col-xs-offset-2 col-xs-8 ">
            <section class="text-center panel-featured">
                <form method="post" action="{{ route('company.agreement.store') }}">
                    {{ csrf_field() }}
                    <header class="panel-heading clearfix">
                        <h3>SAAS SERVICES ORDER FORM</h3>
                        <img height="70" src="{{ asset('images/logo_black.png') }}">
                    </header>
                    <div class="panel-body">
                        <table class="table text-left table-bordered table-condensed no-footer">
                            <tr>
                                <td>Customer: {{ $company->company->name }}</td>
                                <td>Contact: {{ $user->full_name }}</td>
                            </tr>
                            <tr>
                                <td>Address: {{ $company->company->address }}</td>
                                <td>Phone: {{ $company->company->phone }}</td>
                            </tr>
                            <tr>
                                <td>{{ $company->company->city }} {{ $company->company->state }} {{ $company->company->zip }}</td>
                                <td>Email: {{ $company->company->email }}</td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>Services:</strong> Providing Customer and its Users cloud-based
                                    access to
                                    and use of The Shed
                                    App System (as defined in the Agreement) (the “Service(s)”).
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    @if(array_key_exists('base', $fees))
                                    <strong> Base Subscription per Month
                                        Fee:</strong> ${{ number_format($fees['base']->pivot->fee_amount, 2) }}<br><br>
                                    @endif
                                    @if(array_key_exists('per_sale', $fees))
                                        <strong> Base Subscription per Sale
                                            Fee:</strong> ${{ number_format($fees['per_sale']->pivot->fee_amount, 2) }}<br><br>
                                    @endif
                                    @if(array_key_exists('3d_subscription', $fees))
                                    <strong> 3D
                                        Subscription Fees:</strong> ${{ number_format($fees['3d_subscription']->pivot->fee_amount, 2) }} <br><br>
                                    @endif
                                    @if(array_key_exists('payment_processing', $fees))
                                    <strong>
                                        Payment Processing Fee:</strong> {{ $fees['payment_processing']->pivot->fee_amount }}% of total dollars processed via Pay Now
                                    @endif
                                </td>
                                <td>
                                    <strong>Initial Service Term:</strong> From Effective Date through December 31 of
                                    that calendar
                                    year – See Section 6.1
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <strong>Onboarding Services:</strong> THE SHED APP will use commercially reasonable
                                    efforts to
                                    provide Customer the services described in the Statement of Work (“SOW”) attached as
                                    Exhibit A
                                    hereto (“Onboarding Services”), and Customer will pay THE SHED APP the Onboarding
                                    Fee in
                                    accordance with the terms of this Agreement.<br><br>
                                    @if(array_key_exists('onboarding_fee_base', $fees))
                                    <strong>
                                        Onboarding Fee-Base Subscription (one-time):</strong> ${{ number_format($fees['onboarding_fee_base']->pivot->fee_amount, 2) }}<br><br>
                                    @endif
                                    @if(array_key_exists('onboarding_fee_3d', $fees))
                                    <strong>
                                        Onboarding Fee-3D Subscription (one-time):</strong> ${{ number_format($fees['onboarding_fee_3d']->pivot->fee_amount, 2) }}
                                    @endif
                                </td>
                            </tr>
                        </table>

                        <h3>SAAS SERVICES AGREEMENT</h3>
                        <p class="text-left">This SAAS SERVICES AGREEMENT (“Agreement”) is entered into on
                            this {{ $today->format('jS') }} day of
                            {{ $today->format('F') }}, {{ $today->year }} (the
                            “Effective Date”) by and between ROXLEY, LLC d/b/a THE SHED APP (“THE SHED APP” or
                            "Company")), and the Customer listed above (“Customer”)
                            (collectively, the "Parties"). This Agreement includes and incorporates the Order Form
                            above, as well as
                            the attached Terms and Conditions and Exhibits and THE SHED APP's Policies. There will be no
                            force or
                            effect to any different terms of any related purchase order or similar form even if signed
                            by the
                            Parties after the date written above.
                        </p>

                        <table class="table text-left no-border no-footer">
                            <tr>
                                <td class="text-center"><strong>ROXLEY, LLC d/b/a THE SHED APP</strong></td>
                                <td class="text-center"><strong>{{ $company->company->name }}</strong></td>
                            </tr>
                            <tr>
                                <td><br></td>
                                <td><br></td>
                            </tr>
                            <tr>
                                <td>By: Roxley LLC d/b/a The Shed App</td>
                                <td>By: {{ $company->company->name }}</td>
                            </tr>
                            <tr>
                                <td>Email: <a href="mailto:simplify@theshedapp.com">simplify@theshedapp.com</a> </td>
                                <td>Name: {{ $user->full_name }}</td>
                            </tr>
                            <tr>
                                <td>Phone: 602.529.6838</td>
                                <td>Title: {{ $user->title }}</td>
                            </tr>
                        </table>

                        <br>

                        <h4><u>TERMS AND CONDITIONS</u></h4>
                        <ol class="text-left">
                            <li class="sub-list">DEFINITIONS
                                <ol>
                                    <li>Buyer" means Customer's customer who has entered into a rent-to-own contract or
                                        other
                                        purchase transaction with Customer to purchase a product from Customer,
                                        involving payments
                                        (including periodic payments), maintenance of insurance and other requirements.
                                    </li>
                                    <li>“Customer Data” means data in electronic form input or collected through The
                                        Shed App System
                                        by or from Customer, including but not limited to by Customer’s Users.
                                    </li>
                                    <li>“Documentation” means THE SHED APP’s standard manual and other documents related
                                        to use of
                                        the System.
                                    </li>
                                    <li>“Equipment” means equipment needed to connect to, access or otherwise use the
                                        Services,
                                        including but not limited to, modems, hardware, servers, software, operating
                                        systems,
                                        networking, web servers and the like.
                                    </li>
                                    <li>"MST" means Mountain Standard Time.</li>
                                    <li>"Policies" means THE SHED APP's then current standard published policies,
                                        including website
                                        terms and conditions and privacy policy.
                                    </li>
                                    <li>"Sale" means a single rent-to-own or other purchase transaction between the
                                        Customer and its
                                        Buyer to be entered into the System.
                                    </li>
                                    <li>“Software” means source code, object code or underlying structure, ideas,
                                        know-how or
                                        algorithms, software, documentation or data related to the System.
                                    </li>
                                    <li>“Term” has the meaning set forth in Section 6.1 below.</li>
                                    <li>“The Shed App System” or "the System" means THE SHED APP’s cloud-based Software
                                        for
                                        tracking, processing, managing and monitoring of activities related to
                                        rent-to-own and other
                                        purchase transactions.
                                    </li>
                                    <li>“User” means any individual who uses the System on Customer’s behalf or through
                                        Customer’s
                                        account or passwords, whether authorized or not.
                                    </li>
                                </ol>
                            </li>

                            <li class="sub-list">SAAS SERVICES AND SUPPORT
                                <ol>
                                    <li>Subject to the terms of this Agreement, THE SHED APP will use commercially
                                        reasonable efforts to provide Customer the Services in accordance with the
                                        Service Level Terms attached hereto as Exhibit B. As part of the registration
                                        process, Customer will identify an administrative user name and password for
                                        Customer’s THE SHED APP account. THE SHED APP reserves the right to cancel or
                                        refuse to register passwords it deems inappropriate.
                                    </li>
                                    <li>Subject to the terms hereof, THE SHED APP will provide Customer with reasonable
                                        technical support services in accordance with its standard practice as set forth
                                        in Exhibit B.
                                    </li>
                                    <li>THE SHED APP will use commercially reasonable efforts to maintain the security
                                        of the Customer Data, including backing up the data at least daily and
                                        maintaining separate backup files. In the event that Customer is unable to
                                        access the Customer Data due to problems with communication facilities, a breach
                                        in the System, or similar interruption in service, THE SHED APP will act to
                                        restore Customer Data within three (3) days.
                                    </li>
                                </ol>
                            </li>

                            <li class="sub-list">RESTRICTIONS AND RESPONSIBILITIES
                                <ol>
                                    <li>Customer will not, directly or indirectly: reverse engineer, decompile,
                                        disassemble or otherwise attempt to discover any information or details about
                                        the Software or the System; modify, translate, or create derivative works based
                                        on the Services or any Software (except to the extent expressly permitted by THE
                                        SHED APP or authorized within the Services); use the Services or any Software
                                        for timesharing or service bureau purposes or otherwise for the benefit of a
                                        third party or remove any proprietary notices or labels.
                                    </li>
                                    <li>Customer will comply with all applicable laws, including but not limited to laws
                                        governing the protection of personally identifiable information and other laws
                                        applicable to the protection of Customer Data. Customer will not: (a) permit any
                                        third party to access or use the System in violation of any U.S. law or
                                        regulation; or (b) export any Software provided by THE SHED APP or otherwise
                                        remove it from the United States except in compliance with all applicable U.S.
                                        laws and regulations. Without limiting the generality of the foregoing, Customer
                                        will not permit any third party to access or use the System in, or export such
                                        Software outside the United States in violation of U.S. law.
                                    </li>
                                    <li>Customer will comply with THE SHED APP’s requirements for acceptable use.
                                        Customer will not: (a) use the System for service bureau or time-sharing
                                        purposes or in any other way allow third parties to exploit the System; (b)
                                        provide System passwords or other log-in information to any third party; (c)
                                        share non-public System features or content with any third party; or (d) access
                                        the System or draw from it in order to build a competitive product or service,
                                        to build a product using similar ideas, features, functions or graphics of the
                                        System, or to copy or draw from any ideas, features, functions or graphics of
                                        the System; (e) or work with a third-party either directly or indirectly to do
                                        any of the foregoing. In the event that it suspects any breach of the
                                        requirements of this Section 3.3, including without limitation by Users, THE
                                        SHED APP may suspend Customer’s access to the System without advanced notice, in
                                        addition to such other remedies as THE SHED APP may have.
                                    </li>
                                    <li>Customer will take reasonable steps to prevent unauthorized access to the System
                                        or introduction of viruses and malware, including without limitation by
                                        protecting its passwords and other log-in information. Customer will require all
                                        its Users to comply with sound practices for password protection and management,
                                        virus protection and detection, and User training to avoid introduction of
                                        viruses and malware. Customer will notify THE SHED APP immediately of any known
                                        or suspected unauthorized use of the System or breach of its security and will
                                        use best efforts to stop said breach.
                                    </li>
                                    <li>Customer represents, covenants, and warrants that Customer wiąll use the
                                        Services only in compliance with this Agreement and THE SHED APP’s Policies and
                                        all applicable laws and regulations. Although THE SHED APP has no obligation to
                                        monitor Customer’s use of the Services, THE SHED APP may do so and may prohibit
                                        any use of the Services it believes may be (or alleged to be) in violation of
                                        the foregoing.
                                    </li>
                                    <li>Customer will be responsible for obtaining and maintaining any Equipment and
                                        ancillary services needed to connect to, access or otherwise use the Services.
                                        Customer will also be responsible for maintaining the security of the Equipment,
                                        Customer account, passwords (including but not limited to administrative and
                                        user passwords) and files, and for all uses of Customer account or the Equipment
                                        with or without Customer’s knowledge or consent.
                                    </li>
                                </ol>
                            </li>

                            <li class="sub-list">PROPRIETARY RIGHTS; CONFIDENTIALITY
                                <ol>
                                    <li>Customer owns and retains all right, title and interest in and to the Customer
                                        Data, as well as any data that is based on or derived from the Customer Data
                                        that is provided to Customer as part of the Services. Notwithstanding anything
                                        to the contrary, THE SHED APP will have the right to collect and analyze data
                                        and other information relating to the provision, use and performance of various
                                        aspects of the Services and related systems and technologies (including, without
                                        limitation, information concerning Customer Data and data derived therefrom).
                                        THE SHED APP will be free (during and after the Term of this Agreement) to (a)
                                        use such information and data to improve and enhance the Services and the System
                                        and for other development, diagnostic and corrective purposes in connection with
                                        the Services, the System and other THE SHED APP offerings, and (b) disclose such
                                        data solely in aggregate or other de-identified form in connection with its
                                        business. No rights or licenses are granted except as expressly set forth
                                        herein.
                                    </li>
                                    <li>THE SHED APP owns and retains all right, title and interest in and to (a) the
                                        Services and Software, all improvements, enhancements or modifications thereto,
                                        (b) any software, applications, inventions or other technology developed in
                                        connection with Onboarding Services or support, (c) all graphics, user
                                        interfaces, logos, and trademarks reproduced through the System, and (d) all
                                        intellectual property rights related to or derived from any of the foregoing.
                                        This Agreement does not grant Customer any intellectual property license or
                                        rights in or to the System or any of its components. Customer recognizes that
                                        the System and its components are protected by copyright, trademark and other
                                        laws.
                                    </li>
                                    <li>Each Party (the “Receiving Party”) understands that the other Party (the
                                        “Disclosing Party”) has disclosed or may disclose business, technical or
                                        financial information relating to the Disclosing Party’s business (hereinafter
                                        referred to as “Proprietary Information” of the Disclosing Party). Proprietary
                                        Information of THE SHED APP includes non-public information regarding features,
                                        functionality and performance of the System and the Services. Proprietary
                                        Information of Customer includes non-public data Customer Data. The Receiving
                                        Party agrees: (i) to take reasonable precautions to protect such Proprietary
                                        Information, and (ii) not to use such Proprietary Information (except in
                                        performance of the Services or as otherwise permitted herein) or divulge it to
                                        any third person. The Disclosing Party agrees that the foregoing will not apply
                                        with respect to any information that the Receiving Party can document (a) is or
                                        becomes generally available to the public, (b) was in its possession or known by
                                        it prior to receipt from the Disclosing Party, (c) was rightfully disclosed to
                                        it without restriction by a third party, (d) was independently developed without
                                        use of any Proprietary Information of the Disclosing Party, or (e) is required
                                        to be disclosed by law.
                                    </li>
                                    <li>This Agreement does not transfer ownership of Proprietary Information or grant a
                                        license to it outside of use only for purposes under this Agreement. The
                                        Disclosing Party retains all right, title, and interest in and to all
                                        Proprietary Information.
                                    </li>
                                    <li>With respect to each item of Proprietary Information, the obligations of Section
                                        4.3 above regarding nondisclosure will terminate five (5) years after the
                                        termination of this Agreement; provided, however, that such obligations related
                                        to Proprietary Information constituting THE SHED APP’s trade secrets will
                                        continue so long as such Proprietary Information remains subject to trade secret
                                        protection pursuant to applicable law. Upon termination of this Agreement,
                                        Customer as the Receiving Party will return all copies of Proprietary
                                        Information to THE SHED APP, as the Disclosing Party, and THE SHED APP, as the
                                        Receiving Party, will do likewise. In the alternative, the Receiving Party will
                                        certify, in writing, that it has destroyed all Proprietary Information received
                                        from the Disclosing Party.
                                    </li>
                                    <li>Customer agrees that breach of this Article 4 would cause THE SHED APP
                                        irreparable injury, for which monetary damages would not provide adequate
                                        compensation, and that in addition to any other remedy, THE SHED APP will be
                                        entitled to injunctive relief against such breach or threatened breach, without
                                        proving actual damage or posting a bond or other security.
                                    </li>
                                    <li>During the term of this Agreement and for one (1) year thereafter, neither Party
                                        will directly or indirectly solicit or hire any employee of the other Party who
                                        was involved in performance under this Agreement.
                                    </li>
                                    <li>Customer agrees that THE SHED APP may identify it as a customer and use its
                                        identity and logo only for promotional purposes in sales presentations and
                                        marketing materials, including on its website.
                                    </li>
                                </ol>
                            </li>

                            <li class="sub-list">PAYMENT OF FEES
                                <ol>
                                    <li>At the time this Agreement is executed, Customer will pay the appropriate
                                        Onboarding Fee-Base Subscription or the Onboarding Fee-3D Subscription, or both.
                                    </li>
                                    <li>THE SHED APP will bill Customer for the then applicable Base Subscription Fees,
                                        3D Subscription Fees and/or Payment Processing Fees on the first day of the
                                        month following each month when Services have been rendered. All invoices are
                                        due upon receipt and must be paid in full within five (5) days either by credit
                                        card or electronic funds transfer or other similar means. Upon notice of
                                        nonpayment to Customer, Customer will have five (5) days to cure material breach
                                        of nonpayment. If the Customer fails to pay within that cure period, the
                                        Services and this Agreement will be terminated immediately. Unpaid amounts are
                                        subject to the maximum finance charge permitted by law, plus all costs and
                                        expenses of collection. Customer will be responsible for all taxes associated
                                        with Services other than U.S. taxes based on THE SHED APP’s net income.
                                    </li>
                                    <li>THE SHED APP reserves the right to change the Fees or applicable charges and to
                                        institute new charges and Fees for the Services at the end of the Initial
                                        Service Term or then current renewal term, upon sixty (60) days prior notice to
                                        Customer (which may be sent by email or conveyed by other electronic means).
                                        Thus, THE SHED APP will provide notice of new charges and Fees, if any, no later
                                        than November 1 of the calendar year. If Customer believes that THE SHED APP has
                                        billed Customer incorrectly, Customer must contact THE SHED APP immediately to
                                        resolve the issue. Inquiries should be directed to THE SHED APP’s billing
                                        department.
                                    </li>
                                </ol>
                            </li>

                            <li class="sub-list">TERM AND TERMINATION
                                <ol>
                                    <li>Subject to earlier termination as provided below, this Agreement is for the
                                        Initial Service Term as specified in the Order Form, and will be automatically
                                        renewed for successive periods of one calendar year each ending on December 31
                                        of the applicable calendar year (collectively, the “Term”), unless either Party
                                        requests termination at least thirty (30) days prior to the end of the
                                        then-current term.
                                    </li>
                                    <li>In addition to any other remedies it may have, either Party may also terminate
                                        this Agreement upon thirty (30) days’ notice (or without notice in the case of
                                        nonpayment), if the other Party materially breaches any of the terms or
                                        conditions of this Agreement. Customer will pay in full for the Services up to
                                        and including the last day on which the Services are provided.
                                    </li>
                                    <li>Upon termination or expiration of this Agreement, Customer will cease all use of
                                        the System and delete, destroy, or return to THE SHED APP all copies of the
                                        Documentation in its possession or control.
                                    </li>
                                    <li>Upon any termination or expiration of this Agreement, THE SHED APP will return
                                        all Customer Data to Customer within fifteen (15) days following the end of the
                                        calendar month in which the Agreement expires or is terminated. Customer agrees
                                        to pay THE SHED APP for expenses and time involved in returning all Customer
                                        Data at THE SHED APP's then-current rate for engineering services, which is
                                        currently $150.00 hour per person. Thereafter, THE SHED APP may, but is not
                                        obligated to, delete stored Customer Data.
                                    </li>
                                </ol>
                            </li>

                            <li class="sub-list">WARRANTY AND DISCLAIMER
                                <p>THE SHED APP will use reasonable efforts consistent with prevailing industry
                                    standards to maintain the Services in a manner which minimizes errors and
                                    interruptions in the Services and will perform the Onboarding Services in a
                                    professional and workmanlike manner. Services may be temporarily unavailable for
                                    scheduled maintenance or for unscheduled emergency maintenance, either by THE SHED
                                    APP or by third-party providers, or because of other causes beyond THE SHED APP’s
                                    reasonable control, but THE SHED APP will use reasonable efforts to provide advance
                                    notice in writing or by e-mail of any scheduled service disruption. HOWEVER, THE
                                    SHED APP DOES NOT WARRANT THAT THE SERVICES WILL BE UNINTERRUPTED OR ERROR FREE, OR
                                    THAT THE SHED APP WILL BE ABLE TO CORRECT ALL ERRORS. CUSTOMER ACKNOWLEDGES AND
                                    AGREES THAT THE SHED APP DOES CONTROL THE TRANSFER OF DATA OVER COMMUNICATION
                                    FACILITIES, SUCH AS THE INTERNET, AND THUS, SUCH COMMUNICATION FACILITIES MAY BE
                                    SUBJECT TO LIMITATIONS, DELAYS AND OTHER INHERENT PROBLEMS. THE SHED APP IS IN NO
                                    WAY RESPONSIBLE FOR ANY SUCH DELAYS, LIMITATIONS, DELIVERY FAILURES AND OTHER DAMAGE
                                    RESULTING FROM SUCH PROBLEMS. THE SHED APP MAKES NO WARRANTY AS TO THE RESULTS THAT
                                    MAY BE OBTAINED FROM USE OF THE SERVICES. TO THE EXTENT PERMITTED BY LAW, THESE
                                    WARRANTIES ARE EXCLUSIVE; EXCEPT AS EXPRESSLY SET FORTH IN THIS SECTION, THE
                                    SERVICES AND ONBOARDING SERVICES ARE PROVIDED “AS IS” AND THE SHED APP DISCLAIMS ALL
                                    WARRANTIES, EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, IMPLIED WARRANTIES OF
                                    MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT.</p>
                            </li>

                            <li class="sub-list">LIMITATION OF LIABILITY
                                <p>THE SHED APP AND ITS OFFICERS, AFFILIATES, REPRESENTATIVES, CONTRACTORS AND EMPLOYEES
                                    WILL NOT BE RESPONSIBLE OR LIABLE WITH RESPECT TO ANY SUBJECT MATTER OF THIS
                                    AGREEMENT OR TERMS AND CONDITIONS RELATED THERETO UNDER ANY CONTRACT, NEGLIGENCE,
                                    STRICT LIABILITY OR OTHER THEORY: (A) FOR ERROR OR INTERRUPTION OF USE OR FOR LOSS
                                    OR INACCURACY OR CORRUPTION OF DATA OR COST OF PROCUREMENT OF SUBSTITUTE GOODS,
                                    SERVICES OR TECHNOLOGY OR LOSS OF BUSINESS; (B) FOR ANY INDIRECT, EXEMPLARY,
                                    INCIDENTAL, SPECIAL OR CONSEQUENTIAL DAMAGES; (C) FOR ANY MATTER BEYOND THE SHED
                                    APP’S REASONABLE CONTROL; OR (D) FOR ANY AMOUNTS THAT, TOGETHER WITH AMOUNTS
                                    ASSOCIATED WITH ALL OTHER CLAIMS, EXCEED THE FEES PAID BY CUSTOMER TO THE SHED APP
                                    FOR THE SERVICES UNDER THIS AGREEMENT IN THE 12 MONTHS PRIOR TO THE ACT THAT GAVE
                                    RISE TO THE LIABILITY, IN EACH CASE, WHETHER OR NOT THE SHED APP HAS BEEN ADVISED OF
                                    THE POSSIBILITY OF SUCH DAMAGES.</p>
                            </li>

                            <li class="sub-list">MISCELLANEOUS
                                <ol>
                                    <li>If any provision of this Agreement is found to be unenforceable or invalid, that
                                        provision will be limited or eliminated to the minimum extent necessary so that
                                        this Agreement will otherwise remain in full force and effect and enforceable.
                                    </li>
                                    <li>This Agreement is not assignable, transferable or sublicensable by Customer
                                        except with THE SHED APP’s prior written consent, which will not be unreasonably
                                        withheld. THE SHED APP may transfer and assign any of its rights and obligations
                                        under this Agreement without consent.
                                    </li>
                                    <li>This Agreement, including the Order Form, Terms and Conditions, the Exhibits and
                                        Policies, is the complete and exclusive statement of the mutual understanding of
                                        the Parties and supersedes and cancels all previous written and oral agreements,
                                        communications and other understandings relating to the subject matter of this
                                        Agreement. All waivers and modifications must be in a writing signed by both
                                        Parties, except as otherwise provided herein. In the event of a conflict between
                                        the terms of this Agreement and the Policies, this Agreement will control.
                                    </li>
                                    <li>No delay, failure, or default, other than a failure to pay fees when due, will
                                        constitute a breach of this Agreement to the extent caused by acts of war,
                                        terrorism, hurricanes, earthquakes, other acts of God or of nature, strikes or
                                        other labor disputes, riots or other acts of civil disorder, embargoes, or other
                                        causes beyond the performing Party’s reasonable control.
                                    </li>
                                    <li>The Parties agree that the terms of this Agreement result from negotiations
                                        between them and that both Parties have had an opportunity to consult with legal
                                        counsel. This Agreement will not be construed in favor of or against either
                                        Party by reason of authorship.
                                    </li>
                                    <li>The relationship of the Parties is that of independent contractors and no
                                        agency, partnership, joint venture, or employment is created as a result of this
                                        Agreement. Neither Party has any authority of any kind to bind the other Party
                                        in any respect whatsoever.
                                    </li>
                                    <li>All notices under this Agreement will be in writing and will be deemed to have
                                        been duly given when received, if personally delivered; upon transmission, if
                                        transmitted by facsimile or e-mail; and, the day after it is sent, if sent for
                                        next day delivery by recognized overnight delivery service.
                                    </li>
                                    <li>This Agreement will be deemed to have been negotiated in the State of Arizona
                                        and this Agreement will be governed by and construed in accordance with the laws
                                        of the State of Arizona without regard to its conflicts of law provisions. The
                                        Parties consent to the personal and exclusive jurisdiction of the federal and
                                        state courts of Maricopa County, Arizona.
                                    </li>
                                    <li>In any action or proceeding to enforce rights under this Agreement, the
                                        prevailing party will be entitled to recover reasonable costs and reasonable
                                        attorneys’ fees. The term “Prevailing Party” means that Party in whose favor any
                                        monetary or equitable award is made or in whose favor any dispute is resolved,
                                        but only if it exceeds any settlement offer made by the other Party.
                                    </li>
                                    <li>The Parties acknowledge and agree that the provisions regarding disclaimers,
                                        exclusions, limitations of liability, waiver, warranty, choice of law,
                                        jurisdiction and venue set forth in this Agreement form an essential basis of
                                        this Agreement, and that, absent any of such provisions, the terms of this
                                        Agreement, including, without limitation, the economic terms, would be
                                        substantially different.
                                    </li>
                                    <li>The following provisions will survive termination or expiration of this
                                        Agreement: (a) any obligation of Customer to pay fees incurred before
                                        termination; (b) Proprietary Rights; Confidentiality; (c) Termination; (d)
                                        Warranty and Disclaimer; (e) Limitation of Liability; (f) Miscellaneous and (g)
                                        any other provision of this Agreement that must survive to fulfill its essential
                                        purpose.
                                    </li>
                                    <li>Multiple Counterparts. This Agreement may be executed by the Parties hereto in
                                        one or more separate copies, each of which will be deemed to be an original, but
                                        all of which together will be deemed to be one and the same instrument; however,
                                        this Agreement will have no force or effect until executed by both Parties.
                                    </li>
                                </ol>
                            </li>
                        </ol>

                        <h4 class="text-center"><u>EXHIBIT A</u></h4>
                        <h4 class="text-center">ONBOARDING SERVICES</h4>

                        <ol class="text-left">
                            <li>THE SHED APP will contact Customer for necessary information to set up the System for
                                use by the Customer for the Base Subscription or the 3D Subscription or both.
                            </li>
                            <li>Services include setup and testing of the System for Customer's use, as well as one (1)
                                hour of training conducted remotely via webinar or other electronic means.
                            </li>
                            <li>THE SHED APP will provide Documentation, if any, necessary for Customer to use the
                                System.
                            </li>
                            <li>Any additional work beyond that specified above in the sections above will involve
                                additional cost.
                            </li>
                            <li>Customer will pay for any additional work at THE SHED APP's then-current hourly rate for
                                engineering services, which is currently $150.00 per person per hour. Customer will also
                                pay for any additional expenses associated with the additional work.
                            </li>
                        </ol>

                        <h4 class="text-center"><u>EXHIBIT B</u></h4>
                        <h4 class="text-center">SERVICE AVAILABILITY, SERVICE LEVELS AND TECHNICAL SUPPORT</h4>

                        <p class="text-left">Service Availability</p>
                        <p class="text-left">The Services will be available 99% measured monthly, excluding weekends,
                            holidays and scheduled maintenance and downtime.</p>
                        <p class="text-left">Coverage parameters specific to the Services covered in this Agreement are
                            as follows:</p>
                        <ul class="text-left">
                            <li>Telephone support: 9:00 A.M. to 5:00 P.M. (MST) Monday – Friday</li>
                            <li>Emergency calls received out of office hours will be forwarded to a mobile phone;
                                reasonable efforts will be made to answer and/or respond to the call; however, this
                                cannot be guaranteed.
                            </li>
                            <li>Email support: Monitored 9:00 A.M. to 5:00 P.M. (MST) Monday - Friday</li>
                            <li>Emails received outside of office hours will be collected; however, no action can be
                                guaranteed until the next working day
                            </li>
                            <li>All assistance will be provided remotely.</li>
                        </ul>

                        <p class="text-left">Service Requests</p>
                        <p class="text-left">In support of Services outlined in this Agreement, THE SHED APP will
                            respond to service related incidents and/or requests submitted by the Customer within the
                            following time frames:</p>
                        <ul class="text-left">
                            <li>0-8 hours (during business hours) for issues classified as High priority.</li>
                            <li>Within 48 hours for issues classified as Medium priority.</li>
                            <li>Within 5 working days for issues classified as Low priority.</li>
                        </ul>

                        <p class="text-left">Remote assistance will be provided in line with the above timescales
                            depending on the priority of the support request.</p>
                    </div>
                    <div class="panel-footer">
                        <label>
                            <input type="checkbox" name="service_agreement_accepted" value="1">&nbsp;
                            <strong>I have read, understood and agree to the terms of this service agreement.</strong>
                        </label>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ url('/logout') }}">Decline</a>
                            <button type="submit" class="btn btn-primary">Agree</button>
                        </div>
                    </div>
                </form>

            </section>
        </div>
    </div>

</section>

<!-- start: scripts -->
@include('partials.scripts')
<!-- end: scripts -->

</body>
</html>
