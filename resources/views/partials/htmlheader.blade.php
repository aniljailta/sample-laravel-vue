<head>
	<!-- Basic -->
	<meta charset="UTF-8">
	<title>{{ config('app.name') }}. @yield('htmlheader_title') </title>
	<meta name="description" content="{{ config('app.name') }} Management App">
	<meta name="author" content="Borealis">
    <meta name="_token" content="{!! csrf_token() !!}" id="_token">
	<!-- Mobile Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<!-- Web Fonts  -->
	<link rel="stylesheet" href="{{ asset('fonts/webfonts.css') }}" />
	<!-- Vendor CSS -->
	<link rel="stylesheet" href="{{ asset('vendor/sweetalert/sweetalert.css') }}">
	<link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.css') }}" />
	<link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/font-awesome.css') }}" />
	<link rel="stylesheet" href="{{ asset('vendor/magnific-popup/magnific-popup.css') }}" />
	<link rel="stylesheet" href="{{ asset('vendor/pnotify/pnotify.custom.css') }}" />
	<link rel="stylesheet" href="{{ asset('vendor/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" />
	<!-- Specific Page Vendor CSS -->
	@yield('specifics-stylesheet')
	<!-- Theme CSS -->
	<link rel="stylesheet" href="{{ asset('css/theme.css') }}" />
	<!-- Skin CSS -->
	<link rel="stylesheet" href="{{ asset('css/skins/orange/default.css') }}" />
	<!-- Theme Custom CSS -->
	<link rel="stylesheet" href="{{ asset('css/theme-custom.css') }}" />
	<!-- Head Libs -->
	<script src="{{ asset('vendor/modernizr/modernizr.js') }}"></script>
</head>
