@extends('layouts.main')
@section('content')	
	<style type="text/css">
		body { color: black !important; }
	</style>

	<!--main content start-->
    <section id="main-content">
    	<section class="wrapper">            
            <!--overview start-->
			<div class="row">
				<div class="col-lg-12">
					<h3 class="page-header"><i class="fa fa-home"></i><strong> {{ $data['page'] }}</strong></h3>
				</div>
			</div>

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
					</div>
				</div>
			</div>
              
            <div class="row">
            	<a href="{{ asset('incoming') }}">
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<div class="info-box blue-bg">
							<i class="fa fa-inbox"></i>
							<div class="count">{{ count($indocs) }}</div>
							<div class="title">Incoming Documents</div>						
						</div><!--/.info-box-->			
					</div><!--/.col-->
				</a>
				
				<a href="{{ asset('pending') }}">
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<div class="info-box yellow-bg">
							<i class="fa fa-exclamation-triangle"></i>
							<div class="count">{{ count($pendocs) }}</div>
							<div class="title">Pending Documents</div>						
						</div><!--/.info-box-->			
					</div><!--/.col-->
				</a>

				<a href="{{ asset('outgoing') }}">
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<div class="info-box green-bg">
							<i class="fa fa-file-o"></i>
							<div class="count">{{ count($outdocs) }}</div>
							<div class="title">Outgoing Documents</div>						
						</div><!--/.info-box-->
					</div><!--/.col-->
				</a>

				<a href="{{ asset('events') }}">					
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<div class="info-box red-bg">
							<i class="fa fa-calendar"></i>
							<div class="count">{{ count($events) }}</div>
							<div class="title">Events</div>						
						</div><!--/.info-box-->
					</div><!--/.col-->
				</a>
			</div><!--/.row-->

			<div class="row">
				@if(count($today_docs) > 3 && count($yester_docs) > 2)
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="overflow-y: scroll; height: 500px;">
				@else
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				@endif
					<div class="table-responsive">
		                <table class="table table-bordered" id="dataTables2">
		                    <thead>
		                        <tr>
		                            <th>INCOMING TODAY</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    	@if(count($today_docs) > 0)
			                    @foreach($today_docs as $today_doc)
			                    	<tr>
			                    		<td><a href="{{ URL::to('incoming/view/'.$today_doc->d_id) }}">{{ $today_doc->d_subject }}</a></td>
			                    	</tr>
			                    @endforeach
			                    @else
			                    	<tr><td>No document(s) available.</td></tr>
			                    @endif
		                    </tbody>
		                </table>
		            </div>
		            <div class="table-responsive">
	                    <table class="table table-bordered" id="dataTables2">
	                        <thead>
	                            <tr>
	                                <th>INCOMING YESTERDAY</th>
	                            </tr>
	                        </thead>	                        
	                        <tbody>
	                        	@if(count($yester_docs) > 0)
	                        	@foreach($yester_docs as $yester_doc)
	                        		<tr>
	                        			<td><a href="{{ URL::to('incoming/view/'.$yester_doc->d_id) }}">{{ $yester_doc->d_subject }}</a></td>
	                        		</tr>
	                        	@endforeach
	                        	@else
	                        		<tr><td>No document(s) available.</td></tr>
	                        	@endif
	                        </tbody>
	                    </table>
	                </div>
				</div>
				
				@if(count($pendocs) > 3)
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="overflow-y: scroll; height: 500px;">
				@else
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				@endif
					<div class="table-responsive">
		                <table class="table table-bordered" id="dataTables2">
		                    <thead>
		                        <tr>
		                            <th>PENDING</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    	@if(count($pendocs) > 0)
			                    @foreach($pendocs as $pendoc)
			                    	<?php
			                    		//$dtr 		= date('Y-m-d', strtotime($pendoc->d_datetimerouted));
			                    		if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3) {
			                    			$dtr 	= date('Y-m-d', strtotime($pendoc->d_datetimerouted));
			                    		} else {
			                    			$t_route= App\Models\DocumentRouting::where('d_id', $pendoc->d_id)->where('u_id', Auth::user()->u_id)->value('created_at');
			                    			$dtr 	= date('Y-m-d', strtotime($t_route));
			                    		}			               
										$day 		= App\Models\Setting::orderBy('s_id', 'desc')->value('s_pending_days');
										$newDate	= date('Y-m-d', strtotime($dtr. " + {$day} days"));
										$today 		= date('Y-m-d');
										$difference	= (strtotime($today) - strtotime($newDate));
										$no_of_days = floor($difference/(60*60*24));
	                                ?>
			                    	<tr>
	                        			<td>
	                        				{{ $pendoc->d_routingslip }}<br>
	                        				{{ $pendoc->d_sender }}<br>
	                        				{{ App\Models\Company::where('c_id', $pendoc->c_id)->value('c_name') }}<br>
	                        				<a href="{{ URL::to('incoming/view/'.$pendoc->d_id) }}"><strong>{{ $pendoc->d_subject }}</strong></a><br>	
	                        				<i>{{ date('Y-m-d', strtotime($pendoc->d_datetimerouted)) }}</i>
	                        				<div class="pull-right">
	                        					<font color='red'>
	                        						@if( $no_of_days > 0) 
	                        							<i class='fa fa-exclamation-circle'></i> {{ $no_of_days }} day/s
	                        						@endif
	                        					</font>
	                        				</div>
	                        			</td>
	                        		</tr>
			                    @endforeach
			                    @else
			                    	<tr><td>No pending document(s).</td></tr>
			                    @endif
		                    </tbody>
		                </table>
		            </div>
				</div>
				
				@if(count($outs_today) > 5)
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="overflow-y: scroll; height: 500px;">
				@else
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				@endif
					<div class="table-responsive">
		                <table class="table table-bordered" id="dataTables2">
		                    <thead>
		                        <tr>
		                            <th>OUTGOING TODAY</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    	@if(count($outs_today) > 0)
			                    @foreach($outs_today as $out_today)
			                    	<tr>
			                    		<td><a href="{{ URL::to('outgoing/view/'.$out_today->d_id) }}">{{ $out_today->d_subject }}</a></td>
			                    	</tr>
			                    @endforeach
			                    @else
			                    	<tr><td>No document(s) sent today.</td></tr>
			                    @endif
		                    </tbody>
		                </table>
		            </div>
				</div>
								
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					@if(count($events) > 0)
					<div class="table-responsive">
						<table class="table table-bordered" id="dataTables2">
							<thead>
								<tr><th>EVENT</th></tr>
							</thead>
							<tbody>
								<tr><td>Name: <a href="{{ URL::to('events/view/'.$latest_event->e_id) }}">{{ $latest_event->e_name }}</a></td></tr>
								<tr><td>Type: {{ $latest_event->e_type }}</td></tr>
								<tr>
			                   		<?php
			                   			$start 	= $latest_event->e_start_date;
			                   			$end 	= $latest_event->e_end_date;
			                   		?>
			                   		<td>Date:
			                   			@if($start != $end)
			                   				<br>{{ date('F d, Y', strtotime($start)) }} - {{ date('F d, Y', strtotime($end)) }}
			                   			@else
			                   				{{ date('F d, Y', strtotime($start)) }}
			                   			@endif
			                   		</td>
			                   </tr>
			                   <tr><td>Time: {{ $latest_event->e_start_time }} to {{ $latest_event->e_end_time }}</tr>
			                   <?php $attachments = App\Models\EventAttachment::where('e_id', $latest_event->e_id)->get(); ?>
			                   @if(count($attachments) > 0)
			                   <tr>		                   		
			                   		<td>File Attachments:<br>
			                   			@foreach( $attachments as $attachment )
		                    				@if( $attachment->ea_file != "" )
		                    					<?php $file = $attachment['ea_file']; ?>
		                    					@if ( is_file($file) )
		                    						<a href="{{ URL::to($attachment->ea_file) }}" target="_blank" download="{{$attachment->ea_file}}">{{ basename($attachment->ea_file) }}</a><br>
		                    					@else
		                    						File missing.
		                    					@endif
		                    				@endif
		                    			@endforeach
			                   		</td>
			                   </tr>
			                   @endif
							</tbody>
						</table>
					</div>
					@endif
				</div>
			</div>
			
			@if($unseen_comm->isEmpty())
			@else
				<div class="row">
					<div class="col-lg-12">
						<br><br>
						<hr class="style17">
						<h3 class="page-header"><i class="fa fa-comments"></i><strong> LATEST COMMENTS</strong></h3>
					</div>
				</div>
				<table class="table">
					<thead>
						<tr>
							<th>Subject</th>
							<th>Actions Needed</th>
							<th>Comments</th>							
						</tr>
					</thead>
					<tbody>
						@foreach($unseen_comm as $comm)
						@if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3)
						<tr>
							<?php 
								$ref_status = App\Models\Document::where('d_id', $comm->comm_reference)->value('d_status'); 
								$ref_comp 	= App\Models\Document::where('d_id', $comm->comm_reference)->value('c_id');															
							?>
							<td>
								@if($ref_status == 'Incoming')
								<a href="{{ URL::to('incoming/view/'.$comm->comm_reference) }}" target="_blank" >
								{{ $comm->document->d_subject }}</a><br>
								<i>Sender: {{ $comm->document->d_sender }}<br>
								@elseif($ref_status == 'Outgoing')
								<a href="{{ URL::to('outgoing/view/'.$comm->comm_reference) }}" target="_blank" >
								{{ $comm->document->d_subject }}</a><br>
								<i>Addressee: {{ $comm->document->d_addressee }}<br>
								@endif
								Company: {{ App\Models\Company::where('c_id', $ref_comp)->value('c_name') }}</i>
							</td>
							<td>
								@foreach(explode(',', $comm->document->d_actions) as $action) 
									{{ App\Models\Action::where('a_id', $action)->value('a_action') }}<br>
								@endforeach
								@if($comm->document->d_remarks != NULL)
									<i>Additional Remarks: {{ $comm->document->d_remarks }}</i>
								@endif
							</td>
							<td>
								<?php $doc_comms = App\Models\Comment::where('comm_reference', $comm->comm_reference)->where('comm_document', 1)->get(); ?>
								@if(count($doc_comms) < 4)
									@foreach($doc_comms as $doc_comm)
									<strong>{{ $doc_comm->users->u_fname }} {{ $doc_comm->users->u_lname }}:</strong>
									{{ $doc_comm->comm_text }}<br>
									@endforeach
								@else
									<strong>{{ count($doc_comms) }} new comments</strong> 
									<i>
										@if($ref_status == 'Incoming')
										<a href="{{ URL::to('incoming/view/'.$comm->comm_reference) }}" target="_blank" >
										@elseif($ref_status == 'Outgoing')
										<a href="{{ URL::to('outgoing/view/'.$comm->comm_reference) }}" target="_blank" >
										@endif
										[View Document...]</a>
									</i>
								@endif
							</td>
						</tr>
						@else
						<tr>
							<?php
								$ref_status = App\Models\Document::where('d_id', $comm->d_id)->value('d_status');
								$ref_comp 	= App\Models\Document::where('d_id', $comm->d_id)->value('c_id');
							?>
							<td>
								@if($ref_status == 'Incoming')
								<a href="{{ URL::to('incoming/view/'.$comm->d_id) }}" target="_blank" >
								{{ $comm->subject->d_subject }}</a><br>
								<i>Sender: {{ $comm->subject->d_sender }}<br>
								@elseif($ref_status == 'Outgoing')
								<a href="{{ URL::to('outgoing/view/'.$comm->d_id) }}" target="_blank" >
								{{ $comm->subject->d_subject }}</a><br>
								<i>Addressee: {{ $comm->subject->d_addressee }}<br>
								@endif
								Company: {{ App\Models\Company::where('c_id', $ref_comp)->value('c_name') }}</i>
							</td>
							<td>
								@foreach(explode(',', $comm->subject->d_actions) as $action)
									{{ App\Models\Action::where('a_id', $action)->value('a_action') }}<br>
								@endforeach
								@if($comm->subject->d_remarks != NULL)
									<i>Additional Remarks: {{ $comm->subject->d_remarks }}</i>
								@endif
							</td>
							<td>
								<?php $doc_comms = App\Models\Comment::where('comm_reference', $comm->d_id)->where('comm_document', 1)->get(); ?>
								@if(count($doc_comms) < 4)
									@foreach($doc_comms as $doc_comm)
									<strong>{{ $doc_comm->users->u_fname }} {{ $doc_comm->users->u_lname }}:</strong>
									{{ $doc_comm->comm_text }}<br>
									@endforeach
								@else
									<strong>{{ count($doc_comms) }} new comments</strong> 
									<i>
										@if($ref_status == 'Incoming')
										<a href="{{ URL::to('incoming/view/'.$comm->d_id) }}" target="_blank" >
										@elseif($ref_status == 'Outgoing')
										<a href="{{ URL::to('outgoing/view/'.$comm->d_id) }}" target="_blank" >
										@endif
										[View Document...]</a>
									</i>
								@endif
							</td>
						</tr>
						@endif
						@endforeach
					</tbody>
				</table>
				@if($unseen_comm->render())
					<div class="text-center">{!! $unseen_comm->render() !!}</div>
				@endif
			@endif
		</section>
    </section>
@stop