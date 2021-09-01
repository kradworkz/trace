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
						<div class="pull-left">
							<h3 class="page-header"><i class="fa fa-folder-open"></i><strong> {{ $data['page'] }}</strong>					
							</h3>					
						</div>
						<div class="pull-right">							
							<h3 class="page-header">
								@if(Auth::user()->ug_id == 3)
								<a title="Print" href="{{ url('report/print') }}" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print Report</a>
								@endif
							</h3>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-info alert-dismissable" id="reminder">
		                    <label class="label-alert">COLOR LEGENDS: <br/>
		                    	<font color="green">Action done</font><br>
		                    	<font color="orange">Seen the document but not completed</font><br>
		                        <font color="red">Haven't viewed the document</font><br>
		                    </label>
		                </div>
					</div>
				</div>

				<div class="panel panel-default panel-plain">
					@if($documents->isEmpty())
						<div class="panel-body">
							<h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No pending document(s) to report.</strong></h5>
						</div>
					@else
						<table class="table">
							<thead>
								<tr>
									<th>Subject</th>
									<th>Document Type</th>
									<th>Routing Slip</th>
									<th>Date/Time Routed</th>
									@if(Request::segment(2) != 'search')
									<th>Notified</th>									
									<th>Tagged Persons</th>
									@endif
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($documents as $document)
								<?php
									$total_routed 	= App\Models\DocumentRouting::where('d_id', $document->d_id)->count();
									$finished 		= App\Models\DocumentRouting::where('d_id', $document->d_id)->where('dr_completed', 1)->count();
									$pen 			= App\Models\DocumentRouting::where('d_id', $document->d_id)->where('dr_seen', 1)->where('dr_completed', 0)->count();
									$unopened 		= App\Models\DocumentRouting::where('d_id', $document->d_id)->where('dr_seen', 0)->count();
									$employees 		= App\Models\DocumentRouting::where('d_id', $document->d_id)->get();
								?>
								<tr>
									<td>
										{{ $document->d_subject }}
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
									</td>
									<td>{{ App\Models\DocumentType::where('dt_id', $document->dt_id)->value('dt_type') }}</td>
									<td>{{ $document->d_routingslip }}</td>
									<td>
										@if($document->d_routingthru != 0)
										{{ date('F d, Y h:i A', strtotime($document->d_datetimerouted)) }}
										@else
										<i>Not yet routed.</i>
										@endif
									</td>
									@if(Request::segment(2) != 'search')
									<td>
										<font color="green">{{ $finished }} / {{ $total_routed }}</font><br>
										<font color="orange">{{ $pen }} / {{ $total_routed }}</font><br>
										<font color="red">{{ $unopened }} / {{ $total_routed }}</font>
									</td>
									<td>
										@foreach($employees as $employee)
											@if($employee->dr_seen == 1 && $employee->dr_completed == 1)
											<font color="green">
											@elseif($employee->dr_seen == 1 && $employee->dr_completed == 0)
											<font color="orange">
											@elseif($employee->dr_seen == 0 && $employee->dr_seen == 0)
											<font color="red">
											@endif
											{{ $employee->seen->u_fname }} {{ $employee->seen->u_lname }}<br>
											</font>
										@endforeach
									</td>
									@endif
									<td class="text-right">
										<a href="{{ url('incoming/view/'.$document->d_id) }}" class="btn btn-success btn-xs" title="View"><span class="fa fa-eye"></span></a>																		
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
					{!! Form::open(['url'=>'report/print_document', 'class'=>'form', 'target'=>'_blank']) !!}
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
			        <!-- END OF PRINTING MODAL -->

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