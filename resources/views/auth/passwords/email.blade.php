@extends('auth.auth')

@section('content')
<!-- start: page -->
<section class="body-sign">
	<div class="center-sign">
		@if($companySettings->company_logo_public_path)
		<a href="/" class="logo pull-left">
			<img src="{{ url($companySettings->company_logo_public_path) }}" height="54" alt="{{ config('app.name') }}" />
		</a>
		@endif

		<div class="panel panel-sign">
			<div class="panel-title-sign mt-xl text-right">
				<h2 class="title text-uppercase text-weight-bold m-none"><i class="fa fa-user mr-xs"></i> RESET PASSWORD</h2>
			</div>
			<div class="panel-body">
				<form action="{{ url('/password/email') }}" method="post">
					<p class="text-weight-semibold h6 mb-lg text-center">Please enter your email address to start the reset process.</p>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group mb-none">
						<div class="input-group">
							<input name="email" type="email" placeholder="E-Mail" class="form-control input-lg" value="{{ old('email') }}" autofocus />
							<span class="input-group-btn">
								<button class="btn btn-primary btn-lg" type="submit">Reset Password</button>
							</span>
						</div>
					</div>

					<p class="text-center mt-lg">Remember it now? <a href="{{ url('/login') }}">Login!</a>
				</form>
			</div>
		</div>

		<p class="text-center text-muted mt-md mb-md">&copy; Copyright {{date('Y')}}. {{ config('app.name') }}.</p>
	</div>
</section>
<!-- end: page -->
@endsection
