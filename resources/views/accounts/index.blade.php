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
							<h3 class="page-header"><i class="fa fa-users"></i><strong>
								Users</strong> @if(Auth::user()->u_administrator == 1)<a href="{{ url('accounts/add') }}" class="header-link" title="Add Account">[Add Account]</a>@endif
							</h3>
						</div>
						<div class="pull-right">
							<h3 class="page-header">
								<a title="Print" data-toggle="modal" href="#print_document" class="btn btn-default print-doc"><i class="fa fa-print"></i> Download User Logs</a>
							</h3>
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					@if(Request::segment(2) != 'unapproved')
					<div class="pull-right">
						{!! Form::open(['url' => 'accounts/search', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off']) !!}
							<div class="form-group">
								<div class="input-group input-group-sm">
									{!! Form::text('search', $search, ['class' => 'form-control', 'placeholder' => 'Search User']) !!}
									<span class="input-group-btn">
										{!! Form::button('<span class="fa fa-search"></span> Search', ['class' => 'btn btn-primary btn-xs', 'type' => 'submit', 'title' => 'Search']) !!}
										<a href="{{ url('accounts') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Reset</a>
									</span>
								</div>
							</div>
						{!! Form::close() !!}
					</div>
					@endif

					@if($users->isEmpty())
						<div class="panel-body">
							<h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No user(s) found.</strong></h5>
						</div>
					@else
						<table class="table">
							<thead>
								<tr>
									<th>Name</th>
									<th>E-mail Address</th>
									<th>Mobile Number</th>							
									<th>Unit/Division</th>
									<th>Position</th>
									@if(Auth::user()->u_administrator == 1)<th class="text-center">Is Admin</th>@endif
									<th class="text-center">Status</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($users as $user)
								<tr>
									<td>{{ $user->u_lname }}, {{ $user->u_fname }}</td>
									<td>{{ $user->u_email }}</td>
									<td>{{ $user->u_mobile }}</td>
									<td>{{ $user->groups->group_name }}</td>
									<td>{{ $user->u_position }}</td>
									@if(Auth::user()->u_administrator == 1)
									<td class="text-center">
										@if($user->u_administrator == 1)
											<font color="green"><i class="fa fa-check-circle"></i></font>
										@else
											<font color="red"><i class="fa fa-times-circle"></i></font>
										@endif
									</td>
									@endif
									<td class="text-center">
										@if($user->u_active == 1)
											<font color="green"><i class="fa fa-check-circle"></i></font>
										@elseif($user->u_active == 2)
											<font color="red"><i class="fa fa-times-circle"></i></font>
										@else
											<font color="yellow"><i class="fa fa-minus-circle"></i></font>
										@endif
									</td>
									<td class="text-right">
										<a href="{{ url('accounts/view/'.$user->u_id) }}" class="btn btn-success btn-xs" title="View"><span class="fa fa-eye"></span></a>
										@if(Auth::user()->u_administrator == 1)
										<a href="{{ url('accounts/reset/'.$user->u_id) }}" class="btn btn-primary btn-xs" title="Reset"><span class="fa fa-unlock"></span></a>
										<a href="{{ url('accounts/edit/'.$user->u_id) }}" class="btn btn-warning btn-xs" title="Edit"><span class="fa fa-edit"></span></a>
										@if($user->u_active == 1)
										<a href="{{ url('accounts/deactivate/'.$user->u_id) }}" class="btn btn-info btn-xs" title="Deactivate"><span class="fa fa-times"></span></a>
										@endif
										@endif
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						@if($users->render())
							<div class="text-center">@if($search == '') {!! $users->render() !!} @else {!! $users->appends(Request::only('search'))->render() !!} @endif </div>
						@endif
					@endif

					<!-- MODAL FOR PRINTING REPORT -->
					{!! Form::open(['url'=>'accounts/user_logs', 'class'=>'form', 'target'=>'_blank']) !!}
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
												$start_date = App\Models\User::orderBy('created_at', 'asc')->value('created_at');
												$end_date 	= App\Models\User::orderBy('created_at', 'desc')->value('created_at');
												$first 		= date('Y', strtotime($start_date));
												$last 		= date('Y', strtotime($end_date));
												$years      = array_combine(range(date("Y"), $first), range(date("Y"), $first));
											?>
											Year:
											{!! Form::select('year', $years, $last, ['class' => 'chosen-select form-control input-sm']) !!}
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