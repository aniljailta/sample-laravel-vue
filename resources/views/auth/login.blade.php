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
				<h2 class="title text-uppercase text-weight-bold m-none"><i class="fa fa-user mr-xs"></i> LOGIN</h2>
			</div>
			<div class="panel-body">
				@if(session('message'))
					<div class="alert alert-danger">
						{{ session('message') }}
					</div>
				@endif
				<form action="{{ url('login') }}" method="post">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group mb-lg">
						<label>Email</label>
						<div class="input-group input-group-icon">
							<input name="email" type="text" class="form-control input-lg" value="{{ old('email') }}" autofocus/>
							<span class="input-group-addon">
								<span class="icon icon-lg">
									<i class="fa fa-user"></i>
								</span>
							</span>
						</div>
					</div>

					<div class="form-group mb-lg">
						<div class="clearfix">
							<label class="pull-left">Password</label>
							<a href="{{ url('password/email') }}" tabindex="-1" class="pull-right">Forgot your password?</a>
						</div>
						<div class="input-group input-group-icon">
							<input name="password" type="password" class="form-control input-lg" />
							<span class="input-group-addon">
								<span class="icon icon-lg">
									<i class="fa fa-lock"></i>
								</span>
							</span>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-8">
							<div class="checkbox-custom checkbox-default">
								<input id="RememberMe" name="remember" type="checkbox"/>
								<label for="RememberMe">Remember me</label>
							</div>
						</div>
						<div class="col-sm-4 text-right">
							<button type="submit" class="btn btn-primary hidden-xs">Login</button>
							<button type="submit" class="btn btn-primary btn-block btn-lg visible-xs mt-lg">Login</button>
						</div>
					</div>
				</form>
			</div>
		</div>

		<p class="text-center text-muted mt-md mb-md">
			&copy; Copyright {{date('Y')}}, The Shed App. All rights reserved.
			<img height="21"; src="images/logo-main.png">
		</p>
	</div>
</section>
<!-- end: page -->
@endsection
