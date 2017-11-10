@extends('forms.order-layout')

@section('pdf-content')
    <div class="page-break"></div>
    @include('forms.jmag-header')
    @include('forms.rto-renter-info')

    <div class="page-break"></div>
    @include('forms.jmag-header')
    @include('forms.rto-agreement')

    <div class="page-break"></div>
    @include('forms.jmag-header')
    @include('forms.rto-receipt')
@endsection