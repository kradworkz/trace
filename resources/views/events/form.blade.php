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
		/*input { pointer-events: none; }*/

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

	{!! Form::model($event, ['url' => 'events/save', 'class' => 'form-horizontal', 'autocomplete' => 'off', 'id' => 'groupForm', 'files' => true]) !!}
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
							<h3><strong><a href="{{ url('events') }}">{{ $data['page'] }}</a> | {{ $option }} @if($option == 'View' && $event->u_id == Auth::user()->u_id)| <a href="{{ url('events/edit/'.$event->e_id) }}">Edit</a>@elseif($option == 'Edit')| <a href="{{ url('events/view/'.$event->e_id) }}">View</a> @endif </strong></h3>
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
								<div class="form-group {{ ($errors->has('e_name') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Event Name: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('e_name', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#e_name', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="e_name" class="align-text"> {{ $errors->first('e_name') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('e_type') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Event Type: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('e_type', null, ['class' => 'form-control input-sm', 'id' => 'type', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#e_type']) !!}</div>
									<span class="text-danger"><small><strong id="e_type" class="align-text"> {{ $errors->first('e_type') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('e_start_date') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Start Date: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('e_start_date', null, ['class' => 'form-control input-sm', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#e_start_date', 'id' => 'e_start_date', 'maxlength' => '10', 'required', 'placeholder' => 'Start Date', 'readonly']) !!}</div>
									<span class="text-danger"><small><strong id="e_start_date" class="align-text"> {{ $errors->first('e_start_date') }}</strong></small></span>
								</div>						
								<div class="form-group {{ ($errors->has('e_start_time') ? ' has-error ' : '') }} has-feedback">
		                            <label class="col-md-2 control-label">Start Time: <span class="text-danger">*</span></label>
		                            <div class="col-md-8">
		                            @if($option == 'Add')
		                            	<input id="e_start_time" name="e_start_time" type="text" class="form-control input-sm align-form" data-validation="required" data-validation-error-msg-container="#e_start_time" readonly required>
		                            @elseif($option == 'Edit')
		                            	<input id="e_start_time" name="e_start_time" type="text" class="form-control input-sm align-form" data-validation="required" data-validation-error-msg-container="#e_start_time" value="{{ $event->e_start_time }}" readonly required>
		                            @elseif($option == 'View')
		                            	<input id="e_start_time" name="e_start_time" type="text" class="form-control input-sm align-form" data-validation="required" data-validation-error-msg-container="#e_start_time" value="{{ $event->e_start_time }}" readonly required>
		                            @endif
		                            </div>
		                            <span class="text-danger"><small><strong id="e_start_time" class="align-text"> {{ $errors->first('e_start_time') }}</strong></small></span>
		                        </div>
								<div class="form-group {{ ($errors->has('e_end_date') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">End Date: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('e_end_date', null, ['class' => 'form-control input-sm', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#e_end_date', 'id' => 'e_end_date', 'maxlength' => '10', 'required', 'placeholder' => 'End Date', 'readonly']) !!}</div>
									<span class="text-danger"><small><strong id="e_end_date" class="align-text"> {{ $errors->first('e_end_date') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('e_end_time') ? ' has-error ' : '') }} has-feedback">
		                            <label class="col-md-2 control-label">End Time: <span class="text-danger">*</span></label>
		                            <div class="col-md-8">
		                            @if($option == 'Add')
		                            	<input id="e_end_time" name="e_end_time" type="text" class="form-control input-sm align-form" data-validation="required" data-validation-error-msg-container="#e_end_time" readonly required>
		                           	@elseif($option == 'Edit')
		                           		<input id="e_end_time" name="e_end_time" type="text" class="form-control input-sm align-form" data-validation="required" data-validation-error-msg-container="#e_end_time" value="{{ $event->e_end_time }}" readonly required>
		                           	@elseif($option == 'View')
		                           		<input id="e_end_time" name="e_end_time" type="text" class="form-control input-sm align-form" data-validation="required" data-validation-error-msg-container="#e_end_time" value="{{ $event->e_end_time }}" readonly required>
		                           	@endif
		                            </div>
		                            <span class="text-danger"><small><strong id="e_end_time" class="align-text"> {{ $errors->first('e_end_time') }}</strong></small></span>
		                        </div>
		                        <div class="form-group {{ ($errors->has('e_keywords') ? ' has-error ' : '') }} has-feedback">
		                            <label class="col-md-2 control-label">Keywords: <span class="text-danger">*</span></label>
		                            <div class="col-md-8">
		                            @if($option == 'Add' || $option == 'Edit')
		                            <input class="form-control input-sm align-form" data-role="tagsinput" data-validation="required" data-validation-error-msg-container="#e_keywords" type="text" @if($option=='Add') value="" @else value="{{ $event->e_keywords }}" @endif name="e_keywords" id="e_keywords" placeholder="Separate by comma" required></div>
		                            @else
		                            <input class="form-control input-sm align-form" data-role="tagsinput" data-validation="required" data-validation-error-msg-container="#e_keywords" type="text" @if($option=='Add') value="" @else value="{{ $event->e_keywords }}" @endif name="e_keywords" id="e_keywords"></div>
		                            @endif
		                            <span class="text-danger"><small><strong id="e_keywords" class="align-text"> {{ $errors->first('e_keywords') }}</strong></small></span>
		                        </div>
		                        <div class="form-group {{ ($errors->has('e_staff') ? ' has-error ' : '') }} has-feedback">
		                            <label class="col-md-2 control-label">Staff Involved: <span class="text-danger">*</span></label>
		                            <div class="col-md-8">
		                            @if($option == 'Add' || $option == 'Edit')
		                            <input class="form-control input-sm align-form" data-role="tagsinput" data-validation="required" data-validation-error-msg-container="#e_staff" type="text" @if($option=='Add') value="" @else value="{{ $event->e_staff }}" @endif name="e_staff" id="e_staff" placeholder="Enter Name (e.g. JDCruz)" required></div>
		                            @else
		                            <input class="form-control input-sm align-form" data-role="tagsinput" data-validation="required" data-validation-error-msg-container="#e_staff" type="text" @if($option=='Add') value="" @else value="{{ $event->e_staff }}" @endif name="e_staff" id="e_staff"></div>
		                            @endif
		                            <span class="text-danger"><small><strong id="e_keywords" class="align-text"> {{ $errors->first('e_keywords') }}</strong></small></span>
		                        </div>		                        
								<div class="form-group {{ ($errors->has('e_venue') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Event Venue: <span class="text-danger">*</span></label>
									<div class="col-md-8">
									@if($option != 'View')
									{!! Form::text('e_venue', null, ['class' => 'form-control input-sm align-form', 'id' => 'venue', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#e_venue', $option == 'View' ? 'readonly' : '']) !!}</div>
									@else
									{!! Form::text('e_venue', null, ['class' => 'form-control input-sm align-form', 'id' => 'venue', $option == 'View' ? 'readonly' : '']) !!}</div>
									@endif							
									<span class="text-danger"><small><strong id="e_venue" class="align-text"> {{ $errors->first('e_venue') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('e_online') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Will The Meeting Be Online: <span class="text-danger">*</span></label>
									<div class="col-md-8">
										@if($option == 'Add' || $option == 'Edit')
										<div class="radio-inline"><label>{!! Form::radio('e_online', '1', ['required']) !!} Yes.</label></div>
										<br>
										<div class="radio-inline"><label>{!! Form::radio('e_online', '0', true) !!} No. The meeting will NOT be online.</label></div>
										@else
										@if($event->e_online == 1)
										{!! Form::text('e_online', 'Yes.', ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}
										@else
										{!! Form::text('e_online', 'No. The meeting will NOT be online.', ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}
										@endif	
										@endif
									</div>
								</div>
								@if($option == 'Add' || $option == 'Edit')
								<div class="form-group {{ ($errors->has('e_zoom') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Will You Be Requesting For Zoom Usage?:</label>
									<div class="col-md-8">
										<div class="radio-inline"><label>{!! Form::radio('e_zoom', '1') !!} Yes. I will need to have an access on the Zoom Pro Account.</label></div>
										<br>
										<div class="radio-inline"><label>{!! Form::radio('e_zoom', '0', true) !!} No. Another party will host the online meeting./It will take place in another platform.</label></div>
									</div>
								</div>
								@else
								@if($event->e_online == 1)
								<div class="form-group {{ ($errors->has('e_zoom') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Will You Be Requesting For Zoom Usage?:</label>
									<div class="col-md-8">
										@if($event->e_zoom == 1)
										{!! Form::text('e_zoom', 'Yes. I will need to have an access on the Zoom Pro Account', ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}
										@else
										{!! Form::text('e_zoom', 'No. Another party will host the online meeting./It will take place in another platform', ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}
										@endif	
									</div>
								</div>
								@endif
								@endif

								@if($option == 'Add')
								<div class="form-group">
									<label class="col-md-2 control-label">Online Meeting Link (if online and you're NOT the host):</label>
									<div class="col-md-8">{!! Form::textarea('e_zoom_link', null, ['class' => 'form-control input-sm align-form']) !!}</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Online Meeting ID (if online and you're NOT the host):</label>
									<div class="col-md-8">{!! Form::text('e_zoom_mtgid', null, ['class' => 'form-control input-sm align-form']) !!}</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Online Password:</label>
									<div class="col-md-8">{!! Form::text('e_zoom_pw', null, ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}</div>
								</div>
								@elseif($option == 'Edit')
								@if($event->e_zoom == 0)
								<div class="form-group">
									<label class="col-md-2 control-label">Online Meeting Link (if online and you're NOT the host):</label>
									<div class="col-md-8">{!! Form::textarea('e_zoom_link', null, ['class' => 'form-control input-sm align-form']) !!}</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Online Meeting ID (if online and you're NOT the host):</label>
									<div class="col-md-8">{!! Form::text('e_zoom_mtgid', null, ['class' => 'form-control input-sm align-form']) !!}</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Online Password:</label>
									<div class="col-md-8">{!! Form::text('e_zoom_pw', null, ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}</div>
								</div>
								@endif
								@endif

								@if($option != 'Add')
								<?php $attend_checker = App\Models\EventSeen::where('e_id', $event->e_id)->where('es_invited', 1)->where('u_id', Auth::user()->u_id)->count(); ?>
								@if($attend_checker == 1)
								<div class="form-group">
									<label class="col-md-2 control-label">Desired Password for Zoom Meeting (if online):</label>
									<div class="col-md-8">{!! Form::text('e_zoom_pw', null, ['class' => 'form-control input-sm align-form', 'disabled']) !!}</div>
								</div>
								@endif
								@endif

								@if($option == 'Edit')
									<div class="form-group">
										<div class="col-md-3 col-md-offset-2">								
											{!! Form::button('<span class="fa fa-save"></span> Save', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
											<a href="{{ url('events') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Cancel</a>														
										</div>
									</div>
								@endif
							</div>
						</div>
					</div>
				</div>

				@if($option == 'View')
				@if($event->e_online == 1 && $event->e_zoom == 1)
				<?php $invite_check = App\Models\EventSeen::where('e_id', $id)->where('u_id', Auth::user()->u_id)->where('es_invited', 1)->count(); ?>
				@if(Auth::user()->u_id == $event->u_id || $invite_check > 0 || Auth::user()->u_zoom_mgr == 1)
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Online Room Details</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								@if($event->e_zoom_reason != NULL)
								<div class="form-group">
									<label class="col-md-2 control-label">Reason For Disapproving:</label>
									<div class="col-md-8">{!! Form::text('e_zoom_reason', null, ['class' => 'form-control input-sm align-form', 'disabled']) !!}</div>
								</div>
								@else								
								<div class="form-group">
									<label class="col-md-2 control-label">Online Meeting URL:</label>
									<div class="col-md-8">{!! Form::textarea('e_zoom_link', null, ['class' => 'form-control input-sm align-form', 'disabled']) !!}</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Online Meeting ID:</label>
									<div class="col-md-8">{!! Form::text('e_zoom_mtgid', null, ['class' => 'form-control input-sm align-form', 'disabled']) !!}</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Online Meeting Password:</label>
									<div class="col-md-8">{!! Form::text('e_zoom_pw', null, ['class' => 'form-control input-sm align-form', 'disabled']) !!}</div>
								</div>
								@endif
								<div class="form-group">
									<label class="col-md-2 control-label">Date Reviewed:</label>
									<div class="col-md-8">{!! Form::text('e_zoom_date', null, ['class' => 'form-control input-sm align-form', 'disabled']) !!}</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				@endif
				@if($event->e_zoom == 1)
				@if(Auth::user()->u_id == $event->u_id || Auth::user()->u_zoom_mgr == 1)
				<?php
					$zoom_email = App\Models\Zoom::where('zs_id', $event->zs_id)->value('zs_email'); 
					$zoom_pw 	= App\Models\Zoom::where('zs_id', $event->zs_id)->value('zs_password');
				?>
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Zoom Account Settings</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="col-md-2 control-label">E-Mail Address:</label>
									<div class="col-md-8">{!! Form::text('zs_email', $zoom_email, ['class' => 'form-control input-sm align-form', 'disabled']) !!}</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Password:</label>
									<div class="col-md-8">{!! Form::text('zs_password', $zoom_pw, ['class' => 'form-control input-sm align-form', 'disabled']) !!}</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				@endif
				@endif
				@endif
				@endif

				<!-- ATTACHMENT AND FILE DETAILS -->					
				@if($option == 'Add')
				<div class="panel panel-default panel-plain">		
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Attachment Details</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">						
								<div class="form-group {{ ($errors->has('ea_file') ? ' has-error ' : '') }} has-feedback">
		                            <label class="col-md-2 control-label">Upload Files:</label>
									<div class="col-md-8">{!! Form::file('ea_file[]', ['class' => 'align-form', 'multiple'=>true]) !!}</div>
									<span class="text-danger"><small><strong id="ea_file" class="align-text"> {{ $errors->first('ea_file') }}</strong></small></span>
		                        </div>
							</div>
						</div>
					</div>
				</div>
				@else
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>File Attachments</strong></h1>
					</div>
					<div class="panel-body">			
						<div class="row">
							<label class="col-md-2 control-label">Upload Files:</label>
							<div class="col-md-8">{!! Form::file('ea_file[]', ['class' => 'align-form', 'multiple'=>true]) !!}</div>
							<span class="text-danger"><small><strong id="ea_file" class="align-text"> {{ $errors->first('ea_file') }}</strong></small></span>
							<br>
							<div class="col-md-12">						
								@if($files->isEmpty())
									<div class="panel-body">
										<h5><strong><i class="fa fa-info-circle"></i> No file attachment(s) found.</strong></h5>
									</div>														
								@else
									<table class="table">
										<thead>
											<tr>
												<th>Filename</th>
												<th>Date Attached</th>
												@if($event->u_id == Auth::user()->u_id)
												<th></th>
												@endif
											</tr>
										</thead>
										<tbody>
											@foreach($files as $file)
											<tr>
												<td><a href="{{ URL::to($file->ea_file) }}" target="_blank">{{ basename($file->ea_file) }}</a></td>
												<td>{{ date('F d, Y h:i A', strtotime($file->created_at)) }}</td>
												<td class="text-right">											
													@if($event->u_id == Auth::user()->u_id)
													<a href="{{ url('events/view/delete-file/'.$file->ea_id) }}" class="btn btn-danger btn-xs" title="Remove"><span class="fa fa-trash"></span></a>
													@endif
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>
									@if($files->render())
										<div class="panel-footer text-center">{!! $files->render() !!}</div>
									@endif
								@endif
							</div>
						</div>
					</div>
				</div>
				@endif

				<!-- ATTENDEES -->
				@if($option == 'View' || $option == 'Edit')
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
												@if($event->e_confirm == 1)
												<th>Will Be Going</th>
												@endif
												<th></th>
											</tr>
										</thead>
										<tbody>
											@foreach($attendees as $attendee)
											<tr>
												<td>{{ $attendee->seen->u_lname }}, {{ $attendee->seen->u_fname }}</td>
												<td>{{ date('F d, Y h:i A', strtotime($attendee->created_at)) }}</td>
												@if($event->e_confirm == 1)
												<td>
													@if($attendee->es_confirmed == 1)
													<font color="green"><i class="fa fa-check-circle"></i></font>
													@elseif($attendee->es_confirmed == 0)
														<font color="red"><i class="fa fa-times-circle"></i></font><br>
														<i>Reason: {{ $attendee->es_reason}}</i>
													@elseif($attendee->es_confirmed == 99)
														<i>No confirmation yet.</i>
													@endif
												</td>
												@endif
												<td class="text-right">											
													@if(Auth::user()->u_id == $event->u_id)
													<a href="{{ url('events/view/delete-attendee/'.$attendee->es_id) }}" class="btn btn-danger btn-xs" title="Remove"><span class="fa fa-trash"></span></a>
													@endif
												</td>
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
				@endif

				<!-- NOTIFICATION TAGGING -->
				@if($option != 'View')
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Notification Details</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
		                            <label class="col-md-2 control-label">Notification Mode:</label>
									<div class="col-md-8">
										<select name="tagMode" id="tagMode" class="form-control input-sm align-form">
		                            		<option value="0">Individual</option>
		                            		<option value="1">Group</option>
		                            		<option value="2">Tag All Employees</option>
		                            	</select>
									</div>
		                        </div>
							</div>
							<hr>							
							<div class="col-md-12" id="tag_individual">
								<div class="form-group">
		                            <label class="col-md-2 control-label">Tag Employees:</label>
									<div class="col-md-8">								
										<select data-placeholder="Tag Individually" class="chosen-select form-control input-sm" multiple name="individualTag[]" id="individualTag">
		                        			<option value=""></option>
		                                	@foreach($members as $member)
		                                		<option value="<?php echo $member['u_id']; ?>"><?php echo ucfirst($member['u_lname']).", ".ucfirst($member['u_fname']); ?></option>
		                                	@endforeach
		                                </select>
									</div>
		                        </div>
							</div>
							<div class="col-md-12" id="tag_group">
								<div class="form-group">
		                            <label class="col-md-2 control-label">Tag Employees:</label>
									<div class="col-md-8">								
										<select data-placeholder="Tag By Group" class="chosen-select form-control" multiple name="groupTag[]" id="groupTag">
		                        			<option value=""></option>
		                                	@foreach($groups as $group)
		                                		<option value="<?php echo $group['group_id']; ?>"><?php echo $group['group_name']; ?></option>
		                                	@endforeach
		                                </select>
									</div>
		                        </div>
							</div>
							<hr><br>
							<div class="form-group">
								<label class="col-md-2 control-label">Needs Confirmation:</label>
								<div class="col-md-8">
								@if($option == 'Add')
									<div class="radio-inline"><label>{!! Form::radio('e_confirm', '1', ['required']) !!} Yes</label></div>
									<div class="radio-inline"><label>{!! Form::radio('e_confirm', '0', true) !!} No</label></div>
								@elseif($option == 'Edit')
									<div class="radio-inline">
	                                    <label>	                                    	
	                                    	<input <?php if ($event->e_confirm == 1) echo ' checked="checked" '; ?> type="radio" name="e_confirm" id="e_confirm1" value="1" /> Yes	                                    	
	                                    </label>
	                                </div>
	                                <div class="radio-inline">
	                                    <label>	                                    	
	                                    	<input <?php if ($event->e_confirm == 0) echo ' checked="checked" '; ?> type="radio" name="e_confirm" id="e_confirm2" value="0" /> No
	                                    </label>
	                                </div>
								@else
									@if($event->e_confirm == 1)
									{!! Form::text('e_confirm', 'Yes', ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}
									@else
									{!! Form::text('e_confirm', 'No', ['class' => 'form-control input-sm align-form', $option == 'View' ? 'readonly' : '']) !!}
									@endif							
								@endif
								</div>
							</div>
							<br>
							<div class="col-md-12">
								<div class="form-group">
									<div class="col-md-3 col-md-offset-2">
										@if($option == 'Add')
										{!! Form::button('<span class="fa fa-save"></span> Save', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
										<a href="{{ url('events') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Cancel</a>
										@else
										{!! Form::button('<span class="fa fa-bell"></span> Notify Other Employees', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
										<a href="{{ url('events') }}" class="btn btn-danger btn-sm"><span class="fa fa-undo"></span> Back To Events</a>
										@endif
									</div>
								</div>
							</div>
						</div>						
					</div>				
				</div>
				@endif

				<!-- ATTENDANCE CONFIRMATION -->
				@if($option == 'View')
				<?php $checker = App\Models\EventSeen::where('es_invited', 1)->where('e_id', $id)->where('u_id', Auth::user()->u_id)->value('es_confirmed'); ?>
				@if($checker == 99)
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Attendance Confirmation</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="form-group">
								<label class="col-md-2 control-label">Will Attend:</label>
								<div class="col-md-8">
									<div class="radio-inline"><label>{!! Form::radio('es_confirmed', '1', ['required']) !!} Yes, I will attend.</label></div>
									<br>
									<div class="radio-inline"><label>{!! Form::radio('es_confirmed', '0', true) !!} No, will not be able to attend. <i><font color="red" size="2">State reason below...</font></i></label></div>	
									<br>
									{!! Form::textarea('es_reason', NULL, ['class' => 'form-control input-sm', 'size' => '10x3', 'placeholder'=>'State reason...']) !!}
									<br>
									<div class="radio-inline"><label>{!! Form::radio('es_confirmed', '2') !!} No, will send a representative. <i><font color="red" size="2">Tag representative...</font></i></label></div>
									<br>
									<div class="col-md-8">
										<select data-placeholder="Tag Individually" class="chosen-select form-control input-sm" multiple name="individualTag[]" id="individualTag">
		                        			<option value=""></option>
		                                	@foreach($members as $member)
		                                		<option value="<?php echo $member['u_id']; ?>"><?php echo ucfirst($member['u_lname']).", ".ucfirst($member['u_fname']); ?></option>
		                                	@endforeach
		                                </select>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<div class="col-md-3 col-md-offset-2">										
										{!! Form::button('<span class="fa fa-check"></span> Send Confirmation', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
										<a href="{{ url('events') }}" class="btn btn-danger btn-sm"><span class="fa fa-undo"></span> Back To Events</a>										
									</div>
								</div>
							</div>
						</div>
					</div>				
				</div>
				@endif
				@endif

				
				<!-- COMMENT THREAD -->
				@if($option == 'View')
				<div class="row">
					<div class="col-md-6">
						<div class="panel panel-default panel-plain">
							<div class="panel-heading">
								<h1 class="panel-title"><strong>Comment Thread</strong></h1>
							</div>
							<div class="panel-body">
								<div class="row">
									@if(count($comments) == 0)
									<div class="col-md-12" style="height: 204px;">							
									<h4><center>NO COMMENTS</center></h4>
									@else
									<div class="col-md-12" style="overflow-y: scroll; height: 408px;">
										<ul class="timeline">
									@endif								
										<?php
											$num = 0;
											foreach($comments as $comment) {
												$picture 					= $comment->users->u_picture;

												if($picture != "") {
													$picture_file 			= $comment->users->u_picture;

													if(is_file($picture_file)) {
														$profile_picture 	= $picture;
													} else {
														$profile_picture 	= 'upload/profile/no-user-photo.png';
													}
												} else {
													$profile_picture		= 'upload/profile/no-user-photo.png';
												}

												$num++;

			                                    if(($num % 2) == 0) { 
			                                        $timeline_class         = 'timeline-inverted';
			                                    } else { 
			                                        $timeline_class         = ''; 
			                                    } ?>
			                                    <li class="{{ $timeline_class }}">
			                                    	<div class="timeline-badge">
			                                            <img class="img-circle" src="{{ asset($profile_picture) }}" width="50" height="50">
			                                        </div>
			                                        <div class="timeline-panel">
			                                            <div class="timeline-heading">
			                                                <h4 class="timeline-title">{{ $comment->users->u_fname }}</h4>
			                                                    <p><small class="text-muted"><i class="fa fa-clock-o"></i> {{ date('F d, Y', strtotime($comment->created_at)) }}</small></p>
			                                            </div>
			                                            <div class="timeline-body">
			                                            	<p>
			                                            		@if($comment->comm_reply != NULL)
			                                            		<font color="blue" size="1"><i class="fa fa-reply"></i> {{ App\Models\Comment::where('comm_id', $comment->comm_reply)->value('comm_text') }}</font>
			                                            		<br>
			                                            		@endif
			                                            		@if($comment->comm_tag == 1)
			                                            		<?php
			                                            			$dcomms = App\Models\ECommentSeen::where('comm_id', $comment->comm_id)->get();
			                                            		?>
			                                            		<font color="#454ede"><i>Tagged:</i></font>
			                                            		@foreach($dcomms as $dc)
			                                            		<font color="#454ede">
			                                            			@ {{ App\Models\User::where('u_id', $dc->u_id)->value('u_fname') }}
			                                            		</font>
			                                            		@endforeach
			                                            		<br>
			                                            		@endif
			                                            		{{ $comment->comm_text }}
			                                            	</p>
			                                            	@if(Auth::user()->u_id == $comment->u_id)
			                                            		<a data-toggle="modal" data-id="<?=$comment->comm_id;?>" href="#edit_comment" class="btn btn-warning btn-xs pull-right edit-comment" title="Edit Comment"><i class="fa fa-pencil"></i></a>
			                                            	@endif
			                                            	@if(Auth::user()->u_id != $comment->u_id)
			                                            		<a data-toggle="modal" data-id="<?=$comment->comm_id;?>" href="#reply_comment" class="btn btn-primary btn-xs pull-right reply-comment" title="Reply"><i class="fa fa-reply"></i> Reply</a>
			                                            	@endif
			                                            </div>
			                                        </div>
			                                    </li>
											<?php }
										?>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="panel panel-default panel-plain">
							<div class="panel-heading">
								<h1 class="panel-title"><strong>Leave A Comment</strong></h1>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
				                            <label class="col-md-3 control-label">Tag Employees in Comment:</label>
											<div class="col-md-9">								
												<select data-placeholder="Tag Individually" class="chosen-select form-control input-sm" multiple name="commTag[]" id="commTag">
				                        			<option value=""></option>
				                                	@foreach($ev_tags as $ev_tag)
				                                		<option value="<?php echo $ev_tag['u_id']; ?>"><?php echo ucfirst($ev_tag['u_lname']).", ".ucfirst($ev_tag['u_fname']); ?></option>
				                                	@endforeach
				                                </select>
											</div>
				                        </div>
										{!! Form::textarea('comm_text', NULL, ['class' => 'form-control input-sm', 'size' => '10x7']) !!}
										<br>
										<div class="pull-right">
											{!! Form::button('<span class="fa fa-reply"></span> Submit Comment', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
											<a href="{{ url('events') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>

						@if(count($seens) != 0)
						<div class="panel panel-default panel-plain">
							<div class="panel-body">
								<div class="row">							
									<div class="col-md-12" style="overflow-y: scroll; height: 150px;">
										@foreach($seens as $seen)
											<p><i>Last seen by
												<strong>{{ $seen->seen->u_fname }}</strong> on <strong>{{ date('F d, Y H:i A', strtotime($seen->updated_at)) }}</strong><br>
											</i></p>
										@endforeach
									</div>
								</div>
							</div>
						</div>
						@endif
					</div>
				</div>
				@endif

				<!-- MODAL FOR UPDATING COMMENT -->
				{!! Form::open(['url'=>'event/update_comment', 'class'=>'form']) !!}
				<div class="modal fade" id="editComment" role="dialog">
		            <div class="modal-dialog">
					    <div class="modal-content">
					    	<div class="modal-header">
					        	<button type="button" class="close" data-dismiss="modal">&times;</button>
					        	<h4 class="modal-title">Edit Comment</h4>
					      	</div>
					      	<div class="modal-body">
					      		<div class="row">
									<div class="col-md-12">
										<input class="form-control" type="hidden" value="" name="editID" id="editID">
										{!! Form::textarea('up_comment', NULL, ['class' => 'form-control input-sm', 'size' => '10x7']) !!}
										<br>
										<div class="pull-right">
											{!! Form::button('<span class="fa fa-pencil"></span> Update', ['class' => 'btn btn-warning btn-sm', 'type' => 'submit']) !!}									
										</div>
									</div>
								</div>
					      	</div>
					    </div>
				  	</div>
		        </div>
		        {!! Form::close() !!}
		        <script type="text/javascript">
			        $(document).on("click", ".edit-comment", function() {
			            var mt_id = $(this).data('id');
			            $(".modal-body #showid").text(mt_id);
			            $('#editComment').modal('show');
			            document.getElementById("editID").value = mt_id;
			        });
			    </script>

			    <!-- MODAL FOR REPLYING TO A COMMENT -->		
				<div class="modal fade" id="replyComment" role="dialog">
		            <div class="modal-dialog">
					    <div class="modal-content">
					    	<div class="modal-header">
					        	<button type="button" class="close" data-dismiss="modal">&times;</button>
					        	<h4 class="modal-title">Reply</h4>
					      	</div>
					      	<div class="modal-body">
					      		<div class="row">
									<div class="col-md-12">
										<input class="form-control" type="hidden" value="" name="repID" id="repID">
										{!! Form::textarea('rep_comment', NULL, ['class' => 'form-control input-sm', 'size' => '10x7']) !!}
										<br>
										<div class="pull-right">
											{!! Form::button('<span class="fa fa-reply"></span> Submit', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}									
										</div>                      	
									</div>
								</div>
					      	</div>
					    </div>
				  	</div>
		        </div>        
		        <script type="text/javascript">
			        $(document).on("click", ".reply-comment", function() {
			            var rep_id = $(this).data('id');
			            $(".modal-body #showid").text(rep_id);
			            $('#replyComment').modal('show');
			            document.getElementById("repID").value = rep_id;
			        });
			    </script>
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
				$(document).ready(function() {
					$("select#tagMode option:selected").each(function() {
				    	recipient_selector();
				  	});

					$("select#tagMode").change(function(){
				    	recipient_selector();
				  	});
				});

				function recipient_selector() {
					var recipientType = parseInt($("#tagMode").val());
				  	if( recipientType == 0 ) {
					    $("#sending_option").val('INDIVIDUAL');
					    $("#tag_individual").css('display', 'inline');
					    $("#tag_group").css('display', 'none');			    
					    $("#recipient_all").css('display', 'none');
				  	} else if( recipientType == 1 ) {
					    $("#sending_option").val('GROUP');
					    $("#tag_individual").css('display', 'none');
					    $("#tag_group").css('display', 'inline');
					    $("#recipient_all").css('display', 'none');
				  	} else if( recipientType == 2 )  {
					    $("#sending_option").val('ALL');
					    $("#tag_individual").css('display', 'none');
					    $("#tag_group").css('display', 'none');
					    $("#recipient_all").css('display', 'inline');
				  	}
				}
			</script>

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