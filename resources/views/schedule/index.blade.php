@extends('layouts.main')
@section('content')
	<link href="{{ asset('extensions/events/datepicker.css') }}" rel="stylesheet">
	<link href="{{ asset('extensions/events/bootstrap-timepicker.css') }}" rel="stylesheet">

	<style type="text/css">
		.modal-content{
    		background:#fff;
		}
		
		.modal-dialog{
    		position:absolute;
    		margin-left: -300px;
    		margin-top: 40px;
    		/*border-radius: 7px;
    		background:#6b6a63;
    		margin:30px auto 0;
    		padding:6px;  
    		position:absolute;
    		width:800px;
    		top: 50%;
    		left: 50%;
    		margin-left: -400px;
    		margin-top: -40px;*/
		}
	</style>

	<section id="main-content">
    	<section class="wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="pull-left">
							<h3 class="page-header"><i class="fa fa-calendar"></i><strong> {{ $data['page'] }}</strong><a href="{{ url('rd_schedule/add') }}" class="header-link" title="Add"> [Add Schedule</a> | <a href="{{ url('rd_schedule') }}" class="header-link" title="View">View Calendar]</a></h3>							
						</div>
					</div>
				</div>
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
						<div class="alert alert-info alert-dismissable" id="reminder">                    
		                    <label>LEGENDS: <br/>
		                        <font color="red">&nbsp;<i class="fa fa-exclamation-circle"></i>&nbsp;&nbsp;</font> - haven't viewed the remarks.<br>
		                        @if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3)
		                        <span class="label label-primary"><i class="fa fa-thumbs-o-up"></i></span> - approve scheduled meeting<br>                        
		                        @endif
		                        <span class="label label-danger"><i class="fa fa-ban"></i></span> - cancel meeting<br>
		                        <span class="label label-info"><i class="fa fa-calendar-times-o"></i></span> - reschedule the meeting<br>
		                        <span class="label label-warning"><i class="fa fa-pencil"></i></span> - edit meeting<br>
		                        <span class="label label-success"><i class="fa fa-eye"></i></span> - view details of the meeting
		                    </label>
		                </div> 
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<div class="pull-right">
						{!! Form::open(['url' => 'meetings/search', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off']) !!}
							<div class="form-group">
								<div class="input-group input-group-sm">
									{!! Form::text('search', $search, ['class' => 'form-control', 'placeholder' => 'Search']) !!}
									<span class="input-group-btn">
										{!! Form::button('<span class="fa fa-search"></span> Search', ['class' => 'btn btn-primary btn-xs', 'type' => 'submit', 'title' => 'Search']) !!}
										<a href="{{ url('meetings') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Reset</a>
									</span>
								</div>
							</div>
						{!! Form::close() !!}
					</div>
					@if($meetings->isEmpty())
						<div class="panel-body">
							<h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No existing scheduled meeting(s) found.</strong></h5>
						</div>
					@else
						<table class="table">
							<thead>
								<tr>
									<th></th>
									<th>Date of Meeting</th>
									<th>Purpose of Meeting</th>
									<th>Venue</th>
									<th>Encoded By</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($meetings as $meeting)
								<tr>
									<td>
									<center>
									@if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3)
										<?php $stat = App\Models\Meeting::where('m_id', $meeting->m_id)->value('m_stat'); ?>
										@if($stat == 0)
										<font color="red"><i class="fa fa-exclamation-circle"></i></font>
										@endif								
									@else
										<?php $stat = App\Models\Participant::where('m_id', $meeting->m_id)->where('u_id', Auth::user()->u_id)->value('p_seen'); ?>
										@if($stat == 0)
										<font color="red"><i class="fa fa-exclamation-circle"></i></font>
										@endif
									@endif
									</center>
									</td>
									<td>
										<?php $start = date('F d, Y', strtotime($meeting->m_startdate));
											  $end 	 = date('F d, Y', strtotime($meeting->m_enddate)); ?>
										@if($start != $end)
											{{ date('F d, Y', strtotime($meeting->m_startdate)) }} - {{ date('F d, Y', strtotime($meeting->m_enddate)) }}
											<br>({{ $meeting->m_starttime }} to {{ $meeting->m_endtime}})
										@else
											{{ date('F d, Y', strtotime($meeting->m_startdate)) }} <br>({{ $meeting->m_starttime }} to {{ $meeting->m_endtime}})
										@endif
									</td>
									<td>{{ $meeting->m_purpose }}</td>
									<td>{{ $meeting->m_destination }}</td>
									<td>{{ App\Models\User::where('u_id', $meeting->m_encodedby)->value('u_fname') }} {{ App\Models\User::where('u_id', $meeting->m_encodedby)->value('u_lname') }}</td>									
									<td><center>
										@if($meeting->m_status == "Approved")
											<span class="label label-success"><i class="fa fa-check-circle"></i> APPROVED</span></h5>
										@elseif($meeting->m_status == "Pending")
											<span class="label label-warning"><i class="fa fa-ban"></i> PENDING</span>
											@if($meeting->m_tstartdate != "0000-00-00")
											<br><i>Request for Re-schedule. Please <a href="{{ url('rd_schedule/view/'.$meeting->m_id) }}" title="View"><strong>VIEW</strong></a> details.</i>
											@endif
										@elseif($meeting->m_status == "Canceled")
											<span class="label label-danger"><i class="fa fa-times-circle"></i> CANCELED</span>
										@elseif($meeting->m_status == "Postponed")
											<span class="label label-primary"><i class="fa fa-calendar-times-o"></i> RE-SCHEDULED</span>
										@endif
										{!! Form::hidden('status', $meeting->m_status) !!}
										</center>
									</td>
									<td class="text-right">
										@if($meeting->m_status == "Pending")
										@if(Auth::user()->ug_id == 3 || Auth::user()->ug_id == 1)
										<a href="{{ url('rd_schedule/approve/'.$meeting->m_id) }}" class="btn btn-primary btn-xs" title="Approve"><span class="fa fa-check"></span></a>
										@endif
										@endif
										@if($meeting->m_encodedby == Auth::user()->u_id || Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3)
										@if(date('Y-m-d') < $meeting->m_startdate)
										@if($meeting->m_status != "Canceled")
										<a data-toggle="modal" data-id="<?=$meeting->m_id;?>" href="#cancel_schedule" class="btn btn-danger btn-xs cancel-schedule" title="Cancel Schedule"><span class="fa fa-ban"></span></a>
										<a data-toggle="modal" data-id="<?=$meeting->m_id;?>" href="#re_sched" class="btn btn-info btn-xs re-schedule" title="Re-schedule Meeting"><span class="fa fa-calendar-times-o"></span></a>
										@endif
										@endif
										@endif
										@if($meeting->m_encodedby == Auth::user()->u_id && $meeting->m_status == "Pending")
										<a href="{{ url('rd_schedule/edit/'.$meeting->m_id) }}" class="btn btn-warning btn-xs" title="Edit"><span class="fa fa-pencil-square-o"></span></a>								
										@endif
										<a href="{{ url('rd_schedule/view/'.$meeting->m_id) }}" class="btn btn-success btn-xs" title="View"><span class="fa fa-eye"></span></a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						@if($meetings->render())
							<div class="panel-footer text-center">@if($search == '') {!! $meetings->render() !!} @else {!! $meetings->appends(Request::only('search'))->render() !!} @endif </div>
						@endif
					@endif
				</div>

				<!-- MODAL FOR CANCELATION OF MEETING -->
				{!! Form::open(['url'=>'rd_schedule/cancel', 'class'=>'form']) !!}
				<div class="modal fade" id="cancel_meeting" role="dialog">
		            <div class="modal-dialog">									    
					    <div class="modal-content">
					    	<div class="modal-header">
					        	<button type="button" class="close" data-dismiss="modal">&times;</button>
					        	<h4 class="modal-title">Cancel Meeting</h4>
					      	</div>
					      	<div class="modal-body">
					      		<input class="form-control" type="hidden" value="" name="cancelmeetingID" id="cancelmeetingID">
					      		<div class="form-group">
		                        	<label>State Purpose of Cancelation <span class="required">*</span></label>
									<textarea class="form-control" rows="5" name="m_reason" required></textarea>
								</div>
								<div class="form-group">
									<button title="Save" type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Save</button>
									<button title="Cancel" type="reset" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i> Cancel</button>
								</div>
					      	</div>
					    </div>
				  	</div>
		        </div>
		        {!! Form::close() !!}

				<!-- MODAL FOR POSTPONING A MEETING -->
				{!! Form::open(['url'=>'rd_schedule/postpone', 'class'=>'form']) !!}
				<div class="modal fade" id="postpone_meeting" role="dialog">
		            <div class="modal-dialog">
					    <div class="modal-content">
					    	<div class="modal-header">
					        	<button type="button" class="close" data-dismiss="modal">&times;</button>
					        	<h4 class="modal-title">Re-schedule Meeting</h4>
					      	</div>
					      	<div class="modal-body">
					      		<input class="form-control" type="hidden" value="" name="postponemeetingID" id="postponemeetingID">
					      		<div class="form-group {{ ($errors->has('m_tstartdate') ? ' has-error ' : '') }} has-feedback">
									<label>Start Date: <span class="text-danger">*</span></label>
									<div>{!! Form::text('m_tstartdate', null, ['class' => 'form-control input-sm', 'id' => 'm_tstartdate', 'maxlength' => '10', 'required', 'placeholder' => 'Start Date', 'readonly']) !!}</div>
									<span class="text-danger"><small><strong id="m_tstartdate" class="align-text"> {{ $errors->first('m_tstartdate') }}</strong></small></span>
								</div>						
								<div class="form-group {{ ($errors->has('m_tstarttime') ? ' has-error ' : '') }} has-feedback">
			                        <label>Start Time: <span class="text-danger">*</span></label>
			                        <div><input id="m_tstarttime" name="m_tstarttime" type="text" class="form-control input-sm align-form" data-validation="required" data-validation-error-msg-container="#m_tstarttime" readonly required placeholder="Start Time"></div>
			                        <span class="text-danger"><small><strong id="m_tstarttime" class="align-text"> {{ $errors->first('m_tstarttime') }}</strong></small></span>
			                    </div>
								<div class="form-group {{ ($errors->has('m_tenddate') ? ' has-error ' : '') }} has-feedback">
									<label>End Date: <span class="text-danger">*</span></label>
									<div>{!! Form::text('m_tenddate', null, ['class' => 'form-control input-sm', 'id' => 'm_tenddate', 'maxlength' => '10', 'required', 'placeholder' => 'End Date', 'readonly']) !!}</div>
									<span class="text-danger"><small><strong id="m_tenddate" class="align-text"> {{ $errors->first('m_tenddate') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('m_tendtime') ? ' has-error ' : '') }} has-feedback">
			                        <label>End Time: <span class="text-danger">*</span></label>
			                        <div><input id="m_tendtime" name="m_tendtime" type="text" class="form-control input-sm align-form" data-validation="required" data-validation-error-msg-container="#m_tendtime" readonly required placeholder="End Time"></div>
			                        <span class="text-danger"><small><strong id="m_tendtime" class="align-text"> {{ $errors->first('m_tendtime') }}</strong></small></span>
			                    </div>
		                        <div class="form-group">
		                        	<label>State Reason For Re-schedule <span class="required">*</span></label>
									<textarea class="form-control" rows="5" name="m_postponed" required></textarea>
								</div>

								<div class="form-group">
									<button title="Save" type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Save</button>
									<button title="Cancel" type="reset" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i> Cancel</button>
								</div>
					      	</div>
					    </div>
				  	</div>
		        </div>
		    </section>
		</section>
        {!! Form::close() !!}

        <script src="{{ asset('extensions/events/bootstrap-datepicker.js') }}"></script>
		<script src="{{ asset('extensions/events/bootstrap-timepicker.js') }}"></script>
		<script type="text/javascript">
			$(document).ready(function() {
	        	$('#m_tstarttime').timepicker();
	        	$('#m_tendtime').timepicker();

				$('#m_tstartdate').datepicker({
				    format: "yyyy-mm-dd"
				});

				$('#m_tenddate').datepicker({
				    format: "yyyy-mm-dd"
				});
			});
		</script>

		<script type="text/javascript">
	        $(document).on("click", ".cancel-schedule", function() {
	            var mt_id = $(this).data('id');
	            $(".modal-body #showid").text(mt_id);
	            $('#cancel_meeting').modal('show');		                                
	            document.getElementById("cancelmeetingID").value = mt_id;                    		                    
	        });
	    </script>

	    <script type="text/javascript">
	        $(document).on("click", ".re-schedule", function() {
	            var pt_id = $(this).data('id');
	            $(".modal-body #showid").text(pt_id);
	            $('#postpone_meeting').modal('show');		                                
	            document.getElementById("postponemeetingID").value = pt_id;                    		                    
	        });
	    </script>
	</div>
@stop