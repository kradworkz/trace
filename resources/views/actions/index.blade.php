@extends('layouts.main')
@section('content')
	<section id="main-content">
    	<section class="wrapper">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						@if(Session::has('unauthorize'))
				        	<div class="alert alert-danger fade in" id="alert">
				            	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				          		<strong><span class="fa fa-exclamation-circle"></span> {{ Session::get('unauthorize') }}</strong>
				        	</div>
				      	@endif
				      	@if(Session::has('update'))
				        	<div class="alert alert-warning fade in" id="alert">
				            	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				          		<strong><span class="fa fa-check-circle"></span> {{ Session::get('update') }}</strong>
				        	</div>
				      	@endif
				      	@if(Session::has('success'))
				        	<div class="alert alert-success fade in" id="alert">
				            	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					          	<strong><span class="fa fa-check-circle"></span> {{ Session::get('success') }}</strong>
				        	</div>
				        @endif
						<div class="pull-left">
							<h3 class="page-header"><i class="fa fa-check-square-o"></i><strong>{{ $data['page'] }}</strong> <a href="{{ url('action_settings/add') }}" class="header-link" title="Add">[Add Action]</a></h3>
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					@if($actions->isEmpty())
						<div class="panel-body">
							<h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No existing action(s) found.</strong></h5>
						</div>
					@else
						<table class="table">
							<thead>
								<tr>
									<th>Action ID</th>
									<th>Action</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($actions as $action)
								<tr>
									<td>{{ $action->a_number }}</td>
									<td>{{ $action->a_action }}</td>
									<td class="text-right">
										<a href="{{ url('action_settings/edit/'.$action->a_id) }}" class="btn btn-warning btn-xs" title="Edit"><span class="fa fa-pencil-square-o"></span></a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					@endif
				</div>
			</div>
		</section>
	</section>
@stop