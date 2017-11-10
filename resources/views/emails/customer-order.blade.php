@extends('emails._base-layout')

@section('title')
    {{ config('app.name') }} Quote
@endsection

@section('content')
    <div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">
        Hi {{ $customer['first_name'] }} {{ $customer['last_name'] }}!
    </div>
    <br>

    <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
        Find your custom {{ config('app.name') }} building quote attached per your request.<br>
        A sales representative will be contacting you per your preferred channel within the next 24 hours.<br>
        If you have any additional questions, feel free to contact us!
    </div>
    <br>
    <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333; font-style: italic;">
        Preferred customer contact time is: @lang("orders.contact_type_{$contact_type}"), @lang("orders.contact_time_{$contact_time}")
    </div>
    <br>
@endsection

@section('footer')
    @include('emails._footer')
@endsection

@section('copyright')
    @include('emails._copyright')
@endsection