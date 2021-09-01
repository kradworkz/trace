@extends('layouts.main')
@section('content')
	<section id="main-content">
    	<section class="wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="pull-left">
							<h3 class="page-header"><i class="fa fa-pie-chart"></i><strong>{{ $data['page'] }}</strong></h3>
						</div>
						<div class="pull-right">
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<div class="pull-right">
					</div>
					@if($users->isEmpty())
						<div class="panel-body">
							<h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No unit statistics(s) found.</strong></h5>
						</div>
					@else
						<table class="table">
							<thead>
								<tr>
									<th>Name</th>
									<th>Total Utilization</th>
									<th>Average Utilization</th>									
								</tr>
							</thead>
							<tbody>
								@foreach($users as $user)
								<?php
									$logs = App\Models\UserLog::where('u_id', $user->u_id)->where('created_at', 'LIKE', '2019%')->count();
								?>
								<tr>
									<td>{{ $user->u_fname }} {{ $user->u_lname }}</td>
									<td>{{ $logs }}</td>
									<td></td>
								</tr>
								@endforeach
							</tbody>
						</table>
						@if($users->render())
							<div class="text-center">@if($users == '') {!! $users->render() !!} @else {!! $users->appends(Request::only('search'))->render() !!} @endif </div>
						@endif
					@endif
				</div>
			</div>
		</section>
	</section>
@stop