@extends('layouts.main')
@section('content')
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
							<h3 class="page-header"><i class="fa fa-building"></i><strong>{{ $data['page'] }}</strong><a href="{{ url('company/add') }}" class="header-link" title="Add"> [Add Company]</a></h3>
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<div class="pull-right">
						{!! Form::open(['url' => 'company/search', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off']) !!}
							<div class="form-group">
								<div class="input-group input-group-sm">
									{!! Form::text('search', $search, ['class' => 'form-control', 'placeholder' => 'Search Company']) !!}
									<span class="input-group-btn">
										{!! Form::button('<span class="fa fa-search"></span> Search', ['class' => 'btn btn-primary btn-xs', 'type' => 'submit', 'title' => 'Search']) !!}
										<a href="{{ url('company') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Reset</a>
									</span>
								</div>
							</div>
						{!! Form::close() !!}
					</div>
					@if($companies->isEmpty())
						<div class="panel-body">
							<h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No existing company found.</strong></h5>
						</div>
					@else
						<table class="table">
							<thead>
								<tr>
									<th>Company Name</th>
									<th>Company Address</th>
									<th>E-mail Address</th>
									<th>Phone No.</th>
									<th>Website</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($companies as $company)
								<tr>
									<td>{{ $company->c_name }}</td>
									<td>@if(!empty($company->c_address)) {{ $company->c_address }} @endif</td>
									<td>@if(!empty($company->c_email)) {{ $company->c_email }} @endif</td>
									<td>@if(!empty($company->c_telephone)) {{ $company->c_telephone }} @endif</td>									
									<td>@if(!empty($company->c_website)) <a href="{{ url($company->c_website) }}" title="Visit Website" target="_blank">{{ $company->c_website }}  @endif</a> </td>
									<td class="text-right">
										<a href="{{ url('company/view/'.$company->c_id) }}" class="btn btn-info btn-xs" title="View"><span class="fa fa-eye"></span></a>
										@if(Auth::user()->u_id == $company->u_id)
										<a href="{{ url('company/edit/'.$company->c_id) }}" class="btn btn-warning btn-xs" title="Edit"><span class="fa fa-pencil-square-o"></span></a>
										@endif
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						@if($companies->render())
							<div class="text-center">@if($search == '') {!! $companies->render() !!} @else {!! $companies->appends(Request::only('search'))->render() !!} @endif </div>
						@endif
					@endif
				</div>
			</div>
		</section>
	</section>
@stop