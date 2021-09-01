@extends('layouts.main')
@section('content')
	<script type="text/javascript" src="{{ asset('extensions/chart/Chart.js') }}"></script>

	<section id="main-content">
    	<section class="wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
					    <h3 class="page-header"><i class="fa fa-pie-chart"></i><strong><a href="{{ url('unit_statistics') }}" class="header-link" title="User Statistics">{{ $data['page'] }}</a> || {{ $group->group_name }} ({{ $group->groupmembers->count() }} members)</strong></h3>
            		</div>
        		</div>
				<div class="row">
		            <div class="col-lg-12">        
		                <div class="col-lg-12">
		                    <div class="panel panel-default">
		                        <div class="panel-heading panel-heading-2">
		                            <i class="fa fa-bar-chart-o fa-fw"></i>
		                            <strong>
		                                Tracked Documents: {{ count($overall) }}
		                            </strong>
		                        </div>
		                        <div class="panel-body">
		                        	<?php
		                        		$pending= 0;
										$m_two 	= 0;
										$m_five = 0;
										$m_nine = 0;
										$beyond = 0;

										foreach($years as $year) {											
											$dtrs 	= $year->d_datetimerouted;
							                $dts    = strtotime($dtrs);

							                $two    = date('Y-m-d', strtotime($dtrs. ' + 2 days'));
							                $three  = date('Y-m-d', strtotime($dtrs. ' + 5 days'));
							                $nine   = date('Y-m-d', strtotime($dtrs. ' + 9 days'));
							                $adds   = date('Y-m-d', strtotime($dtrs. ' + 9 days'));
							                $dcs 	= $year->dr_date;

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
									?>
		                            <div id="container">
		                            	@if($pending == 0 && $m_two == 0 && $m_five == 0 && $m_nine == 0 && $beyond == 0)
		                            	<h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No document(s) routed to this user. No statistics to be shown.</strong></h5>
		                            	@else
		                            	<canvas id="myChart" width="500" height="500"></canvas>
		                            	@endif                                        
                                    </div>                                    
		                        </div>                        
		                    </div>
		                </div>		                
		            </div>
		        </div>
			</div>
		</section>
	</section>

	<script type="text/javascript">
        $(function () {
            var ctx = document.getElementById("myChart").getContext('2d');
			var myChart = new Chart(ctx, {
			  type: 'polarArea',
			  data: {
			    labels: ["Action Taken 1-2 Days", "Action Taken 3-5 Days", "Action Taken 6-9 Days", "More Than 9 Days", "Pending"],
			    datasets: [{
			      backgroundColor: [
			        "#2ecc71",
			        "#3498db",
			        "#9b59b6",
			        "#e74c3c",
			        "#f1c40f",
			      ],
			      data: [<?php echo $m_two; ?>, <?php echo $m_five; ?>, <?php echo $m_nine; ?>, <?php echo $beyond; ?>, <?php echo $pending; ?>]
			    }]
			  }
			});
        });
    </script>
@stop