@extends('layouts.main')
@section('content')
	{!! Form::model($settings, ['url' => 'settings/save', 'class' => 'form-horizontal', 'autocomplete' => 'off', 'id' => 'groupForm', 'files' => 'true']) !!}
	<section id="main-content">
    	<section class="wrapper">
			<div class="container">			
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Website Information</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group {{ ($errors->has('s_sysname') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">System Name: <span class="text-danger">*</span> </label>
									<div class="col-md-8">{!! Form::text('s_sysname', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#s_sysname', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="s_sysname" class="align-text"> {{ $errors->first('s_sysname') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('s_abbr') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">System Acronym: <span class="text-danger">*</span> </label>
									<div class="col-md-8">{!! Form::text('s_abbr', NULL, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#s_abbr', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="s_abbr" class="align-text"> {{ $errors->first('s_abbr') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('s_pending_days') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Pending Days: <span class="text-danger">*</span> </label>
									<div class="col-md-8">{!! Form::text('s_pending_days', NULL, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#s_pending_days', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="s_pending_days" class="align-text"> {{ $errors->first('s_pending_days') }}</strong></small></span>
								</div>
								
								<div class="form-group">
									<div class="col-md-5 col-md-offset-2">
										{!! Form::button('<span class="fa fa-save"></span> Update', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
									</div>
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
		$.validate({
			form: '#groupForm'
		});
	</script>
@stop