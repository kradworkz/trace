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
					@if($events->isEmpty())
						<div class="panel-body">
							<h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No existing confirmation(s) found.</strong></h5>
						</div>
					@else
						<table class="table">
							<thead>
								<tr>
									<th>Event Name</th>							
									<th width="10%">Event Type</th>
									<th>Date/Time</th>
									<th>Venue</th>							
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($events as $event)
								<tr>
									<td>{{ $event->ev->e_name }}</td>							
									<td>{{ $event->ev->e_type }}</td>
									<td>
										<?php $start = date('F d, Y', strtotime($event->ev->e_start_date));
											  $end 	 = date('F d, Y', strtotime($event->ev->e_end_date)); ?>
										@if($start != $end)
											{{ date('F d, Y', strtotime($event->ev->e_start_date)) }} - {{ date('F d, Y', strtotime($event->ev->e_end_date)) }}
											<br>({{ $event->ev->e_start_time }} to {{ $event->ev->e_end_time}})
										@else
											{{ date('F d, Y', strtotime($event->ev->e_start_date)) }} <br>({{ $event->ev->e_start_time }} to {{ $event->ev->e_end_time}})
										@endif
									</td>
									<td>{{ $event->ev->e_venue }}</td>							
									<td class="text-right">
										<a href="{{ url('events/view/'.$event->e_id) }}" class="btn btn-info btn-xs" title="View"><span class="fa fa-eye"></span></a>							
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