@extends('emails._base-layout')

@section('title')
    {{ config('app.name') }} Quote
@endsection

@section('content')
    <div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">
        Hi {{ $customer->full_name }}!
    </div>
    <br>

    <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
        Congratulations on the purchase of your shed from {{ config('app.name') }}!<br>
        Attached find the signed documentation for your order.<br>
        If you purchased your shed using rent-to-own, you will receive an additional copy once {{ $rtoCompany->name }} has signed the RTO contract.<br>
        You will also receive an email once your order has been processed by our offices.<br>
    </div>
    <br>
@endsection

@section('footer')
    @include('emails._footer')
@endsection

@section('copyright')
    @include('emails._copyright')
@endsection