@extends('forms.order-layout')

@section('pdf-content')

    <?php

    $sections = $sections ?? [
                    'order-main',
                    'order-grid',
                    'rto-renter-info',
                    'rto-agreement',
                    'deposit-receipt',
                    'rto-promo99',
                    'customer-delivery',
                    'neighbor-release-form'
            ];
    $sections = collect($sections);

    $buildingLocation['address'] = $orderReference->building_location_address ?? '';
    $buildingLocation['city'] = $orderReference->building_location_city ?? '';
    $buildingLocation['state'] = $orderReference->building_location_state ?? '';
    $buildingLocation['zip'] = $orderReference->building_location_zip ?? '';

    if ($orderReference->building_in_same_address === true) {
        $buildingLocation['address'] = $orderReference->address ?? '';
        $buildingLocation['city'] = $orderReference->city ?? '';
        $buildingLocation['state'] = $orderReference->state ?? '';
        $buildingLocation['zip'] = $orderReference->zip ?? '';
    }
    ?>

    @if($sections->contains('order-main'))
        @include('forms.order-main')
    @endif

    @if($sections->contains('customer-delivery'))
        <div class="page-break"></div>
        @include('forms.customer-delivery')
    @endif

    <!-- RTO -->
    @if($order->payment_type == 'rto')
        @if($sections->contains('rto-renter-info'))
            <div class="page-break"></div>
            @include('forms.jmag-header')
            @include('forms.rto-renter-info')
        @endif

        @if($sections->contains('rto-agreement'))
            <div class="page-break"></div>
            @include('forms.jmag-header')
            @include('forms.rto-agreement')
        @endif

        @if($sections->contains('deposit-receipt'))
            <div class="page-break"></div>
            @include('forms.jmag-header')
            @include('forms.rto-receipt')
        @endif
    @endif

    @if($sections->contains('rto-promo99') && $order->promo99 == true)
        <div class="page-break"></div>
        @include('forms.rto-promo99')
    @endif
    <!-- /RTO -->

    @if($sections->contains('order-threed-model'))
        <div class="page-break"></div>
        @include('forms.order-3d')
    @endif

    @if($sections->contains('deposit-receipt') && $order->payment_type == 'cash')
        @include('forms.cash-receipt')
    @endif

    @if($sections->contains('order-grid'))
        <div class="page-break"></div>
        @include('forms.order-grid')
    @endif

    @if($sections->contains('signed-building-configuration'))
        <div class="page-break"></div>
        @include('forms.signed-building-configuration')
    @endif

    @if($sections->contains('neighbor-release-form'))
        <div class="page-break"></div>
        @include('forms.neighbor-release-form')
    @endif

    @if($sections->contains('driver_license'))
        <div class="page-break"></div>
        @include('forms.driver-license')
    @endif

@endsection