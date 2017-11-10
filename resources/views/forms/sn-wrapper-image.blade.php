@extends('forms.sn-wrapper-layout')

@section('pdf-content')

    <style type="text/css">
        .stamp > div {
            margin-top: 2px;
            background: #fff;
            border-bottom: 1px solid #6f6f6f;
            text-align: center;
            font-weight: bold;
            font-size: 15px;
        }
    </style>

        <div class="stamp">
            <div>{{ $data['serial_number'] }}</div>
        </div>

        <img src="data:image/{{ $type }};base64,{{ base64_encode($imageBlob) }}" width="100%" />
        <div class="page-break"></div>

@endsection