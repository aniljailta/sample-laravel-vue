@extends('emails._base-layout')

@section('title')
    Order Cancelled
@endsection

@section('content')
    <div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">
        Hi {{ $order->dealer->business_name }}!
    </div>
    <br>

    <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
        The order for {{ $order->customer->full_name }} has been cancelled per your request.
        If available, refunds will be issued to the customer per our cancellation policy. Please call
        {{ config('app.name') }} if you have any additional questions or concerns.<br>
    </div>
    <br>
@endsection

@section('footer')
    @include('emails._footer')
@endsection

@section('copyright')
    @include('emails._copyright')
@endsection