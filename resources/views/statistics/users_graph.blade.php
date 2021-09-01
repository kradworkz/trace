@extends('layouts.main')
@section('content')
	<script type="text/javascript" src="{{ asset('extensions/chart/Chart.js') }}"></script>

	<section id="main-content">
    	<section class="wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
					    <h3 class="page-header"><i class="fa fa-pie-chart"></i><strong><a href="{{ url('user_statistics') }}" class="header-link" title="User Statistics">{{ $data['page'] }}</a></strong></h3>
					    @if($overall != 0)
					    {!! Form::open(['url' => 'user_statistics/'.$user->u_id.'/filter', 'role' => 'form', 'autocomplete' => 'off']) !!}
                        <div class="pull-right">                        	
                            <?php
                                $start_date = App\Models\Document::where('d_status', 'Incoming')->where('d_routingthru', '!=', 0)->orderBy('d_datetimerouted', 'asc')->value('d_datetimerouted');
                                $end_date   = App\Models\Document::where('d_status', 'Incoming')->where('d_routingthru', '!=', 0)->orderBy('d_datetimerouted', 'desc')->value('d_datetimerouted');
                                $first      = date('Y', strtotime($start_date));
                                $last       = date('Y', strtotime($end_date));

                                $yrs      	= array_combine(range(date("Y"), $first), range(date("Y"), $first));
                            ?>
                            Year:
                            {!! Form::select('year', $yrs, $last, ['class' => 'chosen-select form-inline input-sm']) !!}
                            {!! Form::button('<span class="fa fa-filter"></span> Filter', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}
                        </div>
                       	{!! Form::close() !!}
                       	@endif
            		</div>
        		</div>
				<div class="row">
		            <div class="col-lg-12">        
		                <div class="col-lg-12">
		                    <div class="panel panel-default">
		                        <div class="panel-heading panel-heading-2">
		                            <i class="fa fa-bar-chart-o fa-fw"></i>
		                            <strong>
		                                Tracked Documents: {{ $overall }}
		                            </strong>
		                        </div>
		                        <div class="panel-body">
		                        	<?php
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

							                if ( $dcs <= $two ) {							                    
							                    $twodays_count	= 1;
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
		                            	@if($m_two == 0 && $m_five == 0 && $m_nine == 0 && $beyond == 0)
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
		<?php 
        if($overall != 0) { ?>
	        $(function () {
	            var ctx = document.getElementById("myChart").getContext('2d');
				var myChart = new Chart(ctx, {
				  type: 'polarArea',
				  data: {
				    labels: ["Action Taken 1-2 Days", "Action Taken 3-5 Days", "Action Taken 6-9 Days", "More Than 9 Days"],
				    datasets: [{
				      backgroundColor: [
				        "#2ecc71",
				        "#3498db",
				        "#9b59b6",
				        "#e74c3c"
				      ],
				      data: [<?php echo $m_two; ?>, <?php echo $m_five; ?>, <?php echo $m_nine; ?>, <?php echo $beyond; ?>]
				    }]
				  }
				});
	        });
	    <?php } ?>
    </script>
@stop