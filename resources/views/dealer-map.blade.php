<!DOCTYPE html>
<html lang="en" class="fh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Dealer map</title>
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
    <link rel='stylesheet' id='Roboto-css' href='https://fonts.googleapis.com/css?family=Roboto%3A100%2C300%2C400%2C400italic%2C500%2C700&#038;ver=4.6' type='text/css' media='all'/>
    <link rel="stylesheet" type="text/css" href="{{ elixir('dealer_map.css', 'app') }}">
    <link rel="stylesheet" type="text/css" href="{{ elixir('wp_theme.css', 'app') }}">

    <script src="https://cdn.rawgit.com/davidjbradshaw/iframe-resizer/master/js/iframeResizer.contentWindow.js"></script>
</head>

<body class="fh" style="margin: 0px !important; overflow-y: hidden;">
    <div id="v-app" v-cloak>
        <dealer-map></dealer-map>
    </div>
    <script type="text/javascript" src="{{ elixir('dealer_map.js', 'app') }}"></script>
</body>

</html>
