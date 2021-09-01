@extends('layouts.main')
@section('content')
	<link rel="stylesheet" href="{{ asset('extensions/chosen/chosen.css') }}" type="text/css">
	<link rel="stylesheet" href="{{ asset('extensions/chosen/chosen-bootstrap.css') }}" type="text/css">
	<script src="{{ asset('extensions/chosen/chosen.jquery.min.js') }}"></script>

	@if($option == "View")
	<style type="text/css">
		.text-danger { visibility: hidden; }
	</style>
	@endif

	{!! Form::model($user, ['url' => 'profile/save', 'class' => 'form-horizontal', 'autocomplete' => 'off', 'id' => 'groupForm', 'files'=>true]) !!}
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
							<h3><strong><a href="{{ url('accounts') }}">{{ $data['page'] }}</a> | {{ $option }}@if($option != 'Add'): {{ $user->u_lname }}, {{ $user->u_fname }}  @endif</strong></h3>
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Access Details</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								{!! Form::hidden('id', $id) !!}
								<div class="form-group {{ ($errors->has('u_username') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Username: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('u_username', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#u_username', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="u_username" class="align-text"> {{ $errors->first('u_username') }}</strong></small></span>
								</div>
								@if($id == 0)
								<div class="form-group {{ ($errors->has('u_password') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Password: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::password('u_password', ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}</div>							
									<span class="text-danger"><small><strong id="u_password" class="align-text"> {{ $errors->first('u_password') }}</strong></small></span>
								</div>
								@else
									@if(Auth::user()->u_id == $user->u_id)
									<div class="form-group {{ ($errors->has('u_password') ? ' has-error ' : '') }} has-feedback">
										<label class="col-md-2 control-label">Password: <span class="text-danger">*</span></label>
										<div class="col-md-8">{!! Form::password('u_password', ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '', 'required']) !!}</div>							
										<span class="text-danger"><small><strong id="u_password" class="align-text"> {{ $errors->first('u_password') }}</strong></small></span>
									</div>
									@endif
								@endif
							</div>
						</div>
					</div>
				</div>

				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Personal Details</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">						
								<div class="form-group {{ ($errors->has('u_fname') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">First Name: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('u_fname', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#u_fname', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="u_fname" class="align-text"> {{ $errors->first('u_fname') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('u_mname') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Middle Name: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('u_mname', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#u_mname', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="u_mname" class="align-text"> {{ $errors->first('u_mname') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('u_lname') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Last Name: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('u_lname', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#u_lname', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="u_lname" class="align-text"> {{ $errors->first('u_lname') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('u_picture') ? ' has-error ' : '') }} has-feedback">
		                            <label class="col-md-2 control-label">
		                            	@if($option == 'View')
		                            	Uploaded Photo:
		                            	@else
			                            	@if($id != 0 && Auth::user()->u_id == $id)
			                            	Upload An Image:
			                            	@endif
		                            	@endif
		                            </label>                            
									<div class="col-md-8">
									@if($option == 'View')
										@if( $user->u_picture != "" )
		                                	@if(is_file($user->u_picture))
		                                		<div><img src="{{ asset($user->u_picture) }}" class="img-circle" width="100" height="100"/></div>
		                                	@else
		                                		<div>Image file missing.</div>
		                                	@endif
		                                @else
		                                	<div>No image uploaded.</div>
		                                @endif
									@else
										@if($id != 0 && Auth::user()->u_id == $id)
										{!! Form::file('u_picture') !!}
										@endif
									@endif							
									</div>
									<span class="text-danger"><small><strong id="u_picture" class="align-text"> {{ $errors->first('u_picture') }}</strong></small></span>
		                        </div>
							</div>
						</div>
					</div>
				</div>

				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Contact Details</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">						
								<div class="form-group {{ ($errors->has('u_mobile') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Mobile Number: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('u_mobile', null, ['class' => 'form-control input-sm align-form', 'placeholder'=>'(e.g. 639101234567)', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#u_mobile', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="u_mobile" class="align-text"> {{ $errors->first('u_mobile') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('u_email') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">E-mail Address: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('u_email', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#u_email', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="u_email" class="align-text"> {{ $errors->first('u_email') }}</strong></small></span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Work Details</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">						
								<div class="form-group {{ ($errors->has('groups') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Unit: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::select('groups', $groups, $option == 'Edit' || $option == 'View' ? $user->group_id : NULL, ['class' => 'form-control input-sm chosen-select', 'data-validation' => 'required', $option == 'View' ? 'disabled' : '']) !!}</div>
									<span class="text-danger"><small><strong id="groups" class="align-text"> {{ $errors->first('groups') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('u_position') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Position: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('u_position', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#u_position', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="u_position" class="align-text"> {{ $errors->first('u_position') }}</strong></small></span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="panel panel-default panel-plain">
					@if(Auth::user()->u_administrator == 1)
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Account Status</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group {{ ($errors->has('usergroups') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">User Group: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::select('usergroups', $usergroups, $option == 'Edit' || $option == 'View' ? $user->ug_id : NULL, ['class' => 'form-control input-sm chosen-select', 'data-validation' => 'required', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="usergroups" class="align-text"> {{ $errors->first('usergroups') }}</strong></small></span>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Is Active: <span class="text-danger">*</span></label>
									<div class="col-md-8">
									@if($option == 'Add')
										<div class="radio-inline"><label>{!! Form::radio('u_active', '1', true, ['required']) !!} Yes</label></div>
										<div class="radio-inline"><label>{!! Form::radio('u_active', '0') !!} No</label></div>
									@elseif($option == 'Edit')
										<div class="radio-inline">
		                                    <label>	                                    	
		                                    	<input <?php if ($user->u_active == 1) echo ' checked="checked" '; ?> type="radio" name="u_active" id="u_active1" value="1" /> Active	                                    	
		                                    </label>
		                                </div>
		                                <div class="radio-inline">
		                                    <label>	                                    	
		                                    	<input <?php if ($user->u_active == 0) echo ' checked="checked" '; ?> type="radio" name="u_active" id="u_active2" value="2" /> Inactive
		                                    </label>
		                                </div>
									@else
										@if($user->u_active == 1)
										{!! Form::text('u_active', 'Active', ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}
										@else
										{!! Form::text('u_active', 'Inactive', ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}
										@endif							
									@endif
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Head Privilege: <span class="text-danger">*</span></label>
									<div class="col-md-8">
									@if($option == 'Add')
										<div class="radio-inline"><label>{!! Form::radio('u_head', '1', true, ['required']) !!} Yes</label></div>
										<div class="radio-inline"><label>{!! Form::radio('u_head', '0') !!} No</label></div>
									@elseif($option == 'Edit')
										<div class="radio-inline">
		                                    <label>	                                    	
		                                    	<input <?php if ($user->u_head == 1) echo ' checked="checked" '; ?> type="radio" name="u_head" id="u_head1" value="1" /> Yes	                                    	
		                                    </label>
		                                </div>
		                                <div class="radio-inline">
		                                    <label>	                                    	
		                                    	<input <?php if ($user->u_head == 0) echo ' checked="checked" '; ?> type="radio" name="u_head" id="u_head2" value="0" /> No
		                                    </label>
		                                </div>
									@else
										@if($user->u_head == 1)
										{!! Form::text('u_head', 'Yes', ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}
										@else
										{!! Form::text('u_head', 'No', ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}
										@endif							
									@endif
									</div>
								</div>	
								<div class="form-group">
									<label class="col-md-2 control-label">Is Administrator: <span class="text-danger">*</span></label>
									<div class="col-md-8">
									@if($option == 'Add')
										<div class="radio-inline"><label>{!! Form::radio('u_administrator', '1', true, ['required']) !!} Yes</label></div>
										<div class="radio-inline"><label>{!! Form::radio('u_administrator', '0') !!} No</label></div>
									@elseif($option == 'Edit')
										<div class="radio-inline">
		                                    <label>	                                    	
		                                    	<input <?php if ($user->u_administrator == 1) echo ' checked="checked" '; ?> type="radio" name="u_administrator" id="u_administrator1" value="1" /> Yes	                                    	
		                                    </label>
		                                </div>
		                                <div class="radio-inline">
		                                    <label>	                                    	
		                                    	<input <?php if ($user->u_administrator == 0) echo ' checked="checked" '; ?> type="radio" name="u_administrator" id="u_administrator2" value="0" /> No
		                                    </label>
		                                </div>
									@else
										@if($user->u_administrator == 1)
										{!! Form::text('u_administrator', 'Yes', ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}
										@else
										{!! Form::text('u_administrator', 'No', ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}
										@endif							
									@endif
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Zoom Account Manager: <span class="text-danger">*</span></label>
									<div class="col-md-8">
									@if($option == 'Add')
										<div class="radio-inline"><label>{!! Form::radio('u_zoom_mgr', '1', ['required']) !!} Yes</label></div>
										<div class="radio-inline"><label>{!! Form::radio('u_zoom_mgr', '0', true) !!} No</label></div>
									@elseif($option == 'Edit')
										<div class="radio-inline">
		                                    <label>	                                    	
		                                    	<input <?php if ($user->u_zoom_mgr == 1) echo ' checked="checked" '; ?> type="radio" name="u_zoom_mgr" id="u_zoom_mgr1" value="1" /> Yes	                                    	
		                                    </label>
		                                </div>
		                                <div class="radio-inline">
		                                    <label>	                                    	
		                                    	<input <?php if ($user->u_zoom_mgr == 0) echo ' checked="checked" '; ?> type="radio" name="u_zoom_mgr" id="u_zoom_mgr2" value="0" /> No
		                                    </label>
		                                </div>
									@else
										@if($user->u_zoom_mgr == 1)
										{!! Form::text('u_zoom_mgr', 'Yes', ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}
										@else
										{!! Form::text('u_zoom_mgr', 'No', ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}
										@endif							
									@endif
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				@endif
			
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div class="col-md-3 col-md-offset-2">									
									@if($option != 'View') {!! Form::button('<span class="fa fa-save"></span> Save', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!} @endif
									<a href="{{ url('accounts') }}" class="btn btn-danger btn-sm"><span class="fa fa-undo"></span> Back</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</section>
	{!! Form::close() !!}
	<script type="text/javascript" src="{{ asset('extensions/validation/form-validator/jquery.form-validator.min.js') }}"></script>
	<script type="text/javascript">
		if($(".chosen-select").length) {
			$(".chosen-select").chosen();
		}
		$.validate({
			form: '#groupForm'
		});
	</script>
@stop