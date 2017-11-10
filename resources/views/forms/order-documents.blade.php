@extends('forms.order-layout')

@section('pdf-content')
    <?php

    $sections = $sections ?? [
            'order-main',
            'customer-receipt',
            'work-order',
            'delivery-form'
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

    @include('forms.order-main')

    <div class="page-break"></div>

    @if($sections->contains('customer-receipt') && $order->payment_type == 'cash')
        @include('forms.cash-receipt')
    @endif

    @if($sections->contains('customer-receipt') && $order->payment_type == 'rto')
        <div class="page-break"></div>
        @include('forms.jmag-header')
        @include('forms.rto-receipt')
    @endif

    <div class="page-break"></div>

    @include('forms.work-order')

    <div class="page-break"></div>

    @include('forms.delivery-form')
@endsection