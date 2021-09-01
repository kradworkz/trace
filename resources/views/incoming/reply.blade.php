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

	{!! Form::open(['url'=>'incoming/reply/'.$document->d_id, 'class'=>'form-horizontal', 'autocomplete'=>'off', 'id'=>'groupForm', 'files'=>'true']) !!}
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
							<h3><strong><a href="{{ url('incoming') }}">{{ $data['page'] }}</a> | {{ $option }}</strong></h3>
						</div>
					</div>
				</div>
				<!-- ATTACHMENT DETAILS FOR DC -->
				<div class="panel panel-default panel-plain">			
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Attachment Details</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								{!! Form::hidden('id', $id) !!}						
								<div class="form-group {{ ($errors->has('d_subject') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Subject: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('d_subject', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#d_subject', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="d_subject" class="align-text"> {{ $errors->first('d_subject') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('dtypes') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Document Type: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::select('dtypes', $dtypes, $option == 'Edit' || $option == 'View' ? $document->dt_id : NULL, ['class' => 'form-control input-sm chosen-select', 'data-validation' => 'required', $option == 'View' ? 'readonly' : '']) !!}</div>									
									<span class="text-danger"><small><strong id="dtypes" class="align-text"> {{ $errors->first('dtypes') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('d_file') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">File Attachments: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::file('d_file[]', ['class' => 'align-form', 'multiple'=>true]) !!}</div>
									<span class="text-danger"><small><strong id="d_file" class="align-text"> {{ $errors->first('d_file') }}</strong></small></span>
								</div>							
								<div class="form-group {{ ($errors->has('d_documentdate') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Document Date: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('d_documentdate', null, ['class' => 'form-control input-sm', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#d_documentdate', 'id' => 'd_documentdate', 'maxlength' => '10', 'placeholder' => 'Document Date', 'readonly']) !!}</div>
									<span class="text-danger"><small><strong id="d_documentdate" class="align-text"> {{ $errors->first('d_documentdate') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('d_datesent') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Date Sent: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('d_datesent', null, ['class' => 'form-control input-sm', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#d_datesent', 'id' => 'd_datesent', 'maxlength' => '10', 'placeholder' => 'Date Received', 'readonly']) !!}</div>
									<span class="text-danger"><small><strong id="d_datesent" class="align-text"> {{ $errors->first('d_datesent') }}</strong></small></span>
								</div>	
								<div class="form-group {{ ($errors->has('d_addressee') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Addressee: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('d_addressee', null, ['class' => 'form-control input-sm', 'id' => 'addressee', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#d_addressee']) !!}</div>
									<span class="text-danger"><small><strong id="d_addressee" class="align-text"> {{ $errors->first('d_addressee') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('c_name') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">Company: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('c_name', null, ['class' => 'form-control input-sm align-form', 'id' => 'company', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#c_name', $option == 'View' ? 'readonly' : '']) !!}</div>									
									<span class="text-danger"><small><strong id="c_name" class="align-text"> {{ $errors->first('c_name') }}</strong></small></span>
								</div>
								<div class="form-group {{ ($errors->has('d_keywords') ? ' has-error ' : '') }} has-feedback">
		                            <label class="col-md-2 control-label">Keywords: <span class="text-danger">*</span></label>
		                            <div class="col-md-8"><input class="form-control input-sm align-form" data-role="tagsinput" data-validation="required" data-validation-error-msg-container="#d_keywords" type="text" value="" name="d_keywords" id="d_keywords" placeholder="Separate by comma"></div>		                            
		                            <span class="text-danger"><small><strong id="d_keywords" class="align-text"> {{ $errors->first('d_keywords') }}</strong></small></span>
		                        </div>
		                        <div class="form-group">
		                        	<label class="col-md-2 control-label">Routing Slip</label>
		                        	<div class="col-md-8">{!! Form::text('d_routingslip', $routingslip, ['class' => 'form-control input-sm align-form', 'readonly']) !!}</div>
		                        </div>		                     
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">			
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Incoming Reference Details</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="col-md-2 control-label">Reference Slip:</label>
									<div class="col-md-8">{!! Form::text('routingslip', $document->d_routingslip, ['class' => 'form-control input-sm align-form', 'readonly']) !!}</div>									
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Routed Thru:</label>
									<?php
										$fname = App\Models\User::where('u_id', $document->d_routingthru)->value('u_fname');
										$lname = App\Models\User::where('u_id', $document->d_routingthru)->value('u_lname');
									?>
									<div class="col-md-8">{!! Form::text('routedthru', $fname." ".$lname, ['class' => 'form-control input-sm align-form', 'readonly']) !!}</div>
								</div>
								<div class="form-group">
									<label class="col-md-2 control-label">Notified Employees</label>
									<?php $notifications = App\Models\DocumentRouting::where('d_id', $document->d_id)->where('u_id', '!=', $document->d_routingthru)->get(); ?>
									<div class="col-md-8">
										@foreach($notifications as $notification)
											{{ $notification->seen->u_fname }} {{ $notification->seen->u_lname }}<br>
										@endforeach
									</div>									
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Notification Details</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">						
								<div class="form-group">
		                            <label class="col-md-2 control-label">Tagging Mode:</label>
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
			                            <label class="col-md-2 control-label">Notify:</label>
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
			                            <label class="col-md-2 control-label">Notify:</label>
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
							</div>
						</div>
					</div>					
				</div>
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Remarks</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">						
								<div class="form-group">
									<label class="col-md-2 control-label">Remarks:</label>
									<div class="col-md-8">{!! Form::textarea('d_remarks', NULL, ['class' => 'form-control input-sm', 'size' => '10x5']) !!}</div>
								</div>
								<div class="form-group">
									<div class="col-md-3 col-md-offset-2">
										{!! Form::button('<span class="fa fa-reply"></span> Save Reply', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
										<a href="{{ url('incoming') }}" class="btn btn-danger btn-sm"><span class="fa fa-undo"></span> Back To Incoming</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
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