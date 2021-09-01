@extends('layouts.main')
@section('content')	
	@if($option == "View")
	<style type="text/css">
		.text-danger { visibility: hidden; }
	</style>
	@endif

	{!! Form::model($company, ['url' => 'company/save', 'class' => 'form-horizontal', 'autocomplete' => 'off', 'id' => 'groupForm']) !!}
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
							<h3><strong><a href="{{ url('company') }}">{{ $data['page'] }}</a> | {{ $option }}</strong></h3>
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Company Details</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								{!! Form::hidden('id', $id) !!}
								<div class="form-group {{ ($errors->has('c_name') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Company Name: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('c_name', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#c_name', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="c_name" class="align-text"> {{ $errors->first('c_name') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('c_address') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Company Address: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('c_address', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#c_address', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="c_address" class="align-text"> {{ $errors->first('c_address') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('c_acronym') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Acronym:</label>
									<div class="col-md-8">{!! Form::text('c_acronym', null, ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="c_acronym" class="align-text"> {{ $errors->first('c_acronym') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('c_telephone') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Phone Number(s):</label>
									<div class="col-md-8">{!! Form::text('c_telephone', null, ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="c_telephone" class="align-text"> {{ $errors->first('c_telephone') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('c_fax') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Fax Number(s):</label>
									<div class="col-md-8">{!! Form::text('c_fax', null, ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="c_fax" class="align-text"> {{ $errors->first('c_fax') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('c_email') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">E-mail Address:</label>
									<div class="col-md-8">{!! Form::text('c_email', null, ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="c_email" class="align-text"> {{ $errors->first('c_email') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('c_website') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Company Website:</label>
									<div class="col-md-8">{!! Form::text('c_website', null, ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="c_website" class="align-text"> {{ $errors->first('c_website') }}</strong></small></span>
								</div>
								<div class="form-group {{ $option == 'View' ? 'hidden' : ''}}">
									<div class="col-md-3 col-md-offset-2">
										{!! Form::button('<span class="fa fa-save"></span> Save', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
										<a href="{{ url('company') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Cancel</a>
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
		if($(".chosen-select").length) {
			$(".chosen-select").chosen();
		}
		$.validate({
			form: '#groupForm'
		});
	</script>
@stop