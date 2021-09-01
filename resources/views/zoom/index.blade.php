@extends('layouts.main')
@section('content')
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
							<h3 class="page-header"><i class="fa fa-video-camera"></i><strong>{{ $data['page'] }}</strong>  <a href="{{ url('events/add') }}" class="header-link" title="Add">[Add Event]</a></h3>
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<div class="pull-right">
					</div>
					@if($events->isEmpty())
						<div class="panel-body">
							<h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No existing approval(s) found.</strong></h5>
						</div>
					@else
						<table class="table">
							<thead>
								<tr>
									<th>Event Name</th>							
									<th width="10%">Event Type</th>
									<th>Date/Time</th>
									<th>Venue</th>
									<th>No. of Attendees</th>
									<th>Requestor</th>
									<th>Status</th>
									<th>Remarks</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($events as $event)
								<tr>
									<td>
										<?php $invitation = App\Models\EventSeen::where('e_id', $event->e_id)->where('u_id', Auth::user()->u_id)->where('es_invited', 1)->where('es_seen', 0)->get(); ?>
										@if(count($invitation) > 0)
											<font color="red"><i class="fa fa-eye-slash"></i></font>
										@endif
										{{ $event->e_name }}
									</td>							
									<td>{{ $event->e_type }}</td>
									<td>
										<?php $start = date('F d, Y', strtotime($event->e_start_date));
											  $end 	 = date('F d, Y', strtotime($event->e_end_date)); ?>
										@if($start != $end)
											{{ date('F d, Y', strtotime($event->e_start_date)) }} - {{ date('F d, Y', strtotime($event->e_end_date)) }}
											<br>({{ $event->e_start_time }} to {{ $event->e_end_time}})
										@else
											{{ date('F d, Y', strtotime($event->e_start_date)) }} <br>({{ $event->e_start_time }} to {{ $event->e_end_time}})
										@endif
									</td>
									<td>{{ $event->e_venue }}</td>
									<td>{{ App\Models\EventSeen::where('e_id', $event->e_id)->where('es_invited', 1)->count() }}
									<td>{{ $event->user->u_fname }} {{ $event->user->u_lname }}</td>
									<td>
										@if($event->e_zoom_approved == 0)
											@if($event->e_zoom_date == NULL)
											<font color="orange"><i class="fa fa-exclamation-circle" title="Pending"></i></font>
											@else
											<font color="red"><i class="fa fa-times-circle" title="Disapproved"></i></font><br>
											<font size="2"><i>Reason: {{ $event->e_zoom_reason }}</i></font>	
											@endif
										@elseif($event->e_zoom_approved == 1)
											<font color="green"><i class="fa fa-check-circle" title="Approved"></i></font><br>
											<font size="2"><i>Date Approved: {{ $event->e_zoom_date }}</i></font>										
										@endif
									</td>
									<td>
										{{ App\Models\Zoom::where('zs_id', $event->zs_id)->value('zs_remarks') }}
									</td>
									<td class="text-right">
										<a href="{{ url('events/view/'.$event->e_id) }}" class="btn btn-info btn-xs" target="_blank" title="View"><span class="fa fa-eye"></span></a>
										@if(Auth::user()->u_zoom_mgr == 1)
										<a href="{{ url('zoom_schedules/edit/'.$event->e_id) }}" class="btn btn-warning btn-xs" title="Edit"><span class="fa fa-pencil-square-o"></span></a>
										@endif								
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						@if($events->render())
							<div class="text-center">@if($search == '') {!! $events->render() !!} @else {!! $events->appends(Request::only('search'))->render() !!} @endif </div>
						@endif
					@endif
				</div>
			</div>
		</section>
	</section>
@stop