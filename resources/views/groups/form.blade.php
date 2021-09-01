@extends('layouts.main')
@section('content')
	@if($option == "View")
	<style type="text/css">
	.text-danger { display: none; }
	</style>
	@endif

	{!! Form::model($group, ['url' => 'groups/save', 'class' => 'form-horizontal', 'autocomplete' => 'off', 'id' => 'groupForm']) !!}
	<section id="main-content">
    	<section class="wrapper">
			<div class="container-fluid">
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
							<h3><strong><a href="{{ url('groups') }}">Groups</a> | {{ $option }}</strong></h3>
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Group Details</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								{!! Form::hidden('id', $id) !!}
								<div class="form-group {{ ($errors->has('group_name') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Group Name: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('group_name', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#group_name', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="group_name" class="align-text"> {{ $errors->first('group_name') }}</strong></small></span>
								</div>						
								<div class="form-group {{ $option == 'View' ? 'hidden' : ''}}">
									<div class="col-md-3 col-md-offset-2">
										{!! Form::button('<span class="fa fa-save"></span> Save', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
										<a href="{{ url('groups') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Cancel</a>
									</div>
								</div>
								<hr>								
							</div>
						</div>
					</div>

					@if($option == 'Edit')			
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Add New Member</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="col-md-2 control-label">Select Member/s:</label>
									<div class="col-md-8">{!! Form::select('gms[]', $users, NULL, ['class' => 'form-control input-sm chosen-select', 'multiple', 'data-placeholder' => 'Employee/s']) !!}</div>
								</div>
								<div class="form-group {{ $option == 'View' ? 'hidden' : ''}}">
									<div class="col-md-3 col-md-offset-2">
										{!! Form::button('<span class="fa fa-plus"></span> Add Member/s', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
										<a href="{{ url('groups') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Cancel</a>
									</div>
								</div>
								<hr>								
							</div>
						</div>
					</div>
					@endif

					@if($option == 'View' || $option == 'Edit')
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Group Members</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">						
								@if($members->isEmpty())							
									<div class="panel-body">
										<h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No existing member(s) found.</strong></h5>
									</div>
								@else
									<table class="table">
										<thead>
											<tr>
												<th>Name</th>
												<th>Position</th>
												<th>Member Since</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
												@foreach($members as $member)
													<tr>
														<td>{{ App\Models\User::where('u_id', $member->u_id)->value('u_lname') }}, {{ App\Models\User::where('u_id', $member->u_id)->value('u_fname') }}</td>
														<td>{{ App\Models\User::where('u_id', $member->u_id)->value('u_position') }}</td>
														<td>{{ date('F d, Y h:i A', strtotime($member->created_at)) }}</td>
														<td class="text-right">
															<a href="{{ url('accounts/view/'.$member->u_id) }}" class="btn btn-info btn-xs" title="View"><span class="fa fa-eye"></span></a>
															@if($option == 'Edit')
															@if(Auth::user()->u_administrator == 1)													
															<a href="{{ url('accounts/edit/'.$member->u_id) }}" class="btn btn-warning btn-xs" title="Edit"><span class="fa fa-pencil-square-o"></span></a>
															<a href="{{ url('groups/member/delete/'.$member->gm_id) }}" class="btn btn-danger btn-xs" title="Remove"><span class="fa fa-trash-o"></span></a>
															@endif
															@endif
														</td>
													</tr>
												@endforeach
										</tbody>
									</table>
									@if($members->render())
										<div class="text-center">{!! $members->render() !!}</div>
									@endif
								@endif							
							</div>
						</div>				
					</div>
					@endif				
				</div>
			</div>
		</section>
	</section>
	{!! Form::close() !!}

	<script type="text/javascript" src="{{ asset('extensions/validation/form-validator/jquery.form-validator.min.js') }}"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			if ($(".chosen-select").length) {
				$(".chosen-select").chosen();
			}
		});

		$.validate({
			form: '#groupForm'
		});	
	</script>
@stop