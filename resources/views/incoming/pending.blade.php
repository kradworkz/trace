@extends('layouts.main')
@section('content')
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
							<h3 class="page-header"><i class="fa fa-exclamation-triangle"></i><strong> Pending Documents</strong></h3>					
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
		                    <label class="label-alert">LEGENDS: <br/>
		                        <font color="red"><i class="fa fa-exclamation-circle"></i></font> - beyond the deadline of posting the outgoing document or doing the action.<br>
		                        @if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3)
		                        	<font color="red"><i class="fa fa-thumb-tack"></i></font> - haven't routed the document<br>		                        	
		                        @else
		                            <font color="red"><i class="fa fa-eye-slash"></i></font> - haven't viewed the document<br>		                            
		                        @endif
		                        @if(Auth::user()->ug_id == 2)
		                        <font color="blue"><i class="fa fa-thumb-tack"></i></font> - document was routed thru your account<br>
		                        @endif
		                        <font color="red"><i class="fa fa-exclamation-triangle"></i></font> - RUSH document
		                        @if(Auth::user()->ug_id == 2)
		                        <br><br>For Still Pending Column Legends:<br>
		                        <font color="orange">OPENED THE DOCUMENT, BUT DIDN'T CHECK THE ACTION DONE CHECKBOX/NO ACTION INDICATED</font><br>
		                        <font color="red">HAVEN'T OPENED THE DOCUMENT</font>
		                        @endif
		                    </label>
		                </div> 
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<div class="pull-right">
							{!! Form::open(['url' => 'pending/search', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off']) !!}
								<div class="form-group">
									<div class="input-group input-group-sm">
										{!! Form::text('search', $search, ['class' => 'form-control', 'placeholder' => 'Search Document']) !!}
										<span class="input-group-btn">
											{!! Form::button('<span class="fa fa-search"></span> Search', ['class' => 'btn btn-primary btn-xs', 'type' => 'submit', 'title' => 'Search']) !!}
											<a href="{{ url('pending') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Reset</a>
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
									@if(Request::segment(1) == 'pending')
									@if(Auth::user()->group_id != 1)
									<th>Date/Time Tagged</th>
									@endif
									@endif
									<th>Comments</th>
									<th>Keywords</th>
									@if(Auth::user()->ug_id == 2)<th>Still Pending</th>@endif
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($documents as $document)
								<tr>
									<td>
										@if($ugr_add != 1)
											<?php 
												$drseen = App\Models\DocumentRouting::where('d_id', $document->d_id)->value('dr_seen'); 
												$drcomp = App\Models\DocumentRouting::where('d_id', $document->d_id)->value('dr_completed');
											?>
											@if($drseen == 0)
											<font color="red"><i class="fa fa-eye-slash"></i></font>
											@endif
										@endif
										<?php
											$rush 		= App\Models\Document::where('d_id', $document->d_id)->where('d_actions', 'LIKE', "1")->count();
											$rush_more 	= App\Models\Document::where('d_id', $document->d_id)->where('d_actions', 'LIKE', "1,%")->count();
										?>
										@if($rush > 0 || $rush_more > 0)
											<font color="red"><i class="fa fa-exclamation-triangle"></i></font>
										@endif
										{{ $document->d_subject }}
										@if($document->d_istrack == 1)
										<?php
	                                        //$dtr 		= date('Y-m-d', strtotime($document->d_datetimerouted));
											if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3) {
				                    			$dtr 	= date('Y-m-d', strtotime($document->d_datetimerouted));
				                    		} else {
				                    			$t_route= App\Models\DocumentRouting::where('d_id', $document->d_id)->where('u_id', Auth::user()->u_id)->value('created_at');
				                    			$dtr 	= date('Y-m-d', strtotime($t_route));
				                    		}
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
									<td>{{ date('F d, Y h:i A', strtotime($document->created_at)) }}</td>
									<td>{{ date('F d, Y h:i A', strtotime($document->d_datetimerouted)) }}</td>
									@if(Request::segment(1) == 'pending')
									@if(Auth::user()->group_id != 1)
									<td>{{ date('F d, Y h:i A', strtotime(App\Models\DocumentRouting::where('d_id', $document->d_id)->where('u_id', Auth::user()->u_id)->value('created_at'))) }}</td>
									@endif
									@endif
									<td>{{ App\Models\Comment::where('comm_document', 1)->where('comm_reference', $document->d_id)->count() }}</td>
									<td>{{ $document->d_keywords }}</td>
									<td>
										<?php $doc_routes = App\Models\DocumentRouting::where('d_id', $document->d_id)->where('dr_completed', 0)->get(); ?>
										@foreach($doc_routes as $doc_route)
										@if($doc_route->dr_seen == 1 && $doc_route->dr_completed == 0)
										<font color="orange">
										@elseif($doc_route->dr_seen == 0 && $doc_route->dr_completed == 0)
										<font color="red">
										@endif
										{{ App\Models\User::where('u_id', $doc_route->u_id)->value('u_fname') }} {{ App\Models\User::where('u_id', $doc_route->u_id)->value('u_lname') }}<br>
										</font>
										@endforeach
									</td>
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
				</div>
			</div>
		</section>
	</section>
@stop