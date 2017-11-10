@extends('app')

@section('main-content')
<div class="row">
	<section class="panel-featured">
		<header class="panel-heading">
			<div class="panel-actions">
				@yield('panel-tools')
			</div>
			<h2 class="panel-title">{{ $params['subtitle'] }}</h2>
		</header>
		<div class="panel-body">
			@yield('panel-body')
		</div>
		@yield('panel-footer')
	</section>
</div>
@endsection
