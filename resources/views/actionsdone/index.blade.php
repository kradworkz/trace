@extends('layouts.main')
@section('content')	
    <section id="main-content">
    	<section class="wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="pull-left">
							<h3 class="page-header"><i class="fa fa-check-square"></i><strong> {{ $data['page'] }}</strong></h3>					
						</div>
						<div class="pull-right">							
							<h3 class="page-header"><i><a href="{{ url('actions/read') }}" class="header-link" title="Mark"> [Mark All As Read]</a></i></h3>
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">					
					@if($actions->isEmpty())
						<div class="panel-body">
							<h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No actions(s) found.</strong></h5>
						</div>
					@else
						<table class="table">
							<thead>
								<tr>
									<th>Action</th>
									<th>Action Done By</th>
									<th>Date/Time Done</th>
									<th>Reference Incoming Document</th>									
								</tr>
							</thead>
							<tbody>
								@foreach($actions as $action)
								<tr>
									<td>{{ $action->comment->comm_text }}</td>
									<td>{{ $action->user->u_fname }} {{ $action->user->u_lname }}</td>
									<td>{{ date('F d, Y h:i A', strtotime($action->created_at)) }}</td>									
									<td>
									<a href="{{ URL::to('incoming/view/'.$action->d_id) }}" target="_blank" >{{ $action->document->d_subject }}</a><br>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						@if($actions->render())
							<div class="text-center">{!! $actions->render() !!}</div>
						@endif
					@endif				
				</div>
			</div>
		</section>
	</section>
@stop