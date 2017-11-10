<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <meta name="_token" content="{{ csrf_token() }}" id="_token">
    <meta name="_stripePublishableKey" content="{{ env('STRIPE_PUBLISHABLE_KEY') }}" id="_stripePublishableKey">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Web Fonts  -->
    <link rel="stylesheet" href="{{ asset('fonts/webfonts.css') }}" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <script src="{{ url('vendor/promise/promise-7.0.4.min.js') }}"></script>
    <![endif]-->

    <link rel="stylesheet" href="{{ url('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendor/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendor/nanoscroller/nanoscroller.css') }}" />
    <link rel="stylesheet" href="{{ url('vendor/sweetalert/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ elixir('app.css', 'app') }}">

    <style type="text/css">
        html, body {
            min-height: 100%;
            height: 100%;
            margin: 0px;
            padding: 0px;
            overflow: hidden;
        }
    </style>
</head>

<body class="spa">
    <app></app>

    <script src="{{ url('vendor/jquery/dist/jquery.min.js') }}"></script>
    <!-- TODO: remove bootstrap (required for bootstrap-multiselect)-->
    <script src="{{ url('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('vendor/nanoscroller/nanoscroller.js') }}"></script>
    <script src="{{ url('vendor/sweetalert/sweetalert.min.js') }}"></script>
    <script type="text/javascript" src="{{ elixir('app.js', 'app') }}"></script>

</body>

</html>