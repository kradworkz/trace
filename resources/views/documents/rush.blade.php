@extends('layouts.main')
@section('content')
	<!--main content start-->
    <section id="main-content">
    	<section class="wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="pull-left">
							<h3 class="page-header"><i class="fa fa-files-o"></i><strong> {{ $data['page'] }} || RUSH DOCUMENTS</strong>
							</h3>					
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">
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
										@if($document->d_routingthru == 0)
											<font color="red"><i class="fa fa-thumb-tack"></i></font>
											@if(Auth::user()->ug_id == 1)
											<a href="{{ URL::to('incoming/route/'.$document->d_id) }}" title="Route Document"><strong>{{ $document->d_subject }}</strong></a>
											@else
											{{ $document->d_subject }}
											@endif
										@else											
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
									<td>{{ App\Models\Comment::where('comm_document', 1)->where('comm_reference', $document->d_id)->count() }}</td>
									<td>{{ $document->d_keywords }}</td>					
									<td class="text-right">										
										@if($document->d_routingthru != 0)
										<a href="{{ url('incoming/view/'.$document->d_id) }}" class="btn btn-success btn-xs" title="View"><span class="fa fa-eye"></span></a>
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
				</div>
			</div>
		</section>
	</section>
@stop