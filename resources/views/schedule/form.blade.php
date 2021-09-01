@extends('layouts.main')
@section('content')
	<link rel="stylesheet" href="{{ asset('extensions/chosen/chosen.css') }}" type="text/css">
	<link rel="stylesheet" href="{{ asset('extensions/chosen/chosen-bootstrap.css') }}" type="text/css">	
	<script src="{{ asset('extensions/chosen/chosen.jquery.min.js') }}"></script>
	<link href="{{ asset('extensions/events/datepicker.css') }}" rel="stylesheet">
	<link href="{{ asset('extensions/events/bootstrap-timepicker.css') }}" rel="stylesheet">

	@if($option == 'View')
		<style type="text/css">
			.text-danger { visibility: hidden; }
		</style>
	@endif

	{!! Form::model($schedule, ['url' => 'rd_schedule/save', 'class' => 'form-horizontal', 'autocomplete' => 'off', 'id' => 'groupForm', 'files' => 'true']) !!}
	<section id="main-content">
    	<section class="wrapper">
			<div class="container">
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Meeting Details</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								{!! Form::hidden('id', $id) !!}
								{!! Form::hidden('page', $data['page']) !!}	
								{!! Form::hidden('option', $option) !!}					
								<div class="form-group {{ ($errors->has('m_startdate') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Start Date: <span class="text-danger">*</span></label>
									<div class="col-md-8">
									@if($option == 'Add' || $option == 'Edit')
									{!! Form::text('m_startdate', null, ['class' => 'form-control input-sm', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#m_startdate', 'id' => 'm_startdate', 'maxlength' => '10', 'required', 'placeholder' => 'Start Date', 'readonly']) !!}									
									@else
									{!! Form::text('m_startdate', Carbon\Carbon::parse($schedule->m_startdate)->format('F d, Y'), ['class' => 'form-control input-sm', 'maxlength' => '10', 'required', 'placeholder' => 'Date', 'readonly']) !!}
									@endif
									</div>
									<span class="text-danger"><small><strong id="m_startdate" class="align-text"> {{ $errors->first('m_startdate') }}</strong></small></span>
								</div>						
								<div class="form-group {{ ($errors->has('m_starttime') ? ' has-error ' : '') }} has-feedback">
		                            <label class="col-md-2 control-label">Start Time: <span class="text-danger">*</span></label>
		                            <div class="col-md-8">
		                            @if($option == 'Add')
		                            <input id="m_starttime" name="m_starttime" type="text" class="form-control input-sm align-form" data-validation="required" data-validation-error-msg-container="#m_starttime" readonly required>
		                            @elseif($option == 'Edit')
		                            <input id="m_starttime" name="m_starttime" type="text" class="form-control input-sm align-form" data-validation="required" data-validation-error-msg-container="#m_starttime" value="{{ $schedule->m_starttime }}" readonly required>
		                            @else
		                            {!! Form::text('m_starttime', null, ['class' => 'form-control input-sm', 'maxlength' => '10', 'required', 'readonly']) !!}
		                            @endif
		                            </div>
		                            <span class="text-danger"><small><strong id="m_starttime" class="align-text"> {{ $errors->first('m_starttime') }}</strong></small></span>
		                        </div>
								<div class="form-group {{ ($errors->has('m_enddate') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">End Date: <span class="text-danger">*</span></label>
									<div class="col-md-8">
									@if($option == 'Add' || $option == 'Edit')
									{!! Form::text('m_enddate', null, ['class' => 'form-control input-sm', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#m_enddate', 'id' => 'm_enddate', 'maxlength' => '10', 'required', 'placeholder' => 'Start Date', 'readonly']) !!}
									@else
									{!! Form::text('m_enddate', Carbon\Carbon::parse($schedule->m_enddate)->format('F d, Y'), ['class' => 'form-control input-sm', 'maxlength' => '10', 'required', 'placeholder' => 'Date', 'readonly']) !!}
									@endif
									</div>
									<span class="text-danger"><small><strong id="m_enddate" class="align-text"> {{ $errors->first('m_enddate') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('m_endtime') ? ' has-error ' : '') }} has-feedback">
		                            <label class="col-md-2 control-label">End Time: <span class="text-danger">*</span></label>                            
		                            <div class="col-md-8">
		                            @if($option == 'Add')
		                            <input id="m_endtime" name="m_endtime" type="text" class="form-control input-sm align-form" data-validation="required" data-validation-error-msg-container="#m_endtime" readonly required>
		                            @elseif($option == 'Edit')
		                            <input id="m_endtime" name="m_endtime" type="text" class="form-control input-sm align-form" data-validation="required" data-validation-error-msg-container="#m_endtime" value="{{ $schedule->m_endtime }}" readonly required>
		                            @else
		                            {!! Form::text('m_endtime', null, ['class' => 'form-control input-sm', 'maxlength' => '10', 'required', 'readonly']) !!}
		                            @endif
		                            </div>                           
		                            <span class="text-danger"><small><strong id="m_endtime" class="align-text"> {{ $errors->first('m_endtime') }}</strong></small></span>
		                        </div>
		                        <div class="form-group {{ ($errors->has('m_purpose') ? ' has-error ' : '') }} has-feedback">
		                            <label class="col-md-2 control-label">State Purpose: <span class="text-danger">*</span></label>
		                            <div class="col-md-8">{!! Form::textarea('m_purpose', NULL, ['class' => 'form-control input-sm', 'size' => '10x5', 'required', $option == 'View' ? 'readonly' : '']) !!}</div>
		                            <span class="text-danger"><small><strong id="m_purpose" class="align-text"> {{ $errors->first('m_purpose') }}</strong></small></span>
		                        </div>
		                        <div class="form-group {{ ($errors->has('m_destination') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Destination: <span class="text-danger">*</span></label>
									<div class="col-md-8">
									@if($option == 'Add' || $option == 'Edit')
									{!! Form::text('m_destination', null, ['class' => 'form-control input-sm', 'id' => 'destination', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#m_destination']) !!}
									@else
									{!! Form::text('m_destination', null, ['class' => 'form-control input-sm align-form', 'readonly']) !!}
									@endif
									</div>
									<span class="text-danger"><small><strong id="m_destination" class="align-text"> {{ $errors->first('m_destination') }}</strong></small></span>
								</div>
								@if($option == 'Add')
								<div class="form-group">
		                            <label class="col-md-2 control-label">Requesting Party: <span class="text-danger">*</span></label>
									<div class="col-md-8">								
										<select data-placeholder="Tag Individually" class="chosen-select form-control input-sm" multiple name="individualTag[]" id="individualTag">
		                        			<option value=""></option>
		                                	@foreach($members as $member)
		                                		<option value="<?php echo $member['u_id']; ?>"><?php echo ucfirst($member['u_lname']).", ".ucfirst($member['u_fname']); ?></option>
		                                	@endforeach
		                                </select>
									</div>
		                        </div>
		                        @endif
		                        <div class="form-group">
		                            <label class="col-md-2 control-label">Other Participants:</label>
		                            <div class="col-md-8">{!! Form::textarea('m_others', NULL, ['class' => 'form-control input-sm', 'size' => '10x5', 'placeholder'=>'Non-DOST participants', $option == 'View' ? 'readonly' : '']) !!}</div>                        
		                        </div>
								<div class="form-group">
									<div class="col-md-5 col-md-offset-2">
										@if($option != 'View')
										{!! Form::button('<span class="fa fa-save"></span> Save', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
										@endif
										<a href="{{ url('rd_schedule') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Cancel</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				@if($id != 0 && $schedule->m_postponedby != "" && $option == 'View' && $schedule->m_status == 'Pending')
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Proposed New Schedule</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="col-md-2 control-label">New Date:</label>
									<div class="col-md-8">
										<?php 	$start 	= date('F d, Y', strtotime($schedule->m_tstartdate));
												$end 	= date('F d, Y', strtotime($schedule->m_tenddate)); ?>
										{!! Form::hidden('startdate', $schedule->m_tstartdate) !!}
										{!! Form::hidden('enddate', $schedule->m_tenddate) !!}
										{!! Form::hidden('starttime', $schedule->m_tstarttime) !!}
										{!! Form::hidden('endtime', $schedule->m_tendtime) !!}
										@if($start != $end)
											{!! Form::text('m_tstartdate', Carbon\Carbon::parse($schedule->m_tstartdate)->format('F d, Y')." to ".Carbon\Carbon::parse($schedule->m_tenddate)->format('F d, Y'), ['class' => 'form-control input-sm', 'maxlength' => '10', 'required', 'placeholder' => 'Date', 'readonly']) !!}
										@else
											{!! Form::text('m_tstartdate', Carbon\Carbon::parse($schedule->m_tstartdate)->format('F d, Y'), ['class' => 'form-control input-sm', 'maxlength' => '10', 'required', 'placeholder' => 'Date', 'readonly']) !!}
										@endif
									</div>
								</div>
								<div class="form-group">
		                            <label class="col-md-2 control-label">New Time: <span class="text-danger">*</span></label>
		                            <div class="col-md-8">
		                            	{!! Form::text('m_tstarttime', $schedule->m_tstarttime." to ".$schedule->m_tendtime, ['class' => 'form-control input-sm', 'maxlength' => '10', 'required', 'readonly']) !!}
		                            </div>
		                        </div>
		                        @if($schedule->m_postponedby != Auth::user()->u_id)
		                        <div class="form-group">
									<div class="col-md-5 col-md-offset-2">
										{!! Form::button('<span class="fa fa-thumbs-up"></span> Approve New Schedule', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}								
									</div>
								</div>
								@endif
							</div>
						</div>
					</div>
				</div>
				@endif

				@if($option == 'View' && $schedule->m_status == 'Canceled')
				<div class="panel panel-default panel-pain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Reason of Cancelation</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								{!! Form::textarea('m_reason', NULL, ['class' => 'form-control input-sm', 'size' => '10x5', 'disabled']) !!}
							</div>
						</div>
					</div>
				</div>
				@endif

				@if($option == 'View' || $option == 'Edit')		
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Requesting Party</strong></h1>
					</div>			
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								@if($schedule->m_encodedby == Auth::user()->u_id)
								<div class="form-group">
					                <label class="col-md-2 control-label">Add Participant(s):</label>
									<div class="col-md-8">								
										<select data-placeholder="Tag Individually" class="chosen-select form-control input-sm" multiple name="individualTag[]" id="individualTag">
					            			<option value=""></option>
					                    	@foreach($members as $member)
					                    		<option value="<?php echo $member['u_id']; ?>"><?php echo ucfirst($member['u_lname']).", ".ucfirst($member['u_fname']); ?></option>
					                    	@endforeach
					                    </select>
									</div>
					            </div>
					            <div class="form-group">
									<div class="col-md-5 col-md-offset-2">
										{!! Form::button('<span class="fa fa-bell"></span> Notify Participants', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
										<a href="{{ url('meetings') }}" class="btn btn-danger btn-sm"><span class="fa fa-undo"></span> Back To Meetings</a>
									</div>
								</div>						
					            <hr>
					            @endif
								@if($participants->isEmpty())							
									<div class="panel-body">
										<h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No existing member(s) found.</strong></h5>
									</div>
								@else
									<table class="table">
										<thead>
											<tr>
												<th>Participant's Name</th>
												<th>Date Invited</th>
												@if($schedule->m_encodedby == Auth::user()->u_id)
												<th></th>
												@endif
											</tr>
										</thead>
										<tbody>
											@foreach($participants as $participant)
												<tr>
													<td>{{ $participant->users->u_lname }}, {{ $participant->users->u_fname }}</td>											
													<td>{{ date('F d, Y h:i A', strtotime($participant->created_at)) }}</td>
													@if($schedule->m_encodedby == Auth::user()->u_id)
													<td class="text-right">																							
														<a href="{{ url('rd_schedule/remove-participant/'.$participant->p_id) }}" class="btn btn-danger btn-xs" title="Remove"><span class="fa fa-trash-o"></span></a>												
													</td>
													@endif
												</tr>
											@endforeach
										</tbody>
									</table>
									@if($participants->render())
										<div class="text-center">{!! $participants->render() !!}</div>
									@endif
								@endif							
							</div>
						</div>				
					</div>	
				</div>
				@endif
			</div>
		</section>
	</section>
	{!! Form::close() !!}
	
	<script type="text/javascript" src="{{ asset('extensions/validation/form-validator/jquery.form-validator.min.js') }}"></script>
	<script src="{{ asset('extensions/events/bootstrap-datepicker.js') }}"></script>
	<script src="{{ asset('extensions/events/bootstrap-timepicker.js') }}"></script>	
	<script type="text/javascript">
		if($(".chosen-select").length) {
			$(".chosen-select").chosen();
		}

		$.validate({
			form: '#groupForm'
		});

		$.get('{{ url("rd_schedule/destinations") }}', function(data) {
			var destinations = [];
			$.each(data, function(i, e) {
				destinations.push(e.m_destination.replace(/\s+/g, " "));
			});
			$.formUtils.suggest($('#destination'), destinations);
		});

		$(document).ready(function() {
        	$('#m_starttime').timepicker();
        	$('#m_endtime').timepicker();
        	$('#m_tstarttime').timepicker();
        	$('#m_tendtime').timepicker();

        	var date 	= new Date();	        	
			date.setDate(date.getDate());		

			$('#m_startdate').datepicker({ 			    
			    format: "yyyy-mm-dd"
			});

			$('#m_enddate').datepicker({ 			    
			    format: "yyyy-mm-dd"
			});

			$('#m_tstartdate').datepicker({
				startDate: date,	
			    format: "yyyy-mm-dd"
			});

			$('#m_tenddate').datepicker({
				startDate: date,
			    format: "yyyy-mm-dd"
			});
		});
	</script>
@stop