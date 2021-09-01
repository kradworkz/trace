@extends('layouts.main')
@section('content')
	@if($option == "View")
	<style type="text/css">
		.text-danger { display: none; }
	</style>
	@endif

	{!! Form::model($action, ['url' => 'action_settings/save', 'class' => 'form-horizontal', 'autocomplete' => 'off', 'id' => 'groupForm']) !!}
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
							<h3><strong><a href="{{ url('action_settings') }}">Actions</a> | {{ $option }}</strong></h3>
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Action Details</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								{!! Form::hidden('id', $id) !!}
								<div class="form-group {{ ($errors->has('a_action') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Action: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('a_action', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#a_action', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="a_action" class="align-text"> {{ $errors->first('a_action') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('a_number') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Number Arrangement: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('a_number', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#a_number', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="a_number" class="align-text"> {{ $errors->first('a_number') }}</strong></small></span>
								</div>	
								<div class="form-group {{ $option == 'View' ? 'hidden' : ''}}">
									<div class="col-md-3 col-md-offset-2">
										{!! Form::button('<span class="fa fa-save"></span> Save', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
										<a href="{{ url('action_settings') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Cancel</a>
									</div>
								</div>
								<hr>								
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