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
						<div class="pull-left">
							<h3 class="page-header"><i class="fa fa-pie-chart"></i><strong>{{ $data['page'] }}</strong></h3>
						</div>
						<div class="pull-right">
							<h3 class="page-header">
								<a title="Print" data-toggle="modal" href="#print_document" class="btn btn-default print-doc"><i class="fa fa-print"></i> Download user Statistics</a>
							</h3>
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<div class="pull-right">
						{!! Form::open(['url' => 'user_statistics/search', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off']) !!}
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
							<h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No user statistics(s) found.</strong></h5>
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
									<th>Total Tracked</th>
									<th>Total Incoming</th>
									<th>Pending/Unactioned</th>
									<th>Unopened</th>									
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($stats as $stat)
								<?php									
									/*$years 	= DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_documents.d_istrack', 1)->where('t_document_routings.u_id', $stat->u_id)->where('t_documents.created_at', 'LIKE', "2019%")->get();
									$dcount = DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_documents.d_istrack', 1)->where('t_document_routings.u_id', $stat->u_id)->where('t_documents.created_at', 'LIKE', "2019%")->count();*/

									$years 	= DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_documents.d_istrack', 1)->where('t_document_routings.u_id', $stat->u_id)->where('t_document_routings.dr_completed', 1)->get();
									$dcount = DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_documents.d_istrack', 1)->where('t_document_routings.u_id', $stat->u_id)->where('t_document_routings.dr_completed', 1)->count();

									$pending= 0;
									$m_two 	= 0;
									$m_five = 0;
									$m_nine = 0;
									$beyond = 0;

									foreach($years as $year) {
										//$dtrs 	= $year->d_datetimerouted;
										$dtrs 	= App\Models\DocumentRouting::where('d_id', $year->d_id)->where('u_id', $stat->u_id)->value('created_at');
                                        $dts    = strtotime($dtrs);

                                        $two    = date('Y-m-d', strtotime($dtrs. ' + 2 days'));
                                        $three  = date('Y-m-d', strtotime($dtrs. ' + 5 days'));
                                        $nine   = date('Y-m-d', strtotime($dtrs. ' + 9 days'));
                                        $adds   = date('Y-m-d', strtotime($dtrs. ' + 9 days'));                                            
                                        $dcs 	= $year->dr_date;

                                        if ( $dcs <= $two ) {                                            
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

									if ( $m_two != 0 ) {
										$first 	= ($m_two / $dcount) * 100;
									} else {
										$first 	= 0;
									}

									if ( $m_five != 0 ) {
										$second = ($m_five / $dcount) * 100;
									} else {
										$second = 0;
									}

									if ( $m_nine != 0 ) {
										$third 	= ($m_nine / $dcount) * 100;
									} else {
										$third 	= 0;
									}

									if ( $beyond != 0 ) {
										$fourth = ($beyond / $dcount) * 100;
									} else {
										$fourth = 0;
									}									
								?>
								<tr>
									<td>{{ $stat->u_lname }}, {{ $stat->u_fname }}</td>
									<td>
										{{ $m_two }} (<?php echo round($first,2)."%"; ?>)<br>										
									</td>
									<td>
										{{ $m_five }} (<?php echo round($second,2)."%"; ?>)<br>
									</td>
									<td>
										{{ $m_nine }} (<?php echo round($third,2)."%"; ?>)<br>
									</td>
									<td>
										{{ $beyond }} (<?php echo round($fourth,2)."%"; ?>)<br>									
									</td>									
									<td><?php echo DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_document_routings.u_id', $stat->u_id)->where('t_documents.d_istrack', 1)->count();?></td>
									<td><?php echo DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('d_status', 'Incoming')->where('t_document_routings.u_id', $stat->u_id)->count(); ?></td>
									<td>{{ App\Models\DocumentRouting::where('dr_status', 1)->where('dr_seen', 1)->where('dr_completed', 0)->where('u_id', $stat->u_id)->count() }}</td>
									<td>{{ App\Models\DocumentRouting::where('dr_status', 1)->where('dr_seen', 0)->where('u_id', $stat->u_id)->count() }}</td>
									<td>
										<a href="{{ url('user_statistics/'.$stat->u_id) }}" class="btn btn-info btn-xs" title="View"><span class="fa fa-eye"></span></a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						@if($stats->render())
							<div class="text-center">@if($stats == '') {!! $stats->render() !!} @else {!! $stats->appends(Request::only('search'))->render() !!} @endif </div>
						@endif
					@endif

					<!-- MODAL FOR PRINTING REPORT -->
					{!! Form::open(['url'=>'user_statistics/download', 'class'=>'form', 'target'=>'_blank']) !!}
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