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

	<?php
		$ug_id 		= Auth::user()->ug_id;
		$ur_id 		= App\Models\UserRight::where('ur_name', $data['page'])->value('ur_id');
    	$ugr_add 	= App\Models\UserGroupRight::where('ug_id', $ug_id)->where('ur_id', $ur_id)->value('ugr_add');
    	$ugr_edit 	= App\Models\UserGroupRight::where('ug_id', $ug_id)->where('ur_id', $ur_id)->value('ugr_edit');
    	$ugr_del 	= App\Models\UserGroupRight::where('ug_id', $ug_id)->where('ur_id', $ur_id)->value('ugr_delete');
	?>

	<!--main content start-->
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
							<h3 class="page-header"><i class="fa fa-files-o"></i><strong> {{ $data['page'] }}</strong>
								@if(Auth::user()->ug_id == 3 || Auth::user()->ug_id == 5)
								<a href="{{ url('incoming/add') }}" class="header-link" title="Add"> [Add Incoming Document]</a>
								@endif						
							</h3>					
						</div>
						<div class="pull-right">
							@if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3)
							<h3 class="page-header">
								<a title="Print" data-toggle="modal" href="#print_document" class="btn btn-default print-doc"><i class="fa fa-print"></i> Print Document Report</a>
							</h3>
							@endif
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-info alert-dismissable" id="reminder">                    
		                    <label class="label-alert">LEGENDS: <br/>
		                        <font color="red"><i class="fa fa-exclamation-circle"></i></font> - beyond the deadline of posting the outgoing document or doing the action.<br>
		                        @if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3)
		                        	<font color="red"><i class="fa fa-thumb-tack"></i></font> - haven't routed the document<br>
		                        @else
		                            <font color="red"><i class="fa fa-eye-slash"></i></font> - haven't viewed the document<br>
		                        @endif
		                        <font color="green"><i class="fa fa-check-circle"></i></font> - action done<br>
		                        @if(Auth::user()->ug_id == 2)
		                        <font color="blue"><i class="fa fa-thumb-tack"></i></font> - document was routed thru your account<br>
		                        @endif
		                    </label>
		                </div> 
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<div class="pull-right">
						@if(Request::segment(1) == 'incoming')
						{!! Form::open(['url' => 'incoming/search', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off']) !!}
						@elseif(Request::segment(1) == 'unrouted')
						{!! Form::open(['url' => 'unrouted/search', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off']) !!}
						@elseif(Request::segment(1) == 'incoming_routed')
						{!! Form::open(['url' => 'incoming_routed/search', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off']) !!}
						@elseif(Request::segment(1) == 'my_incoming')
						{!! Form::open(['url' => 'my_incoming/search', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off']) !!}
						@endif
							<div class="form-group">
								<div class="input-group input-group-sm">
									{!! Form::text('search', $search, ['class' => 'form-control', 'placeholder' => 'Search Document', 'id' => 'search']) !!}
									<span class="input-group-btn">
										{!! Form::button('<span class="fa fa-search"></span> Search', ['class' => 'btn btn-primary btn-xs', 'type' => 'submit', 'title' => 'Search']) !!}
										@if(Request::segment(1) == 'incoming')
										<a href="{{ url('incoming') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Reset</a>
										@elseif(Request::segment(1) == 'incoming_routed')
										<a href="{{ url('incoming_routed') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Reset</a>
										@elseif(Request::segment(1) == 'my_incoming')
										<a href="{{ url('my_incoming') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Reset</a>
										@elseif(Request::segment(1) == 'unrouted')
										<a href="{{ url('unrouted') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Reset</a>
										@endif
									</span>
								</div>
							</div>
						{!! Form::close() !!}
					</div>
					@if($documents->isEmpty())
						<div class="panel-body">
							<h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No incoming document(s) found.</strong></h5>
						</div>
					@else
						<table class="table">
							<thead>
								<tr>
									<th>Subject</th>
									<th>Document Type</th>
									<th>Routing Slip</th>
									<th>Date/Time Encoded</th>
									<th>Date/Time Routed</th>
									@if(Request::segment(1) == 'incoming')
									@if(Auth::user()->group_id != 1)
									<th>Date/Time Tagged</th>
									@endif
									@endif
									<th>Keywords</th>
									<th>Comments</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($documents as $document)
								<tr>
									<td>
										@if($document->d_routingthru == 0)
											<font color="red"><i class="fa fa-thumb-tack"></i></font>
											@if(Auth::user()->ug_id == 1)
											<a href="{{ URL::to('incoming/route/'.$document->d_id) }}" title="Route Document"><strong>{{ $document->d_subject }}</strong></a>
											@else
											{{ $document->d_subject }}
											@endif
										@else						
											<?php 
												$seen_count = App\Models\DocumentRouting::where('d_id', $document->d_id)->where('u_id', Auth::user()->u_id)->count();
												$seen_check = App\Models\DocumentRouting::where('d_id', $document->d_id)->where('u_id', Auth::user()->u_id)->value('dr_seen');
												$completed 	= App\Models\DocumentRouting::where('d_id', $document->d_id)->where('u_id', Auth::user()->u_id)->value('dr_completed');
											?>																
											@if($seen_count > 0)
												@if($seen_check == 0)
												<font color="red"><i class="fa fa-eye-slash"></i></font>
												@endif
											@endif

											@if(Auth::user()->ug_id == 2)
											@if($document->d_routingthru == Auth::user()->u_id)
											<font color="blue"><i class="fa fa-thumb-tack"></i></font>
											@endif
											@endif
											{{ $document->d_subject }}

											@if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3)
												@if($document->d_istrack == 1 && $document->d_iscompleted == 0)													
													<?php
				                                        $dtr 		= date('Y-m-d', strtotime($document->d_datetimerouted));
														$day 		= App\Models\Setting::orderBy('s_id', 'desc')->value('s_pending_days');
														$newDate	= date('Y-m-d', strtotime($dtr. " + {$day} days"));
														$today 		= date('Y-m-d');
														$difference	= (strtotime($today) - strtotime($newDate));
														$no_of_days = floor($difference/(60*60*24));
				                                        if ( $no_of_days > 0) {
				                                            echo "<font color='red'><i class='fa fa-exclamation-circle'></i> " . $no_of_days . " day/s </font>";
				                                        }
				                                    ?>
				                                @endif
											@else
												
												@if($document->d_istrack == 1 && $completed == 0)
												<?php
			                                        $dtr 		= date('Y-m-d', strtotime($document->d_datetimerouted));
													$day 		= App\Models\Setting::orderBy('s_id', 'desc')->value('s_pending_days');
													$newDate	= date('Y-m-d', strtotime($dtr. " + {$day} days"));
													$today 		= date('Y-m-d');
													$difference	= (strtotime($today) - strtotime($newDate));
													$no_of_days = floor($difference/(60*60*24));
													if(Request::segment(1) != 'incoming_routed') {
														if ( $no_of_days > 0) {
				                                            echo "<font color='red'><i class='fa fa-exclamation-circle'></i> " . $no_of_days . " day/s </font>";
				                                        }	
													}
			                                    ?>
			                                    @endif
											@endif
										@endif
									</td>
									<td>{{ App\Models\DocumentType::where('dt_id', $document->dt_id)->value('dt_type') }}</td>
									<td>{{ $document->d_routingslip }}</td>
									<td>{{ date('F d, Y h:i A', strtotime($document->created_at)) }}</td>
									<td>
										@if($document->d_routingthru != 0)
										{{ date('F d, Y h:i A', strtotime($document->d_datetimerouted)) }}
										@else
										<i>Not yet routed.</i>
										@endif
									</td>
									@if(Request::segment(1) == 'incoming')
									@if(Auth::user()->group_id != 1)
									<td>{{ date('F d, Y h:i A', strtotime(App\Models\DocumentRouting::where('d_id', $document->d_id)->where('u_id', Auth::user()->u_id)->value('created_at'))) }}</td>
									@endif
									@endif
									<td>{{ $document->d_keywords }}</td>
									<td>{{ App\Models\Comment::where('comm_document', 1)->where('comm_reference', $document->d_id)->count() }}</td>
									<td class="text-right">
										@if(Auth::user()->ug_id == 1)
											@if($document->d_routingthru == 0)
											<a href="{{ url('incoming/route/'.$document->d_id) }}" class="btn btn-primary btn-xs" title="Route"><span class="fa fa-thumb-tack"></span></a>
											@endif
										@endif
										@if($document->d_routingthru != 0)
										<a href="{{ url('incoming/view/'.$document->d_id) }}" class="btn btn-success btn-xs" title="View"><span class="fa fa-eye"></span></a>
										@endif
										@if($ugr_edit == 1)											
											<a href="{{ url('incoming/edit/'.$document->d_id) }}" class="btn btn-warning btn-xs" title="Edit"><span class="fa fa-edit"></span></a>
											@if($document->d_routingthru == 0 && $document->d_encoded_by == Auth::user()->u_id)
											<a href="{{ url('incoming/delete/'.$document->d_id) }}" class="btn btn-danger btn-xs" title="Delete"><span class="fa fa-trash"></span></a>
											@endif
										@endif
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						@if($documents->render())
							<div class="text-center">@if($search == '') {!! $documents->render() !!} @else {!! $documents->appends(Request::only('search'))->render() !!} @endif </div>
						@endif
					@endif

					<!-- MODAL FOR PRINTING REPORT -->
					{!! Form::open(['url'=>'incoming/print_document', 'class'=>'form', 'target'=>'_blank']) !!}
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
												$start_date = App\Models\Document::where('d_status', 'Incoming')->where('d_routingthru', '!=', 0)->orderBy('d_datetimerouted', 'asc')->value('d_datetimerouted');
												$end_date 	= App\Models\Document::where('d_status', 'Incoming')->where('d_routingthru', '!=', 0)->orderBy('d_datetimerouted', 'desc')->value('d_datetimerouted');
												$first 		= date('Y', strtotime($start_date));
												$last 		= date('Y', strtotime($end_date));

												$years      = array_combine(range(date("Y"), $first), range(date("Y"), $first));
											?>
											Search For The Whole Year?
											{!! Form::select('wholeYear', [	
										    	'Yes' => 'Yes',
											   	'No'  => 'No'],
											   	null, ['class' => 'form-control input-sm']) 
											!!}
											<br>
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
											Enter Keyword:
											{!! Form::text('reportSearch', $reportSearch, ['class' => 'form-control', 'placeholder' => 'Search Document']) !!}
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