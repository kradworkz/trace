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
							<h3 class="page-header"><i class="fa fa-files-o"></i><strong> {{ $data['page'] }}</strong><a href="{{ url('outgoing/add') }}" class="header-link" title="Add"> [Add Outgoing Document]</a></h3>
						</div>
						<div class="pull-right">
							<h3 class="page-header">
								@if(Auth::user()->ug_id == 3)
								<a title="Print" data-toggle="modal" href="#print_document" class="btn btn-default print-doc"><i class="fa fa-print"></i> Print Document Report</a>
								@else
								<a title="Print" data-toggle="modal" href="#print_document" class="btn btn-default print-doc"><i class="fa fa-print"></i> Print Your Monthly Documents</a>
								@endif
							</h3>							
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-info alert-dismissable" id="reminder">                    
		                    <label class="label-alert">LEGENDS: <br/>
		                       	<font color="red"><i class="fa fa-eye-slash"></i></font> - haven't viewed the document<br>
		                        <font color="green"><i class="fa fa-reply"></i></font> - reply to an incoming document<br>                        
		                    </label>
		                </div> 
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<div class="pull-right">
						@if(Request::segment(1) == 'my_outgoing')
						{!! Form::open(['url' => 'my_outgoing/search', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off']) !!}
						@else
						{!! Form::open(['url' => 'outgoing/search', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off']) !!}
						@endif
							<div class="form-group">
								<div class="input-group input-group-sm">
									{!! Form::text('search', $search, ['class' => 'form-control', 'placeholder' => 'Search Document']) !!}
									<span class="input-group-btn">
										{!! Form::button('<span class="fa fa-search"></span> Search', ['class' => 'btn btn-primary btn-xs', 'type' => 'submit', 'title' => 'Search']) !!}
										@if(Request::segment(1) == 'my_outgoing')
										<a href="{{ url('my_outgoing') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Reset</a>
										@else
										<a href="{{ url('outgoing') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Reset</a>
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
									<th>Date/Time Routed</th>
									<th>Comments</th>
									<th>Keywords</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($documents as $document)
								<tr>
									<td>
										<?php
											$seen_count = App\Models\DocumentRouting::where('d_id', $document->d_id)->where('u_id', Auth::user()->u_id)->count();
											$seen_check = App\Models\DocumentRouting::where('d_id', $document->d_id)->where('u_id', Auth::user()->u_id)->value('dr_seen');
										?>
										@if($seen_count > 0)
											@if($seen_check == 0)
											<font color="red"><i class="fa fa-eye-slash"></i></font>
											@endif
										@endif
										@if($document->d_incomingreference != NULL)
										<font color="green"><i class="fa fa-reply"></i></font>
										@endif
										{{ $document->d_subject }}
									</td>
									<td>{{ App\Models\DocumentType::where('dt_id', $document->dt_id)->value('dt_type') }}</td>
									<td>{{ $document->d_routingslip }}</td>
									<td>{{ date('F d, Y h:i A', strtotime($document->created_at)) }}</td>
									<td>{{ App\Models\Comment::where('comm_document', 1)->where('comm_reference', $document->d_id)->count() }}</td>
									<td>{{ $document->d_keywords }}</td>
									<td class="text-right">
										<a href="{{ url('outgoing/view/'.$document->d_id) }}" class="btn btn-success btn-xs" title="View"><span class="fa fa-eye"></span></a>
										@if($document->d_routingfrom == Auth::user()->u_id)
										<a href="{{ url('outgoing/edit/'.$document->d_id) }}" class="btn btn-warning btn-xs" title="Edit"><span class="fa fa-edit"></span></a>
										@endif
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						@if($documents->render())
							<div class="panel-footer text-center">@if($search == '') {!! $documents->render() !!} @else {!! $documents->appends(Request::only('search'))->render() !!} @endif </div>
						@endif
					@endif

					<!-- MODAL FOR PRINTING REPORT -->
					{!! Form::open(['url'=>'outgoing/print_document', 'class'=>'form', 'target'=>'_blank']) !!}
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
												$start_date = App\Models\Document::where('d_status', 'Outgoing')->where('d_routingthru', '!=', 0)->orderBy('created_at', 'asc')->value('created_at');
												$end_date 	= App\Models\Document::where('d_status', 'Outgoing')->where('d_routingthru', '!=', 0)->orderBy('created_at', 'desc')->value('created_at');
												$first 		= date('Y', strtotime($start_date));
												$last 		= date('Y', strtotime($end_date));

												$years      = array_combine(range(date("Y"), $first), range(date("Y"), $first));
											?>
											@if(Auth::user()->ug_id == 3)
											Search For The Whole Year?
											{!! Form::select('wholeYear', [	
										    	'Yes' => 'Yes',											   	
											   	'No'  => 'No'],
											   	null, ['class' => 'form-control input-sm']) 
											!!}
											<br>
											@endif
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
											@if(Auth::user()->ug_id == 3)
											Enter Keyword:
											{!! Form::text('reportSearch', $reportSearch, ['class' => 'form-control', 'placeholder' => 'Search Document']) !!}
											<br>
											@endif
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