@extends('emails._base-layout')

@section('title')
    {{ config('app.name') }} Order for {{ $orderReference->customer_name }}, SN: {{ $order->building->serial_number }}
@endsection

@section('content')
    <div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">
        Hello {{ $orderReference->customer_name }}!
    </div>
    <br>

    <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
        Thanks for choosing {{ config('app.name') }}!
    </div>

    <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
        Your order has been received and processed. It is now being sent to dispatch for delivery scheduling. For your building to be delivered in a timely and efficient manner <strong>we require</strong>:<br>
    </div>
    <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
        <ul>
            <li>A compact and level pad</li>
            <li>Sufficient clearance along the delivery path. We recommend 2 feet. Be sure gates are wide enough, and any debris or vegetation has been properly cleared.</li>
            <li>FAILING TO MEET THESE REQUIREMENTS WILL LIKELY INCUR ADDITIONAL DELIVERY AND/OR SETUP FEES.</li>
            <li>For additional information and requirements, please refer to the {{ config('app.name') }} Dispatch and Delivery Procedures you signed when your order was placed.</li>
        </ul>
    </div>
    <br>

    <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
        Here is what you can expect:
    </div>
    <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
        <ul>
            <li>The <i>estimated period</i> for delivery is <strong>{{ date("m/d/Y", strtotime($order->ced_start)) }} – {{ date("m/d/Y", strtotime($order->ced_end)) }}</strong>. Normally you can expect your building to be delivered sometime within this period.</li>
            <li>Within 2 business days of receiving this email, our dispatcher will contact you by phone to setup the delivery day.</li>
            <li>If you have requested a specific delivery date and/or time when you placed your order, rest assured that we will do our best to accommodate your request. We cannot however guarantee that your requested delivery date and time will be available.</li>
            <li>Once your delivery date has been scheduled, our driver will contact you the day prior to the delivery to setup a 4 hour delivery window.</li>
            <li>Our driver will contact you again once your building is loaded for delivery or if the delivery window changes.</li>
        </ul>
    </div>
    <br>

    <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
        Please be aware that scheduling changes may be necessary due to circumstances beyond our control.
    </div>
    <br>

    <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333; font-style: italic;">
        In the meantime, if you have any questions or concerns, feel free to contact your local dealer: <br/>
        {{ $dealer->business_name }} <br>
        {{ $dealer->phone }} <br>
        {{ $dealer->email }}
    </div>
    <br>
@endsection

@section('footer')
    @include('emails._footer')
@endsection

@section('copyright')
    @include('emails._copyright')
@endsection