@extends('layouts.main')
@section('content')
	<link rel="stylesheet" href="{{ asset('extensions/chosen/chosen.min.css') }}" type="text/css">
	<script src="{{ asset('extensions/chosen/chosen.jquery.min.js') }}"></script>
	<link href="{{ asset('extensions/events/datepicker.css') }}" rel="stylesheet">
	<link href="{{ asset('extensions/events/bootstrap-timepicker.css') }}" rel="stylesheet">
	<link href="{{ asset('extensions/events/bootstrap-tagsinput.css') }}" rel="stylesheet">
	<link href="{{ asset('extensions/chat.css') }}" rel="stylesheet">
	<script src="{{ asset('extensions/chat.js') }}"></script>

	@if($option == "View")
	<style type="text/css">
		.text-danger { visibility: hidden; }

		.modal-content{
    		background:#fff;
		}
		
		.modal-dialog{
    		position:absolute;
    		margin-left: -300px;
    		margin-top: 40px;
		}
	</style>
	@endif

	{!! Form::model($event, ['url' => 'zoom_schedules/save', 'class' => 'form-horizontal', 'autocomplete' => 'off', 'id' => 'groupForm', 'files' => true]) !!}
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
							<h3><strong><a href="{{ url('zoom_schedules') }}">{{ $data['page'] }}</a></strong></h3>
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<!-- BASIC EVENT DETAILS -->
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Event Details</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								{!! Form::hidden('id', $id) !!}
								{!! Form::hidden('option', $option) !!}
								<div class="form-group">
									<label class="col-md-2 control-label">Event Name:</label>
									<div class="col-md-8">{!! Form::text('e_name', null, ['class' => 'form-control input-sm align-form', 'disabled']) !!}</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Event Type:</label>
									<div class="col-md-8">{!! Form::text('e_type', null, ['class' => 'form-control input-sm', 'id' => 'type', 'disabled']) !!}</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Start Date:</label>
									<div class="col-md-8">{!! Form::text('e_start_date', null, ['class' => 'form-control input-sm', 'disabled']) !!}</div>
								</div>
								<div class="form-group">
		                            <label class="col-md-2 control-label">Start Time:</label>
		                            <div class="col-md-8">
		                            	<input id="e_start_time" name="e_start_time" type="text" class="form-control input-sm align-form" value="{{ $event->e_start_time }}" readonly>
		                            </div>
		                        </div>
								<div class="form-group">
									<label class="col-md-2 control-label">End Date:</label>
									<div class="col-md-8">{!! Form::text('e_end_date', null, ['class' => 'form-control input-sm', 'disabled']) !!}</div>
								</div>
								<div class="form-group">
		                            <label class="col-md-2 control-label">End Time:</label>
		                            <div class="col-md-8">
		                            	<input id="e_end_time" name="e_end_time" type="text" class="form-control input-sm align-form" value="{{ $event->e_end_time }}" disabled>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="col-md-2 control-label">Staff Involved:</label>
		                            <div class="col-md-8">
		                            	<input class="form-control input-sm align-form" data-role="tagsinput" type="text" value="{{ $event->e_staff }}" name="e_staff" id="e_staff" disabled>
		                        	</div>
		                        </div>
		                        @if($option != 'Add')
		                        @if(Auth::user()->u_id == $event->u_id || Auth::user()->u_zoom_mgr == 1)
								<div class="form-group">
									<label class="col-md-2 control-label">Desired Password for Zoom Meeting:</label>
									<div class="col-md-8">{!! Form::text('e_zoom_pw', null, ['class' => 'form-control input-sm align-form', 'disabled']) !!}</div>
								</div>
								@endif
								<?php $attend_checker = App\Models\EventSeen::where('e_id', $event->e_id)->where('es_invited', 1)->where('u_id', Auth::user()->u_id)->count(); ?>
								@if($attend_checker == 1)
								<div class="form-group">
									<label class="col-md-2 control-label">Desired Password for Zoom Meeting:</label>
									<div class="col-md-8">{!! Form::text('e_zoom_pw', null, ['class' => 'form-control input-sm align-form', 'disabled']) !!}</div>
								</div>
								@endif
								@endif
							</div>
						</div>
					</div>
				</div>

				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Zoom Room Details</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group {{ ($errors->has('e_zoom_approved') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Approve Request?: <span class="text-danger">*</span></label>
									<div class="col-md-8">
										@if($option == 'Edit')
										<div class="radio-inline"><label>{!! Form::radio('e_zoom_approved', '1', ['required']) !!} Yes.</label></div>
										<br>
										<div class="radio-inline"><label>{!! Form::radio('e_zoom_approved', '0', true) !!} No.</label></div>
										@endif
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Reason For Disapproving:</label>
									<div class="col-md-8">{!! Form::text('e_zoom_reason', null, ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}</div>
								</div>
								<div class="form-group {{ ($errors->has('zooms') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Select Zoom Account: </label>
									<div class="col-md-8">{!! Form::select('zooms', $zooms, $option == 'Edit' || $option == 'View' ? $event->zs_id : NULL, ['class' => 'form-control input-sm chosen-select', $option == 'View' ? 'readonly' : '']) !!}</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Online Meeting URL:</label>
									<div class="col-md-8">{!! Form::text('e_zoom_link', null, ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="e_zoom_link" class="align-text"> {{ $errors->first('e_zoom_link') }}</strong></small></span>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Online Meeting ID:</label>
									<div class="col-md-8">{!! Form::text('e_zoom_mtgid', null, ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Online Meeting Password:</label>
									<div class="col-md-8">{!! Form::text('e_pwd', null, ['class' => 'form-control input-sm align-form']) !!}</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Possible Date Conflicts:</label>
									<div class="col-md-8">
										@foreach($conflicts as $conflict)
											<a href="{{ url('events/view/'.$conflict->e_id) }}" target="_blank">{{ $conflict->e_name }}</a> ({{ $conflict->e_start_time }} to {{ $conflict->e_end_time }})<br>
										@endforeach
									</div>
								</div>
								@if($option == 'Edit')
								<div class="form-group">
									<div class="col-md-3 col-md-offset-2">								
										{!! Form::button('<span class="fa fa-save"></span> Save', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
										<a href="{{ url('zoom_schedules') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Cancel</a>														
									</div>
								</div>
								@endif
							</div>
						</div>
					</div>
				</div>

				<!-- ATTENDEES -->
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Attendees</strong> (Total #: {{ App\Models\EventSeen::where('e_id', $id)->where('es_invited', 1)->count() }})</h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">						
								@if($attendees->isEmpty())
									<div class="panel-body">
										<h5><strong><i class="fa fa-info-circle"></i> No attendee(s) found.</strong></h5>
									</div>														
								@else
									<table class="table">
										<thead>
											<tr>
												<th>Name</th>										
												<th>Date Invited</th>
											</tr>
										</thead>
										<tbody>
											@foreach($attendees as $attendee)
											<tr>
												<td>{{ $attendee->seen->u_lname }}, {{ $attendee->seen->u_fname }}</td>
												<td>{{ date('F d, Y h:i A', strtotime($attendee->created_at)) }}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
									@if($attendees->render())
										<div class="panel-footer text-center">{!! $attendees->render() !!}</div>
									@endif
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
			{!! Form::close() !!}

			<script type="text/javascript" src="{{ asset('extensions/validation/form-validator/jquery.form-validator.min.js') }}"></script>
			<script src="{{ asset('extensions/events/bootstrap-datepicker.js') }}"></script>
			<script src="{{ asset('extensions/events/bootstrap-timepicker.js') }}"></script>	
			<script src="{{ asset('extensions/events/bootstrap-tagsinput.min.js') }}"></script>
			<script src="{{ asset('extensions/events/bootstrap-tagsinput-angular.min.js') }}"></script>

			@if($option == 'View')
			<script>
				$(document).ready(function() {
					$("input[type=radio]").attr('disabled', false);
				}
			</script>
			@endif

			<script type="text/javascript">
				if($(".chosen-select").length) {
					$(".chosen-select").chosen();
				}
				$.validate({
					form: '#groupForm'
				});

				$.get('{{ url("events/types") }}', function(data) {
					var types = [];
					$.each(data, function(i, e) {
						types.push(e.e_type.replace(/\s+/g, " "));
					});
					$.formUtils.suggest($('#type'), types);
				});

				$.get('{{ url("events/venues") }}', function(data) {
					var venues = [];
					$.each(data, function(i, e) {
						venues.push(e.e_venue.replace(/\s+/g, " "));
					});
					$.formUtils.suggest($('#venue'), venues);
				});

				$(document).ready(function() {
		        	$('#e_start_time').timepicker();
		        	$('#e_end_time').timepicker();

		        	var date 	= new Date();	        	
					date.setDate(date.getDate());		

					$('#e_start_date').datepicker({ 			    
					    format: "yyyy-mm-dd"
					});

					$('#e_end_date').datepicker({ 			    
					    format: "yyyy-mm-dd"
					});
				});
			</script>
		</section>
	</section>
@stop