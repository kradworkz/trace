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
							<h3 class="page-header"><i class="fa fa-users"></i><strong>{{ $data['page'] }}</strong></h3>
						</div>						
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<div class="pull-right">
						{!! Form::open(['url' => 'user_groups/search', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off']) !!}
							<div class="form-group">
								<div class="input-group input-group-sm">
									{!! Form::text('search', $search, ['class' => 'form-control', 'placeholder' => 'Search User Group']) !!}
									<span class="input-group-btn">
										{!! Form::button('<span class="fa fa-search"></span> Search', ['class' => 'btn btn-primary btn-xs', 'type' => 'submit', 'title' => 'Search']) !!}
									</span>
								</div>
							</div>
						{!! Form::close() !!}
					</div>
					@if($usergroups->isEmpty())
						<div class="panel-body">
							<h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No user group(s) found.</strong></h5>
						</div>
					@else
						<table class="table">
							<thead>
								<tr>
									<th>#</th>
									<th>User Group</th>							
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($usergroups as $usergroup)
								<tr>
									<?php $num++; ?>
									<td>{{ $num }}</td>
									<td>{{ $usergroup->ug_name }}</td>							
									<td class="text-right">								
										<a href="{{ url('user_groups/edit/'.$usergroup->ug_id) }}" class="btn btn-info btn-xs" title="Edit"><span class="fa fa-pencil-square-o"></span></a>								
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						@if($usergroups->render())
							<div class="text-center">@if($search == '') {!! $usergroups->render() !!} @else {!! $usergroups->appends(Request::only('search'))->render() !!} @endif </div>
						@endif
					@endif
				</div>
			</div>
		</section>
	</section>
@stop