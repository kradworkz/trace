@extends('layouts.main')
@section('content')
	<!--main content start-->
    <section id="main-content">
    	<section class="wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="pull-left">
							<h3 class="page-header"><i class="fa fa-comments"></i><strong> {{ $data['page'] }}</strong></h3>
						</div>
						<div class="pull-right">							
							<h3 class="page-header"><i><a href="{{ url('document_comments/read') }}" class="header-link" title="Mark"> [Mark All As Read]</a></i></h3>
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					@if($comments->isEmpty())
						<div class="panel-body">
							<h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No unseen comment(s) found.</strong></h5>
						</div>
					@else
						<table class="table">
							<thead>
								<tr>
									<th>Subject</th>									
									<th>Routing Slip</th>
									<th>Unseen Comments</th>
									<th>Total Comments</th>									
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($comments as $comment)
								<tr>
									<td>{{ $comment->subject->d_subject }}</td>									
									<td>{{ $comment->subject->d_routingslip }}</td>
									<td>{{ App\Models\DCommentSeen::where('d_id', $comment->d_id)->where('u_id', Auth::user()->u_id)->where('dcs_seen', 0)->count() }}</td>
									<td>{{ App\Models\Comment::where('comm_document', 1)->where('comm_reference', $comment->d_id)->count() }}</td>									
									<td class="text-right">
										<?php $status = App\Models\Document::where('d_id', $comment->d_id)->value('d_status'); ?>
										@if($status == 'Incoming')
										<a href="{{ url('incoming/view/'.$comment->d_id) }}" class="btn btn-success btn-xs" title="View"><span class="fa fa-eye"></span></a>
										@elseif($status == 'Outgoing')
										<a href="{{ url('outgoing/view/'.$comment->d_id) }}" class="btn btn-success btn-xs" title="View"><span class="fa fa-eye"></span></a>
										@endif
									</td>
								</tr>								
								@endforeach
							</tbody>
						</table>
						@if($comments->render())
							<div class="text-center">{!! $comments->render() !!}</div>
						@endif
					@endif
				</div>
			</div>
		</section>
	</section>
@stop