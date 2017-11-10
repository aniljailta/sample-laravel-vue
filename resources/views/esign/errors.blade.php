@extends('esign._layout')

@section('title')
    Something went wrong.
@endsection

@section('content')
<div class="container error">
    <h1>Something went wrong!</h1>
    @if($errors->any())
        @foreach($errors->getMessages() as $error)
            <p>{{ $error[0] }}</p>
        @endforeach
    @endif

    <a href="{{ url()->current() }}" type="button" role="button" class="btn btn-primary btn-md refresh">
        <i class="fa fa-refresh" aria-hidden="true"></i> Try Again
    </a>
</div>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.refresh').click(function () {
                var $button = $(this)
                $button.find('.fa-refresh').addClass('fa-spin')
                $button.addClass('disabled')
            });
        });
    </script>
@endsection

@section('styles')
    @parent
    <style type="text/css">

        .container.error {
            padding: 40px;
        }

        .container.error h1 {
            font-size: 34px;
        }

        .container.error p em {
            font-weight: bold;
            color: #333;
        }

        .container.error p {
            font-size: 14px;
            color: #666;
        }
    </style>
@endsection
