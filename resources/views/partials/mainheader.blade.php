<header class="header">
	<div class="logo-container">
		@if($companySettings->company_logo_public_path)
		<a href="/" class="logo">
			<img src="{{ url($companySettings->company_logo_public_path) }}" height="35" alt="{{ config('app.name') }}" />
		</a>
		@endif
		<div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
			<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
		</div>
	</div>


	<!-- start: search & user box -->
	<div class="header-right">
		{{ 'Current Timezone: '.$time_zone['abbr'].' ('. $time_zone['name'].')' }}
		<span class="separator"></span>

		@if (Auth::check())
		<div id="userbox" class="userbox">
			<a href="#" data-toggle="dropdown">
				<figure class="profile-picture">
					<img src="{{ asset('/images/user_blue.png') }}" alt="{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}" data-lock-picture="{{ asset('/images/logo_lock.png') }}" />
				</figure>
				<div class="profile-info" data-lock-name="{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}" data-lock-email="{{ Auth::user()->email }}">
					<span class="name">{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}</span>
					<span class="role">{{ Auth::user()->roles()->first()->display_name }}</span>
				</div>

				<i class="fa custom-caret"></i>
			</a>


			<div class="dropdown-menu">
				<ul class="list-unstyled">
					<li class="divider"></li>
					<li>
						<a role="menuitem" tabindex="-1" href="#"><i class="fa fa-user"></i> My Profile</a>
					</li>
					<li>
						<a role="menuitem" tabindex="-1" href="#" data-lock-screen="true"><i class="fa fa-lock"></i> Lock</a>
					</li>
					<li>
						<a role="menuitem" tabindex="-1" href="{{ url('/logout') }}"><i class="fa fa-power-off"></i> Logout</a>
					</li>
				</ul>
			</div>
		</div>
		@endif
	</div>
	<!-- end: search & user box -->
</header>
