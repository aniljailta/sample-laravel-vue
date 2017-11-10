@extends('partials._base')

@section("panel-tools")
    @if(isset($params['search']) and $params['search'] == 'YES')
        <form action="" id="searchForm" method="GET" accept-charset="UTP-8">
        	<div class="input-group input-search mt-none" style="width:200px;">
				<input type="search" id="bx_filter" name="filter" class="form-control input-sm bx_filter" value="{{ $params['filter'] }}" placeholder="Search..." autofocus='true'>
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
				</span>
			</div>
        </form>
    @endif
@endsection

@section('panel-body')
<!-- Table List Items -->
<div class="dataTables_wrapper no-footer">
	<div class="table-responsive">
		<table class="table table-bordered table-striped table-condensed mb-none no-footer" >
		@yield('table-items')
		</table>
	</div>
	<div class="row datatables-footer">
		<div class="col-sm-12 col-md-6">
	        @if(Auth::user()->ability('sysadmin', $params['route_name'].'-create'))
	        <div class="pull-left">
	            <a href="{{ url($params['route']) }}/create" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i> New</a>
	        </div>
	        @endif
	    </div>
	    <div class="col-sm-12 col-md-6">
	        <div class="dataTables_paginate paging_bs_normal pull-right">
	            {!! $params['data']['items']->appends(Request::input())->render() !!}
	        </div>
	    </div>
	</div>
</div>
@endsection

@section('extra-content')
	@if(Auth::user()->ability('sysadmin', $params['route_name'].'-delete'))
		<!-- Modal Delete Confirmation -->
    	@include('partials._confirm_mov_modal', ['modalTitle' => 'Confirm', 'modalSize' => 'modal-block-sm'])
	@endif
@endsection
