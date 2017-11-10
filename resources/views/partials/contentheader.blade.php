<header class="page-header">
	<h2>{{ $params['title'] }}</h2>

	<div class="right-wrapper pull-right">
		<ol class="breadcrumbs">
			<li><a href="/"><i class="fa fa-home"></i></a></li>
			@if ($params['breadcrumbs'])
				@foreach($params['breadcrumbs'] as $bc)
					@if(!empty($bc['url']))
	            		<li><a href="{{ url($bc['url']) }}">{{ $bc['page'] }}</a></li>
	            	@else
	            		<li><span>{{ $bc['page'] }}</span></li>
            		@endif
	            @endforeach
			@endif
		</ol>
		<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
	</div>
</header>
