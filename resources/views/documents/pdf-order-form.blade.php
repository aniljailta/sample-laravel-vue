@extends('forms.order-layout')

@section('pdf-content')
    @include('forms.order-main')

    <div class="page-break"></div>

    @include('forms.order-grid')
@endsection