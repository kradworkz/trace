@extends('layouts.guest')
@section('content')
	<link rel="stylesheet" href="{{ asset('extensions/chosen/chosen.css') }}" type="text/css">
	<link rel="stylesheet" href="{{ asset('extensions/chosen/chosen-bootstrap.css') }}" type="text/css">
	<script src="{{ asset('extensions/chosen/chosen.jquery.min.js') }}"></script>

	{!! Form::open(['url'=>'register', 'class'=>'form-horizontal', 'files'=>true, 'autocomplete' => 'off', 'id' => 'groupForm']) !!}	
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="pull-left">
					<h3><strong><a href="{{ url('/') }}">Home</a> | {{ $page }}</strong></h3>
				</div>
				<div class="pull-right">
					<button title="Save" type="submit" class="btn btn-primary btn-circle btn-md"><i class="fa fa-save"></i> Save</button>&nbsp;
					<a href="{{ URL::to('/') }}" title="Cancel" class="btn btn-danger btn-circle btn-md"><i class="fa fa-undo"></i> Back</a>
				</div>
			</div>
		</div>
		<div class="panel panel-default panel-plain">
			<div class="panel-heading">
				<h1 class="panel-title"><strong>Account Details</strong></h1>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group {{ ($errors->has('u_username') ? ' has-error ' : '') }} has-feedback">
							<label class="col-md-2 control-label">Username: <span class="text-danger">*</span></label>
							<div class="col-md-8">{!! Form::text('u_username', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#u_username']) !!}</div>
							<span class="text-danger"><small><strong id="u_username" class="align-text"> {{ $errors->first('u_username') }}</strong></small></span>
						</div>
						<div class="form-group {{ ($errors->has('u_password') ? ' has-error ' : '') }} has-feedback">
							<label class="col-md-2 control-label">Password: <span class="text-danger">*</span></label>
							<div class="col-md-8">{!! Form::password('u_password', ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#u_password']) !!}</div>							
							<span class="text-danger"><small><strong id="u_password" class="align-text"> {{ $errors->first('u_password') }}</strong></small></span>
						</div>
					</div>
				</div>
			</div>

			<div class="panel-heading">
				<h1 class="panel-title"><strong>Personal Details</strong></h1>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">						
						<div class="form-group {{ ($errors->has('u_fname') ? ' has-error ' : '') }} has-feedback">
							<label class="col-md-2 control-label">First Name: <span class="text-danger">*</span></label>
							<div class="col-md-8">{!! Form::text('u_fname', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#u_fname']) !!}</div>
							<span class="text-danger"><small><strong id="u_fname" class="align-text"> {{ $errors->first('u_fname') }}</strong></small></span>
						</div>
						<div class="form-group {{ ($errors->has('u_mname') ? ' has-error ' : '') }} has-feedback">
							<label class="col-md-2 control-label">Middle Name: <span class="text-danger">*</span></label>
							<div class="col-md-8">{!! Form::text('u_mname', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#u_mname']) !!}</div>
							<span class="text-danger"><small><strong id="u_mname" class="align-text"> {{ $errors->first('u_mname') }}</strong></small></span>
						</div>
						<div class="form-group {{ ($errors->has('u_lname') ? ' has-error ' : '') }} has-feedback">
							<label class="col-md-2 control-label">Last Name: <span class="text-danger">*</span></label>
							<div class="col-md-8">{!! Form::text('u_lname', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#u_lname']) !!}</div>
							<span class="text-danger"><small><strong id="u_lname" class="align-text"> {{ $errors->first('u_lname') }}</strong></small></span>
						</div>
						<div class="form-group {{ ($errors->has('u_picture') ? ' has-error ' : '') }} has-feedback">
                            <label class="col-md-2 control-label">Upload An Image:</label>
							<div class="col-md-8">{!! Form::file('u_picture') !!}</div>
							<span class="text-danger"><small><strong id="u_picture" class="align-text"> {{ $errors->first('u_picture') }}</strong></small></span>
                        </div>
					</div>
				</div>
			</div>

			<div class="panel-heading">
				<h1 class="panel-title"><strong>Other Details</strong></h1>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">						
						<div class="form-group {{ ($errors->has('u_mobile') ? ' has-error ' : '') }} has-feedback">
							<label class="col-md-2 control-label">Mobile Number: <span class="text-danger">*</span></label>
							<div class="col-md-8">{!! Form::text('u_mobile', null, ['class' => 'form-control input-sm align-form', 'placeholder'=>'(e.g. 639101234567)', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#u_mobile']) !!}</div>
							<span class="text-danger"><small><strong id="u_mobile" class="align-text"> {{ $errors->first('u_mobile') }}</strong></small></span>
						</div>
						<div class="form-group {{ ($errors->has('u_email') ? ' has-error ' : '') }} has-feedback">
							<label class="col-md-2 control-label">E-mail Address: <span class="text-danger">*</span></label>
							<div class="col-md-8">{!! Form::text('u_email', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#u_email']) !!}</div>
							<span class="text-danger"><small><strong id="u_email" class="align-text"> {{ $errors->first('u_email') }}</strong></small></span>
						</div>
						<div class="form-group {{ ($errors->has('groups') ? ' has-error ' : '') }} has-feedback">
							<label class="col-md-2 control-label">Unit: <span class="text-danger">*</span></label>
							<div class="col-md-8">{!! Form::select('groups', $groups, NULL, ['class' => 'form-control input-sm chosen-select', 'data-validation' => 'required']) !!}</div>							
							<span class="text-danger"><small><strong id="groups" class="align-text"> {{ $errors->first('groups') }}</strong></small></span>
						</div>
						<div class="form-group {{ ($errors->has('u_position') ? ' has-error ' : '') }} has-feedback">
							<label class="col-md-2 control-label">Position: <span class="text-danger">*</span></label>
							<div class="col-md-8">{!! Form::text('u_position', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#u_position']) !!}</div>
							<span class="text-danger"><small><strong id="u_position" class="align-text"> {{ $errors->first('u_position') }}</strong></small></span>
						</div>
						<hr>
						<div class="form-group">
							<div class="col-md-3 col-md-offset-2">
								{!! Form::button('<span class="fa fa-save"></span> Save', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
								<a href="{{ url('/') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Cancel</a>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
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