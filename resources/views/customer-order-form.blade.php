<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }} - Customer Order Form</title>
        <meta name="_token" content="{{ csrf_token() }}" id="_token">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <script src="{{ url('vendor/promise/promise-7.0.4.min.js') }}"></script>
        <![endif]-->
        <link rel="stylesheet" href="{{ url('vendor/bootstrap/css/bootstrap.min.css') }}">

        <link rel="stylesheet" href="{{ url('vendor/font-awesome/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ url('vendor/sweetalert/sweetalert.css') }}">
        <link rel='stylesheet' id='Roboto-css' href='https://fonts.googleapis.com/css?family=Roboto%3A100%2C300%2C400%2C400italic%2C500%2C700&#038;ver=4.6' type='text/css' media='all'/>
        <link rel="stylesheet" type="text/css" href="{{ elixir('customer_order_form.css', 'app') }}">
        <link rel="stylesheet" type="text/css" href="{{ elixir('wp_theme.css', 'app') }}">

        <script src="https://cdn.rawgit.com/davidjbradshaw/iframe-resizer/master/js/iframeResizer.contentWindow.js"></script>
    </head>

    <body>
        <div id="v-app" v-cloak>
            <customer-order-form></customer-order-form>
        </div>
        <script src="{{ url('vendor/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ url('vendor/sweetalert/sweetalert.min.js') }}"></script>
        <script type="text/javascript" src="{{ elixir('customer_order_form.js', 'app') }}"></script>
    </body>

</html>
