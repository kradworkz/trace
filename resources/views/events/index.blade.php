@extends('layouts.main')
@section('content')
	<style type="text/css">
		.modal-content{
    		background:#fff;
		}
		
		.modal-dialog{
    		position:absolute;
    		margin-left: -300px;
    		margin-top: 40px;
		}
	</style>

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
							<h3 class="page-header"><i class="fa fa-calendar"></i><strong>{{ $data['page'] }}</strong> <a href="{{ url('events/add') }}" class="header-link" title="Add">[Add Event | <a href="{{ url('events/calendar') }}" class="header-link" title="Events">Calendar View]</a></h3>
						</div>
						<div class="pull-right">							
							<h3 class="page-header">
								<a title="Print" data-toggle="modal" href="#print_document" class="btn btn-default print-doc"><i class="fa fa-print"></i> Print Monthly Events</a>
							</h3>							
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<div class="pull-right">
						{!! Form::open(['url' => 'events/search', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off']) !!}
							<div class="form-group">
								<div class="input-group input-group-sm">
									{!! Form::text('search', $search, ['class' => 'form-control', 'placeholder' => 'Search Event']) !!}
									<span class="input-group-btn">
										{!! Form::button('<span class="fa fa-search"></span> Search', ['class' => 'btn btn-primary btn-xs', 'type' => 'submit', 'title' => 'Search']) !!}
										<a href="{{ url('events') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Reset</a>
									</span>
								</div>
							</div>
						{!! Form::close() !!}
					</div>
					@if($events->isEmpty())
						<div class="panel-body">
							<h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No existing event(s) found.</strong></h5>
						</div>
					@else
						<table class="table">
							<thead>
								<tr>
									<th>Event Name</th>							
									<th width="10%">Event Type</th>
									<th>Date/Time</th>
									<th>Venue</th>
									<th>Status (for Zoom Requests)</th>
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
									<td>
										<?php $invited = App\Models\EventSeen::where('e_id', $event->e_id)->where('u_id', Auth::user()->u_id)->where('es_invited', 1)->count(); ?>
										@if($invited > 0 || $event->u_id == Auth::user()->u_id)
											@if($event->e_online == 1)
												@if($event->e_zoom == 1)
													@if($event->e_zoom_approved == 1)
														@if($event->e_zoom_link != NULL)
														<a href="{{ url($event->e_zoom_link) }}" title="Meeting URL Link" target="_blank">{{ $event->e_venue }}</a>
														@else
														<i>Didn't provide meeting link.</i>
														@endif
														<br>
														Meeting ID: {{ $event->e_zoom_mtgid }}<br>
														Password: {{ $event->e_zoom_pw }}
													@else
														{{ $event->e_venue }}
													@endif
												@else
												@if($event->e_zoom_link != NULL)
												<a href="{{ url($event->e_zoom_link) }}" title="Meeting URL Link" target="_blank">{{ $event->e_venue }}</a>
												@else
												<i>Didn't provide meeting link.</i>
												@endif
												<br>
												Meeting ID: {{ $event->e_zoom_mtgid }}<br>
												Password: {{ $event->e_zoom_pw }}
												@endif
											@else
											{{ $event->e_venue }}
											@endif
										@else
											{{ $event->e_venue }}
										@endif
									</td>
									<td>
										@if($event->e_online == 1)
											@if($event->e_zoom == 1)
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
											@else
											<i>No office zoom account needed.</i>
											@endif
										@else
											<i>Not an online meeting.</i>
										@endif
									</td>
									<td class="text-right">
										<a href="{{ url('events/view/'.$event->e_id) }}" class="btn btn-info btn-xs" title="View"><span class="fa fa-eye"></span></a>
										@if($event->u_id == Auth::user()->u_id)
										<a href="{{ url('events/edit/'.$event->e_id) }}" class="btn btn-warning btn-xs" title="Edit"><span class="fa fa-pencil-square-o"></span></a>
										<a href="{{ url('events/delete/'.$event->e_id) }}" class="btn btn-danger btn-xs" title="Delete"><span class="fa fa-trash-o"></span></a>
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

					<!-- MODAL FOR PRINTING REPORT -->
					{!! Form::open(['url'=>'events/print_document', 'class'=>'form', 'target'=>'_blank']) !!}
					<div class="modal fade" id="printDocument" role="dialog">
			            <div class="modal-dialog">
						    <div class="modal-content">
						    	<div class="modal-header">
						        	<button type="button" class="close" data-dismiss="modal">&times;</button>
						        	<h4 class="modal-title">Select Month and Year</h4>
						      	</div>
						      	<div class="modal-body">
						      		<div class="row">
										<div class="col-md-12">
											<?php
												$start_date = App\Models\Event::orderBy('e_start_date', 'asc')->value('e_start_date');
												$end_date 	= App\Models\Event::orderBy('e_end_date', 'desc')->pluck('e_end_date');
												$first 		= date('Y', strtotime($start_date));
												$last 		= date('Y', strtotime($end_date));

												$years      = array_combine(range(date("Y"), $first), range(date("Y"), $first));
											?>
											Year:
											{!! Form::select('year', $years, $last, ['class' => 'chosen-select form-control input-sm']) !!}
											<br>
											Month:
											{!! Form::select('month', [
										    	'01' => 'January',
												'02' => 'February',
											   	'03' => 'March',
											   	'04' => 'April',
											   	'05' => 'May',
											   	'06' => 'June',
											   	'07' => 'July',
											   	'08' => 'August',
											   	'09' => 'September',
											   	'10' => 'October',
											   	'11' => 'November',											   	
											   	'12' => 'December'],
											   	null, ['class' => 'form-control input-sm']) 
											!!}
											<br>
											<div class="pull-right">												
												{!! Form::button('<span class="fa fa-print"></span> Print', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit', 'target' => '_blank']) !!}
											</div>
										</div>
									</div>
						      	</div>
						    </div>
					  	</div>
			        </div>
			        {!! Form::close() !!}
			        <script type="text/javascript">
				        $(document).on("click", ".print-doc", function() {
				            $('#printDocument').modal('show');
				        });
				    </script>
				</div>
			</div>
		</section>
	</section>
@stop