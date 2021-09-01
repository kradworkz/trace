@extends('layouts.main')
@section('content')
	<link rel="stylesheet" href="{{ asset('extensions/chosen/chosen.css') }}" type="text/css">
	<link rel="stylesheet" href="{{ asset('extensions/chosen/chosen-bootstrap.css') }}" type="text/css">
	<script src="{{ asset('extensions/chosen/chosen.jquery.min.js') }}"></script>

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
							<h3 class="page-header"><i class="fa fa-file-o"></i><strong>{{ $data['page'] }} || COST REDUCTION</strong> 
						</div>
					</div>
				</div>
				{!! Form::open(['url' => 'report/cost_reduction/show', 'class' => 'form-horizontal', 'role' => 'form', 'autocomplete' => 'off', 'target'=>'_blank']) !!}
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Report Parameters</strong></h1>
					</div>
					<?php
						$start_date = App\Models\Document::where('d_status', 'Incoming')->where('d_routingthru', '!=', 0)->orderBy('created_at', 'asc')->value('created_at');
						$end_date 	= App\Models\Document::where('d_status', 'Incoming')->where('d_routingthru', '!=', 0)->orderBy('updated_at', 'desc')->value('updated_at');
						$first 		= date('Y', strtotime($start_date));
						$last 		= date('Y', strtotime($end_date));
						$years      = array_combine(range(date("Y"), $first), range(date("Y"), $first));
					?>
					<div class="panel-body">
						<div class="row">
							<div class="form-group">
								<label class="col-md-2 control-label">Select Year</label>
								<div class="col-md-8">
									{!! Form::select('year', $years, $last, ['class' => 'chosen-select form-control input-sm']) !!}
								</div>					
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Select Type</label>
								<div class="col-md-8">							
									{!! Form::select('docType', [	
									   	'Incoming' 	=> 'Incoming Documents Only',
									   	'Outgoing'  => 'Outgoing Documents Only',
									   	'Both'		=> 'Both'],
									   	null, ['class' => 'chosen-select form-control input-sm']) 
									!!}				
								</div>
							</div>
							<div class="form-group {{ ($errors->has('printCost') ? ' has-error ' : '') }} has-feedback">
								<label class="col-md-2 control-label">Enter Page Cost: <span class="text-danger">*</span></label>
								<div class="col-md-8">{!! Form::text('printCost', null, ['class' => 'form-control input-sm align-form', 'id'=>'printCost', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#printCost']) !!}</div>
								<span class="text-danger" id="errmsg"></span>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="pull-right">
								{!! Form::button('Generate Report', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit', 'title' => 'Search', 'id'=>'search-btn', 'name'=>'search-btn']) !!}
								<a href="{{ url('report/cost_reduction') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Reset</a>
							</div>
						</div>
					</div>
				</div>
				
				{!! Form::close() !!}
			</div>
		</section>
	</section>

	<script type="text/javascript" src="{{ asset('extensions/validation/form-validator/jquery.form-validator.min.js') }}"></script>
	<script type="text/javascript">
		if($(".chosen-select").length) {
			$(".chosen-select").chosen();
		}
		$.validate({
			form: '#groupForm'
		});
	</script>

	<script>
		$(document).ready(function () {
		 	//called when key is pressed in textbox
		  	$("#printCost").keypress(function (e) {
		    	//if the letter is not digit then display error and don't type anything
		     	if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
		        	//display error message
		        	$("#errmsg").html("Digits Only").show().fadeOut("slow");
		            return false;
		    	}
		    });
		});
	</script>
@stop