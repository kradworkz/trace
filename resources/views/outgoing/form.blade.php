@extends('layouts.main')
@section('content')
	<link rel="stylesheet" href="{{ asset('extensions/chosen/chosen.css') }}" type="text/css">
	<link rel="stylesheet" href="{{ asset('extensions/chosen/chosen-bootstrap.css') }}" type="text/css">	
	<script src="{{ asset('extensions/chosen/chosen.jquery.min.js') }}"></script>
	<link href="{{ asset('extensions/events/datepicker.css') }}" rel="stylesheet">
	<link href="{{ asset('extensions/events/bootstrap-tagsinput.css') }}" rel="stylesheet">

	@if($option == "View")
	<style type="text/css">
		.text-danger { visibility: hidden; }
	</style>
	@endif	

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

	{!! Form::model($document, ['url' => 'outgoing/save', 'class' => 'form-horizontal', 'id' => 'groupForm', 'files' => true]) !!}
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
							<h3><strong>
								<a href="{{ url('outgoing') }}">{{ $data['page'] }}</a> | {{ $option }}
								@if($option == 'View' && $document->d_routingfrom == Auth::user()->u_id)| <a href="{{ url('outgoing/edit/'.$document->d_id) }}">Edit</a>@elseif($option == 'Edit')| <a href="{{ url('outgoing/view/'.$document->d_id) }}">View</a> @endif
							</strong></h3>					
						</div>
					</div>
				</div>
				@if($option == 'View')
				@if(count($references) > 0)
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reference Outgoing Document(s):<br>
				@foreach($references as $reference)
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{{ URL::to('incoming/view/'.$reference->d_id) }}">{{ $reference->d_subject }}</a>
				@endforeach
				@endif
				@endif
				<!-- ATTACHMENT DETAILS FOR DC -->
				<div class="panel panel-default panel-plain">			
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Attachment Details</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								{!! Form::hidden('id', $id) !!}
								{!! Form::hidden('d_option', $option) !!}
								<div class="form-group {{ ($errors->has('d_subject') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Subject: <span class="text-danger">*</span></label>
									<div class="col-md-8">
									@if($option == 'Add' || $option == 'Edit')
									{!! Form::text('d_subject', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#d_subject', $option == 'View' ? 'readonly' : '']) !!}
									@else
									{!! Form::text('d_subject', null, ['class' => 'form-control input-sm align-form', 'readonly']) !!}
									@endif
									</div>
									<span class="text-danger"><small><strong id="d_subject" class="align-text"> {{ $errors->first('d_subject') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('dtypes') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Document Type: <span class="text-danger">*</span></label>
									<div class="col-md-8">
									@if($option == 'Add' || $option == 'Edit')
									{!! Form::select('dtypes', $dtypes, $option == 'Edit' || $option == 'View' ? $document->dt_id : NULL, ['class' => 'form-control input-sm chosen-select', 'data-validation' => 'required', $option == 'View' ? 'readonly' : '']) !!}
									@else
									{!! Form::text('dt_id', $document->dtypes->dt_type, ['class' => 'form-control input-sm align-form', 'readonly']) !!}
									@endif
									</div>
									<span class="text-danger"><small><strong id="dtypes" class="align-text"> {{ $errors->first('dtypes') }}</strong></small></span>
								</div>
								@if($option == 'Add')
								<div class="form-group {{ ($errors->has('d_file') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">File Attachments: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::file('d_file[]', ['class' => 'align-form', 'multiple'=>true]) !!}</div>
									<span class="text-danger"><small><strong id="d_file" class="align-text"> {{ $errors->first('d_file') }}</strong></small></span>
								</div>
								@elseif($option == 'View')
								<div class="form-group {{ ($errors->has('da_file') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">File Attachments: <span class="text-danger">*</span></label>
									<div class="col-md-8">
										@foreach( $files as $file )                                		
		                    				@if( $file->da_file != "" )
		                    					<?php $attachment = $file['da_file']; ?>
		                    					@if ( is_file($attachment) )                                            
		                    						<a href="{{ URL::to($file->da_file) }}" target="_blank" >{{ basename($file->da_file) }}</a><br>                                            
		                    					@else
		                    						File missing.<br>
		                    					@endif
		                    				@else
		                    					No file uploaded.
		                    				@endif
		                    			@endforeach
									</div>
									<span class="text-danger"><small><strong id="da_file" class="align-text"> {{ $errors->first('da_file') }}</strong></small></span>
								</div>
								@endif
								<div class="form-group {{ ($errors->has('d_documentdate') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Document Date: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('d_documentdate', null, ['class' => 'form-control input-sm', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#d_documentdate', 'id' => 'd_documentdate', 'maxlength' => '10', 'placeholder' => 'Document Date', 'readonly']) !!}</div>
									<span class="text-danger"><small><strong id="d_documentdate" class="align-text"> {{ $errors->first('d_documentdate') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('d_datesent') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Date Sent: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('d_datesent', null, ['class' => 'form-control input-sm', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#d_datesent', 'id' => 'd_datesent', 'maxlength' => '10', 'placeholder' => 'Date Sent', 'readonly']) !!}</div>
									<span class="text-danger"><small><strong id="d_datesent" class="align-text"> {{ $errors->first('d_datesent') }}</strong></small></span>
								</div>	
								<div class="form-group {{ ($errors->has('d_addressee') ? ' has-error ' : '') }} has-feedback">									
									<label class="col-md-2 control-label">Addressee: <span class="text-danger">*</span></label>
									<div class="col-md-8">
									@if($option == 'Add' || $option == 'Edit')
									{!! Form::text('d_addressee', null, ['class' => 'form-control input-sm', 'id' => 'addressee', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#d_addressee']) !!}
									@else
									{!! Form::text('d_addressee', null, ['class' => 'form-control input-sm align-form', 'readonly']) !!}
									@endif
									</div>
									<span class="text-danger"><small><strong id="d_addressee" class="align-text"> {{ $errors->first('d_addressee') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('c_name') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Company: <span class="text-danger">*</span></label>
									<div class="col-md-8">
									@if($option == 'Add')
									{!! Form::text('c_name', null, ['class' => 'form-control input-sm align-form', 'id' => 'company', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#c_name', $option == 'View' ? 'readonly' : '']) !!}							
									@elseif($option == 'Edit')
									{!! Form::text('c_name', $document->companies->c_name, ['class' => 'form-control input-sm align-form', 'id' => 'company', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#c_name', $option == 'View' ? 'readonly' : '']) !!}
									@else
									{!! Form::text('c_id', $document->companies->c_name, ['class' => 'form-control input-sm align-form', 'readonly']) !!}
									@endif
									</div>
									<span class="text-danger"><small><strong id="c_name" class="align-text"> {{ $errors->first('c_name') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('d_keywords') ? ' has-error ' : '') }} has-feedback">
		                            <label class="col-md-2 control-label">Keywords: <span class="text-danger">*</span></label>
		                            <div class="col-md-8"><input class="form-control input-sm align-form" data-role="tagsinput" data-validation="required" data-validation-error-msg-container="#d_keywords" type="text" @if($option=='Add') value="" @else value="{{ $document->d_keywords }}" @endif name="d_keywords" id="d_keywords" @if($option == 'View') disabled @endif placeholder="Separate by comma"></div>
		                            <span class="text-danger"><small><strong id="d_keywords" class="align-text"> {{ $errors->first('d_keywords') }}</strong></small></span>
		                        </div>
							</div>
						</div>
					</div>
				</div>

				<!-- ROUTING DETAILS -->
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Routing Details</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">					
								<div class="form-group {{ ($errors->has('d_routingslip') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Routing Slip:</label>
									<div class="col-md-8">
									@if($option == 'Add')
									{!! Form::text('d_routingslip', $routingslip, ['class' => 'form-control input-sm align-form', 'readonly']) !!}							
									@else							
									{!! Form::text('d_routingslip', null, ['class' => 'form-control input-sm align-form', 'readonly']) !!}													
									@endif
									</div>
									<span class="text-danger"><small><strong id="d_routingslip" class="align-text"> {{ $errors->first('d_routingslip') }}</strong></small></span>
								</div>
								@if($option == 'View')
								<div class="form-group {{ ($errors->has('u_id') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Sent By: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('u_id', $document->user->u_fname." ".$document->user->u_lname, ['class' => 'form-control input-sm align-form', 'readonly']) !!}</div>							
								</div>
								@endif

								@if($option == 'Add')
								<div class="form-group">
		                            <label class="col-md-2 control-label">Route Mode:</label>
									<div class="col-md-8">
										<select name="tagMode" id="tagMode" class="form-control input-sm align-form">
		                            		<option value="0">Individual</option>
		                            		<option value="1">Group</option>
		                            		<option value="2">Tag All Employees</option>
		                            	</select>
									</div>
		                        </div>
								<div id="tag_individual">
									<div class="form-group">
			                            <label class="col-md-2 control-label">Route To:</label>
										<div class="col-md-8">								
											<select data-placeholder="Tag Individually" class="chosen-select form-control input-sm" multiple name="individualTag[]" id="individualTag">
			                        			<option value=""></option>
			                                	@foreach($members as $member)
			                                		<option value="<?php echo $member['u_id']; ?>"><?php echo ucfirst($member['u_lname']).", ".ucfirst($member['u_fname']); ?></option>
			                                	@endforeach
			                                </select>
										</div>
			                        </div>
								</div>
								<div id="tag_group">
									<div class="form-group">
			                            <label class="col-md-2 control-label">Route To:</label>
										<div class="col-md-8">								
											<select data-placeholder="Tag By Group" class="chosen-select form-control" multiple name="groupTag[]" id="groupTag">
			                        			<option value=""></option>
			                                	@foreach($groups as $group)
			                                		<option value="<?php echo $group['group_id']; ?>"><?php echo $group['group_name']; ?></option>
			                                	@endforeach
			                                </select>
										</div>
			                        </div>
								</div>
								@endif
							</div>
						</div>
					</div>
				</div>

				<!-- FILE ATTACHMENTS FOR EDIT -->
				@if($option == 'Edit')
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>File Attachments</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">			
								<div class="form-group {{ ($errors->has('d_file') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">File Attachments:</label>
									<div class="col-md-8">{!! Form::file('d_file[]', ['class' => 'align-form', 'multiple'=>true]) !!}</div>
									<span class="text-danger"><small><strong id="d_file" class="align-text"> {{ $errors->first('d_file') }}</strong></small></span>
								</div>			
								@if($attachments->isEmpty())							
									<div class="panel-body">
										<h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No existing attachment(s) found.</strong></h5>
									</div>
								@else
									<table class="table">
										<thead>
											<tr>
												<th>Filename</th>
												<th>Date/Time Uploaded</th>										
												<th></th>
											</tr>
										</thead>
										<tbody>
											@foreach($attachments as $attachment)
												<tr>											
													<td><a href="{{ URL::to($attachment->da_file) }}" target="_blank" >{{ basename($attachment->da_file) }}</a></td>
													<td>{{ date('F d, Y h:i A', strtotime($attachment->created_at)) }}</td>
													<td class="text-right">
														<a href="{{ url('outgoing/attachment_delete/'.$attachment->da_id) }}" class="btn btn-danger btn-xs" title="Delete"><span class="fa fa-trash"></span></a>
													</td>
												</tr>
											@endforeach
										</tbody>
									</table>
									@if($attachments->render())
										<div class="panel-footer text-center">{!! $attachments->render() !!}</div>
									@endif
								@endif							
							</div>
						</div>
					</div>			
				</div>
				@endif

				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Remarks</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">					
								<div class="form-group {{ ($errors->has('d_remarks') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Remarks:</label>
									<div class="col-md-8">
									@if($option == 'Add' || $option == 'Edit')
									{!! Form::textarea('d_remarks', NULL, ['class' => 'form-control input-sm', 'size' => '10x5']) !!}
									@else
									{!! Form::textarea('d_remarks', NULL, ['class' => 'form-control input-sm', 'size' => '10x5', 'disabled']) !!}
									@endif
									</div>
									<span class="text-danger"><small><strong id="d_remarks" class="align-text"> {{ $errors->first('d_remarks') }}</strong></small></span>
								</div>
								@if($option == 'Add' || $option == 'Edit')
								<div class="form-group">
									<div class="col-md-3 col-md-offset-2">
										{!! Form::button('<span class="fa fa-save"></span> Save', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit', $option == 'View' ? 'disabled' : '']) !!}
										<a href="{{ url('outgoing') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Cancel</a>
									</div>
								</div>
								@endif
							</div>

						</div>
					</div>
					
				</div>

				<!-- NOTIFICATION DETAILS -->
				@if($option == 'View' || $option == 'Edit')
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Notification Details</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">						
								<div class="form-group">
		                            <label class="col-md-2 control-label">Route Mode:</label>
									<div class="col-md-8">
										<select name="tagMode" id="tagMode" class="form-control input-sm align-form">
		                            		<option value="0">Individual</option>
		                            		<option value="1">Group</option>
		                            		<option value="2">Tag All Employees</option>
		                            	</select>
									</div>
		                        </div>
								<div id="tag_individual">
									<div class="form-group">
			                            <label class="col-md-2 control-label">Route To:</label>
										<div class="col-md-8">								
											<select data-placeholder="Tag Individually" class="chosen-select form-control input-sm" multiple name="individualTag[]" id="individualTag">
			                        			<option value=""></option>
			                                	@foreach($members as $member)
			                                		<option value="<?php echo $member['u_id']; ?>"><?php echo ucfirst($member['u_lname']).", ".ucfirst($member['u_fname']); ?></option>
			                                	@endforeach
			                                </select>
										</div>
			                        </div>
								</div>
								<div id="tag_group">
									<div class="form-group">
			                            <label class="col-md-2 control-label">Route To:</label>
										<div class="col-md-8">								
											<select data-placeholder="Tag By Group" class="chosen-select form-control" multiple name="groupTag[]" id="groupTag">
			                        			<option value=""></option>
			                                	@foreach($groups as $group)
			                                		<option value="<?php echo $group['group_id']; ?>"><?php echo $group['group_name']; ?></option>
			                                	@endforeach
			                                </select>
										</div>
			                        </div>
								</div>
								<hr>
								<div class="form-group">
									<div class="col-md-3 col-md-offset-2">
										{!! Form::button('<span class="fa fa-bell"></span> Notify Other Employees', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
										<a href="{{ url('outgoing') }}" class="btn btn-danger btn-sm"><span class="fa fa-undo"></span> Back To Outgoing</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>			

				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Tagged Employees</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">						
								@if($routes->isEmpty())
									<div class="panel-body">
										<h5><strong><i class="fa fa-info-circle"></i> No tagged employee(s) found.</strong></h5>
									</div>														
								@else
									<table class="table">
										<thead>
											<tr>
												<th>Name</th>										
												<th>Date Invited</th>
												@if($document->d_routingfrom == Auth::user()->u_id)
												<th></th>
												@endif
											</tr>
										</thead>
										<tbody>
											@foreach($routes as $route)
											<tr>
												<td>{{ $route->seen->u_lname }}, {{ $route->seen->u_fname }}</td>
												<td>{{ date('F d, Y h:i A', strtotime($route->created_at)) }}</td>
												@if($document->d_routingfrom == Auth::user()->u_id)
												<td class="text-right">																						
													<a href="{{ url('outgoing/view/remove-tag/'.$route->dr_id) }}" class="btn btn-danger btn-xs" title="Remove"><span class="fa fa-trash"></span></a>											
												</td>
												@endif
											</tr>
											@endforeach
										</tbody>
									</table>
									@if($routes->render())
										<div class="panel-footer text-center">{!! $routes->render() !!}</div>
									@endif
								@endif		
							</div>
						</div>
					</div>
				</div>
				@endif

				<!-- COMMENT THREAD -->
				@if($option == 'View')
				<div class="row">
					<div class="col-md-6">
						<div class="panel panel-default panel-plain">
							<div class="panel-heading">
								<h1 class="panel-title"><strong>Comment Thread</strong></h1>
							</div>
							<div class="panel-body">
								<div class="row">
									@if(count($comments) == 0)
									<div class="col-md-12" style="height: 204px;">							
									<h4><center>NO COMMENTS</center></h4>
									@else
									<div class="col-md-12" style="overflow-y: scroll; height: 408px;">
										<ul class="timeline">
									@endif								
										<?php
											$num = 0;
											foreach($comments as $comment) {
												$picture 					= $comment->users->u_picture;

												if($picture != "") {
													$picture_file 			= $comment->users->u_picture;

													if(is_file($picture_file)) {
														$profile_picture 	= $picture;
													} else {
														$profile_picture 	= 'upload/profile/no-user-photo.png';
													}
												} else {
													$profile_picture		= 'upload/profile/no-user-photo.png';
												}

												$num++;

			                                    if(($num % 2) == 0) { 
			                                        $timeline_class         = 'timeline-inverted';
			                                    } else { 
			                                        $timeline_class         = ''; 
			                                    } ?>
			                                    <li class="{{ $timeline_class }}">
			                                    	<div class="timeline-badge">
			                                            <img class="img-circle" src="{{ asset($profile_picture) }}" width="50" height="50">
			                                        </div>
			                                        <div class="timeline-panel">
			                                            <div class="timeline-heading">
			                                                <h4 class="timeline-title">{{ $comment->users->u_fname }}</h4>
			                                                    <p><small class="text-muted"><i class="fa fa-clock-o"></i> {{ date('F d, Y', strtotime($comment->created_at)) }}</small></p>
			                                            </div>
			                                            <div class="timeline-body">
			                                            	<p>
			                                            		@if($comment->comm_reply != NULL)
			                                            		<font color="blue" size="1"><i class="fa fa-reply"></i> {{ App\Models\Comment::where('comm_id', $comment->comm_reply)->value('comm_text') }}</font>
			                                            		<br>
			                                            		@endif
			                                            		@if($comment->comm_tag == 1)
			                                            		<?php
			                                            			$dcomms = App\Models\DCommentSeen::where('comm_id', $comment->comm_id)->get();
			                                            		?>
			                                            		<font color="#454ede"><i>Tagged:</i></font>
			                                            		@foreach($dcomms as $dc)
			                                            		<font color="#454ede">
			                                            			@ {{ App\Models\User::where('u_id', $dc->u_id)->value('u_fname') }}
			                                            		</font>
			                                            		@endforeach
			                                            		<br>
			                                            		@endif
			                                            		{{ $comment->comm_text }}
			                                            	</p>
			                                            	@if(Auth::user()->u_id == $comment->u_id)
			                                            		<a data-toggle="modal" data-id="<?=$comment->comm_id;?>" href="#edit_comment" class="btn btn-warning btn-xs pull-right edit-comment" title="Edit Comment"><i class="fa fa-pencil"></i></a>
			                                            	@endif
			                                            	@if(Auth::user()->u_id != $comment->u_id)
			                                            		<a data-toggle="modal" data-id="<?=$comment->comm_id;?>" href="#reply_comment" class="btn btn-primary btn-xs pull-right reply-comment" title="Reply"><i class="fa fa-reply"></i> Reply</a>
			                                            	@endif
			                                            </div>
			                                        </div>
			                                    </li>
											<?php }
										?>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="panel panel-default panel-plain">
							<div class="panel-heading">
								<h1 class="panel-title"><strong>Leave A Comment</strong></h1>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
				                            <label class="col-md-3 control-label">Tag Employees in Comment:</label>
											<div class="col-md-9">								
												<select data-placeholder="Tag Individually" class="chosen-select form-control input-sm" multiple name="commTag[]" id="commTag">
				                        			<option value=""></option>
				                                	@foreach($comm_tags as $comm_tag)
				                                		<option value="<?php echo $comm_tag['u_id']; ?>"><?php echo ucfirst($comm_tag['u_lname']).", ".ucfirst($comm_tag['u_fname']); ?></option>
				                                	@endforeach
				                                </select>
											</div>
				                        </div>
										{!! Form::textarea('comm_text', NULL, ['class' => 'form-control input-sm', 'size' => '10x7']) !!}
										<br>
										<div class="pull-right">
											{!! Form::button('<span class="fa fa-reply"></span> Submit Comment', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
											<a href="{{ url('outgoing') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>

						@if(count($seens) != 0)
						<div class="panel panel-default panel-plain">
							<div class="panel-body">
								<div class="row">							
									<div class="col-md-12" style="overflow-y: scroll; height: 150px;">
										@foreach($seens as $seen)
											<p><i>Last seen by
												<strong>{{ $seen->seen->u_fname }}</strong> on <strong>{{ date('F d, Y H:i A', strtotime($seen->updated_at)) }}</strong><br>
											</i></p>
										@endforeach
									</div>
								</div>
							</div>
						</div>
						@endif
					</div>
				</div>
				@endif

				<!-- MODAL FOR UPDATING COMMENT -->		
				<div class="modal fade" id="editComment" role="dialog">
		            <div class="modal-dialog">
					    <div class="modal-content">
					    	<div class="modal-header">
					        	<button type="button" class="close" data-dismiss="modal">&times;</button>
					        	<h4 class="modal-title">Edit Comment</h4>
					      	</div>
					      	<div class="modal-body">
					      		<div class="row">
									<div class="col-md-12">
										<input class="form-control" type="hidden" value="" name="editID" id="editID">
										{!! Form::textarea('up_comment', NULL, ['class' => 'form-control input-sm', 'size' => '10x7']) !!}
										<br>
										<div class="pull-right">
											{!! Form::button('<span class="fa fa-pencil"></span> Update', ['class' => 'btn btn-warning btn-sm', 'type' => 'submit']) !!}									
										</div>                      	
									</div>
								</div>
					      	</div>
					    </div>
				  	</div>
		        </div>        
		        <script type="text/javascript">
			        $(document).on("click", ".edit-comment", function() {
			            var mt_id = $(this).data('id');
			            $(".modal-body #showid").text(mt_id);
			            $('#editComment').modal('show');
			            document.getElementById("editID").value = mt_id;
			        });
			    </script>

			    <!-- MODAL FOR REPLYING TO A COMMENT -->		
				<div class="modal fade" id="replyComment" role="dialog">
		            <div class="modal-dialog">
					    <div class="modal-content">
					    	<div class="modal-header">
					        	<button type="button" class="close" data-dismiss="modal">&times;</button>
					        	<h4 class="modal-title">Reply</h4>
					      	</div>
					      	<div class="modal-body">
					      		<div class="row">
									<div class="col-md-12">
										<input class="form-control" type="hidden" value="" name="repID" id="repID">
										{!! Form::textarea('rep_comment', NULL, ['class' => 'form-control input-sm', 'size' => '10x7']) !!}
										<br>
										<div class="pull-right">
											{!! Form::button('<span class="fa fa-reply"></span> Submit', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}									
										</div>                      	
									</div>
								</div>
					      	</div>
					    </div>
				  	</div>
		        </div>        
		        <script type="text/javascript">
			        $(document).on("click", ".reply-comment", function() {
			            var rep_id = $(this).data('id');
			            $(".modal-body #showid").text(rep_id);
			            $('#replyComment').modal('show');
			            document.getElementById("repID").value = rep_id;
			        });
			    </script>
			</div>
		</section>
	</section>
	{!! Form::close() !!}
	
	<script type="text/javascript" src="{{ asset('extensions/validation/form-validator/jquery.form-validator.min.js') }}"></script>
	<script src="{{ asset('extensions/events/bootstrap-datepicker.js') }}"></script>
	<script src="{{ asset('extensions/events/bootstrap-tagsinput.min.js') }}"></script>
	<script src="{{ asset('extensions/events/bootstrap-tagsinput-angular.min.js') }}"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("select#tagMode option:selected").each(function() {
		    	recipient_selector();
		  	});

			$("select#tagMode").change(function(){
		    	recipient_selector();
		  	});
		});

		function recipient_selector() {
			var recipientType = parseInt($("#tagMode").val());
		  	if( recipientType == 0 ) {
			    $("#sending_option").val('INDIVIDUAL');
			    $("#tag_individual").css('display', 'inline');
			    $("#tag_group").css('display', 'none');
			    $("#recipient_all").css('display', 'none');
		  	} else if( recipientType == 1 ) {
			    $("#sending_option").val('GROUP');
			    $("#tag_individual").css('display', 'none');
			    $("#tag_group").css('display', 'inline');
			    $("#recipient_all").css('display', 'none');
		  	} else if( recipientType == 2 )  {
			    $("#sending_option").val('ALL');
			    $("#tag_individual").css('display', 'none');
			    $("#tag_group").css('display', 'none');
			    $("#recipient_all").css('display', 'inline');
		  	}
		}
	</script>

	<script type="text/javascript">
		if($(".chosen-select").length) {
			$(".chosen-select").chosen();
		}
		$.validate({
			form: '#groupForm'
		});

		$.get('{{ url("outgoing/addressees") }}', function(data) {
			var senders = [];
			$.each(data, function(i, e) {
				senders.push(e.d_addressee.replace(/\s+/g, " "));
			});
			$.formUtils.suggest($('#addressee'), senders);
		});

		$.get('{{ url("outgoing/companies") }}', function(data) {
			var companies = [];
			$.each(data, function(i, e) {
				companies.push(e.c_name.replace(/\s+/g, " "));
			});
			$.formUtils.suggest($('#company'), companies);
		});

		$(document).ready(function() {
        	var date 	= new Date();	        	
			date.setDate(date.getDate());		

			$('#d_documentdate').datepicker({ 			    
			    format: "yyyy-mm-dd"
			});

			$('#d_datesent').datepicker({ 			    
			    format: "yyyy-mm-dd"
			});
		});
	</script>
@stop