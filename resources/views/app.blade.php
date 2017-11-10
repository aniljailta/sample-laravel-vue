<!doctype html>
{{-- <html class="fixed js header-dark sidebar-left-xs darkfixed js flexbox flexboxlegacy no-touch csstransforms csstransforms3d no-overflowscrolling no-mobile-device custom-scroll sidebar-left-xs1 dark1"> --}}
<html class="fixed header-dark">
    @include('partials.htmlheader')
	<body class="loading-overlay-showing" data-loading-overlay>
		<!-- Start: Loading Overlay -->
		<div class="loading-overlay dark">
			<div class="loader white"></div>
		</div>
		<!-- End: Loading Overlay -->

		<section class="body">

			<!-- start: main header -->
			@include('partials.mainheader')
			<!-- end: main header -->

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				@include('partials.sidebar')
				<!-- end: sidebar -->

				<section role="main" class="content-body" id="v-app">

					<!-- start: content header -->
					@include('partials.contentheader')
					<!-- end: content header -->

					<!-- start: content -->
					@yield('main-content')
					<!-- end: content -->

					<!-- start: extra content -->
					@yield('extra-content')
					<!-- end: extra content -->
				</section>
			</div>

		</section>

        @yield('modals')

		<!-- start: scripts -->
		@include('partials.scripts')
		<!-- end: scripts -->

	</body>
</html>
