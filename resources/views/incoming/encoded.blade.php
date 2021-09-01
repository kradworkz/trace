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
							<h3 class="page-header"><i class="fa fa-files-o"></i><strong> {{ $data['page'] }}</strong>
								@if(Auth::user()->ug_id == 3 || Auth::user()->ug_id == 5)
								<a href="{{ url('incoming/add') }}" class="header-link" title="Add"> [Add Incoming Document]</a>
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
						{!! Form::open(['url' => 'incoming/encoded', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off']) !!}
						<div class="form-group">
							<div class="input-group input-group-sm">
								{!! Form::text('search', $search, ['class' => 'form-control', 'placeholder' => 'Search Document', 'id' => 'search']) !!}
								<span class="input-group-btn">
									{!! Form::button('<span class="fa fa-search"></span> Search', ['class' => 'btn btn-primary btn-xs', 'type' => 'submit', 'title' => 'Search']) !!}
									<a href="{{ url('incoming/encoded') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Reset</a>
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
										@endif
										{{ $document->d_subject }}
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
									<td>{{ $document->d_keywords }}</td>
									<td>{{ App\Models\Comment::where('comm_document', 1)->where('comm_reference', $document->d_id)->count() }}</td>
									<td class="text-right">
										@if($document->d_routingthru != 0)
										<a href="{{ url('incoming/view/'.$document->d_id) }}" class="btn btn-success btn-xs" title="View"><span class="fa fa-eye"></span></a>
										@endif
										@if($ugr_edit == 1)
											@if($document->d_encoded_by == Auth::user()->u_id)
											<a href="{{ url('incoming/edit/'.$document->d_id) }}" class="btn btn-warning btn-xs" title="Edit"><span class="fa fa-edit"></span></a>
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
				</div>
			</div>
		</section>
	</section>
@stop