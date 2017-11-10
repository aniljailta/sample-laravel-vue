@extends('emails._base-layout')

@section('title')
    Test email from {{ config('app.name') }}
@endsection

@section('content')
    <div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550">
        Hi!
    </div>
    <br>

    <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
        This message has been sent from {{ config('app.name') }}.
    </div>
    <br>
@endsection

@section('footer')
    @include('emails._footer')
@endsection

@section('copyright')
    @include('emails._copyright')
@endsection