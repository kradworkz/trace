@extends('layouts.main')
@section('content')
	@if($option == "View")
	<style type="text/css">
		.text-danger { display: none; }
	</style>
	@endif

	{!! Form::model($zoom, ['url' => 'zoom_settings/save', 'class' => 'form-horizontal', 'autocomplete' => 'off', 'id' => 'groupForm']) !!}
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
							<h3><strong>Zoom Settings | {{ $option }}</strong></h3>
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Zoom Settings</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								{!! Form::hidden('id', $id) !!}
								<div class="form-group {{ ($errors->has('zs_email') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Zoom Pro Account E-mail: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('zs_email', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#zs_email', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="zs_email" class="align-text"> {{ $errors->first('zs_email') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('zs_password') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Zoom Pro Account Password: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('zs_password', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#zs_password', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="zs_password" class="align-text"> {{ $errors->first('zs_password') }}</strong></small></span>
								</div>
								<div class="form-group {{ $option == 'View' ? 'hidden' : ''}}">
									<div class="col-md-3 col-md-offset-2">
										{!! Form::button('<span class="fa fa-save"></span> Save', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
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