@extends('esign._layout')
@section('title')
    E-sign order via email
@endsection

@section('content')
    <div class="container info">
        <h1>Email successfully submitted!</h1>

        <p>This order successfully submitted to <em>{{ $order->order_reference->email }}</em>.</p>
        <p>If you have any questions, please don't hesitate to get in touch with us.</p>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        jQuery(document).ready(function($) {
            var targerOrigin = location.protocol + '//' + location.host;
            window.parent.postMessage({
                event: 'signature_pending',
                fileId: '{{ $file->id }}'
            }, targerOrigin);
        });
    </script>
@endsection

@section('styles')
    @parent

    <style type="text/css">

        .container.info {
            padding: 40px;
        }

        .container.info h1 {
            font-size: 34px;
        }

        .container.info p em {
            font-weight: bold;
            color: #333;
        }

        .container.info p {
            font-size: 14px;
            color: #666;
        }
    </style>
@endsection