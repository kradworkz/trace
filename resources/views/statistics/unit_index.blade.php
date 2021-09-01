@extends('layouts.main')
@section('content')
	<section id="main-content">
    	<section class="wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="pull-left">
							<h3 class="page-header"><i class="fa fa-pie-chart"></i><strong>{{ $data['page'] }}</strong></h3>
						</div>
						<div class="pull-right">
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<div class="pull-right">
						{!! Form::open(['url' => 'unit_statistics/search', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off']) !!}
							<div class="form-group">
								<div class="input-group input-group-sm">
									{!! Form::text('search', $search, ['class' => 'form-control', 'placeholder' => 'Search User']) !!}
									<span class="input-group-btn">
										{!! Form::button('<span class="fa fa-search"></span> Search', ['class' => 'btn btn-primary btn-xs', 'type' => 'submit', 'title' => 'Search']) !!}
										<a href="{{ url('user_statistics') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Reset</a>
									</span>
								</div>
							</div>
						{!! Form::close() !!}
					</div>
					@if($stats->isEmpty())
						<div class="panel-body">
							<h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No unit statistics(s) found.</strong></h5>
						</div>
					@else
						<table class="table">
							<thead>
								<tr>
									<th>Name</th>
									<th>1-2 Days</th>
									<th>3-5 Days</th>
									<th>6-9 Days</th>
									<th>More Than 9 Days</th>
									<th>Pending</th>
									<th>Total Tracked</th>
									<th>Total Incoming</th>
								</tr>
							</thead>
							<tbody>
								@foreach($stats as $stat)
								<?php									
									//$result				= App\Models\User::where('group_id', $stat->group_id)->orderBy('u_id', 'asc')->lists('u_id');
									$result 			= App\Models\GroupMember::where('group_id', $stat->group_id)->orderBy('u_id', 'asc')->pluck('u_id');
									//$tagged_documents 	= DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->whereIn('t_document_routings.u_id', $result)->where('t_documents.d_istrack', 1)->groupBy('t_document_routings.d_id')->get();
									/*$total_incoming		= DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->whereIn('t_document_routings.u_id', $result)->where('t_documents.d_status', 'Incoming')->groupBy('t_document_routings.d_id')->get();									
									$years 				= DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->whereIn('t_document_routings.u_id', $result)->where('t_documents.d_istrack', 1)->groupBy('t_document_routings.d_id')->get();*/

									$total_incoming		= DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_documents.d_status', 'Incoming')->where('t_documents.d_datetimerouted', 'LIKE', "2019%")->whereIn('t_document_routings.u_id', $result)->groupBy('t_document_routings.d_id')->get();									
									$years 				= DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_documents.d_istrack', 1)->where('t_documents.d_iscompleted', 1)->where('t_documents.d_datetimerouted', 'LIKE', "2019%")->whereIn('t_document_routings.u_id', $result)->groupBy('t_document_routings.d_id')->get();
									$doc_count 			= count($years);

									$pending 			= 0;
                                    $m_two 				= 0;
									$m_five 			= 0;
									$m_nine 			= 0;
									$beyond 			= 0;

									foreach($years as $year) {
										$dtrs 			= $year->d_datetimerouted;
                                        $dts    		= strtotime($dtrs);

                                        $two    		= date('Y-m-d', strtotime($dtrs. ' + 2 days'));
                                        $three  		= date('Y-m-d', strtotime($dtrs. ' + 5 days'));
                                        $nine   		= date('Y-m-d', strtotime($dtrs. ' + 9 days'));
                                        $adds   		= date('Y-m-d', strtotime($dtrs. ' + 9 days'));

                                        $dcs 			= $year->d_datecompleted;

                                        if ( $dcs == "0000-00-00") {                                        	
                                        	$pending_count 	= 1;
                                        	$pending 		+= $pending_count;
                                        } elseif ( $dcs <= $two ) {                                            
                                            $twodays_count 	= 1;
                                            $m_two 			+= $twodays_count;
                                        } elseif (($dcs <= $three) && ($dcs > $two)) {                                            
                                            $threedays_count = 1;
                                            $m_five 		 += $threedays_count;
                                        } elseif (($dcs <= $nine) && ($dcs > $three)) {                                            
                                            $ninedays_count = 1;
                                            $m_nine 		+= $ninedays_count;
                                        } elseif ( $dcs > $nine ) {                                            
                                            $beyond_counts 	= 1;
                                            $beyond  		+= $beyond_counts;  
                                        }
									}

									if ( $pending != 0 ) {
										$pend 	= ($pending / $doc_count) * 100;
									} else {
										$pend 	= 0;
									}									

									if ( $m_two != 0 ) {
										$first 	= ($m_two / $doc_count) * 100;
									} else {
										$first 	= 0;
									}

									if ( $m_five != 0 ) {
										$second = ($m_five / $doc_count) * 100;
									} else {
										$second = 0;
									}

									if ( $m_nine != 0 ) {
										$third 	= ($m_nine / $doc_count) * 100;
									} else {
										$third 	= 0;
									}

									if ( $beyond != 0 ) {
										$fourth = ($beyond / $doc_count) * 100;
									} else {
										$fourth = 0;
									}
								?>
								<tr>
									<td>{{ $stat->group_name }}</td>
									<td>{{ $m_two }} (<?php echo round($first,2)."%"; ?>)</td>
									<td>{{ $m_five }} (<?php echo round($second,2)."%"; ?>)</td>
									<td>{{ $m_nine }} (<?php echo round($third,2)."%"; ?>)</td>
									<td>{{ $beyond }} (<?php echo round($fourth,2)."%"; ?>)</td>
									<td>{{ $pending }} (<?php echo round($pend,2)."%"; ?>)</td>
									<td>{{ count($years) }}</td>
									<td>{{ count($total_incoming) }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						@if($stats->render())
							<div class="text-center">@if($stats == '') {!! $stats->render() !!} @else {!! $stats->appends(Request::only('search'))->render() !!} @endif </div>
						@endif
					@endif
				</div>
			</div>
		</section>
	</section>
@stop