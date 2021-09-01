@extends('layouts.main')
@section('content')
	<style type="text/css">
		.modal-dialog {    
		    margin-left: 10% !important;
		  margin-right: 10% !important;
		}
	</style>
	
	{!! Form::model($usergroup, ['url' => 'user_groups/save', 'class' => 'form-horizontal', 'autocomplete' => 'off', 'id' => 'groupForm']) !!}
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
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong><a href="{{ url('user_groups') }}">{{ $data['page'] }}</a> | {{ $option }}</strong></strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								{!! Form::hidden('id', $id) !!}
								<div class="form-group {{ ($errors->has('ug_name') ? ' has-error ' : '') }} has-feedback">
									<label class="col-md-2 control-label">User Group Name: <span class="text-danger">*</span></label>
									<div class="col-md-8">{!! Form::text('ug_name', null, ['class' => 'form-control input-sm align-form', 'data-validation' => 'required', 'data-validation-error-msg-container' => '#ug_name', $option == 'View' ? 'readonly' : '']) !!}</div>
									<span class="text-danger"><small><strong id="ug_name" class="align-text"> {{ $errors->first('ug_name') }}</strong></small></span>
								</div>
								<div class="form-group {{ $option == 'View' ? 'hidden' : ''}}">
									<div class="col-md-3 col-md-offset-2">
										{!! Form::button('<span class="fa fa-save"></span> Save', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
										<a href="{{ url('user_groups') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Cancel</a>
									</div>
								</div>						
							</div>
						</div>
					</div>
				</div>

				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>User Group Rights</strong></h1>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">						
								<table class="table">
									<thead>
										<tr>
											<th>Category</th>
											<th class="text-center">View</th>
											<th class="text-center">Add</th>
											<th class="text-center">Edit</th>
											<th class="text-center">Delete</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										@foreach($rights as $right)
										<tr>									
											<td>{{ $right->urights->ur_name }}</td>
											<td class="text-center"><input name="view" type="checkbox" value="1" @if($right->ugr_view == 1) checked="checked" @endif disabled></td>
											<td class="text-center"><input name="add" type="checkbox" value="1" @if($right->ugr_add == 1) checked="checked" @endif disabled></td>
											<td class="text-center"><input name="edit" type="checkbox" value="1" @if($right->ugr_edit == 1) checked="checked" @endif disabled></td>
											<td class="text-center"><input name="delete" type="checkbox" value="1" @if($right->ugr_delete == 1) checked="checked" @endif disabled></td>
											<td><a data-toggle="modal" data-id="<?=$right->ugr_id;?>" href="#edit_rights" class="btn btn-warning btn-xs pull-right edit-rights" title="Edit Rights"><i class="fa fa-pencil"></i></a></td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>			

					<div class="modal fade" id="editRights" role="dialog">
				        <div class="modal-dialog">									    
						    <div class="modal-content">
						    	<div class="modal-header">
						        	<button type="button" class="close" data-dismiss="modal">&times;</button>
						        	<h4 class="modal-title">Edit</h4>
						      	</div>
						      	<div class="modal-body">
						      		<input class="form-control" type="hidden" value="" name="editID" id="editID">
						      		<div class="row">
										<div class="col-md-12">
											<table class="table">
												<thead>
													<tr>													
														<th class="text-center">View</th>
														<th class="text-center">Add</th>
														<th class="text-center">Edit</th>
														<th class="text-center">Delete</th>													
													</tr>
												</thead>
												<tbody>												
													<tr>																						
														<td class="text-center"><input name="ugr_view" type="checkbox" value="1"></td>
														<td class="text-center"><input name="ugr_add" type="checkbox" value="1"></td>
														<td class="text-center"><input name="ugr_edit" type="checkbox" value="1"></td>
														<td class="text-center"><input name="ugr_delete" type="checkbox" value="1"></td>													
													</tr>												
												</tbody>
											</table>
											<div class="form-group">
												<div class="pull-right">
													{!! Form::button('<span class="fa fa-save"></span> Update', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}												
												</div>
											</div>
										</div>
									</div>							
						      	</div>
						    </div>
					  	</div>
				    </div>
				    <script type="text/javascript">
				        $(document).on("click", ".edit-rights", function() {
				            var mt_id = $(this).data('id');
				            $(".modal-body #showid").text(mt_id);
				            $('#editRights').modal('show');		                                
				            document.getElementById("editID").value = mt_id;                    		                    
				        });
				    </script>
				</div>
			</div>
		</section>
	</section>
	{!! Form::close() !!}

	<!-- MODAL FOR UPDATING RIGHTS -->
	{!! Form::open(['url'=>'incoming/update_comment', 'class'=>'form']) !!}

    {!! Form::close() !!}
@stop