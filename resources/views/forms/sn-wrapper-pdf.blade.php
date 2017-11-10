@extends('forms.sn-wrapper-layout')

@section('pdf-content')

    @foreach($pages as $page)
        <div class="stamp">
            <div>{{ $data['serial_number'] }}</div>
        </div>

        <img width="100%" height="auto" src="data:image/{{ $type }};base64,{{ base64_encode($page) }}" />
        <div class="page-break"></div>
    @endforeach

@endsection