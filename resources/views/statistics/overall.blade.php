@extends('layouts.main')
@section('content')
    <script type="text/javascript" src="{{ asset('extensions/chart/jquery-1.12.4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('extensions/chart/canvasjs.min.js') }}"></script>

    <?php
        if($pending != 0) {
            $pend   = round((($pending/$overall) * 100), 2);    
        } else {
            $pend   = 0;
        }
        
        if($total_ontimes != 0) {
            $ontime = round((($total_ontimes/$overall) * 100), 2);    
        } else {
            $ontime = 0;
        }

        if($total_beyonds != 0) {
            $over   = round((($total_beyonds/$overall) * 100), 2);    
        } else {
            $over   = 0;
        }
                
        $dataPoints = array(
            array("y" => $pend, "name" => "Pending", "color" => "#FFD735", "exploded" => true),
            array("y" => $ontime, "name" => "On-Time Documents", "color" => "#3DB80D"),
            array("y" => $over, "name" => "Beyond The Deadline", "color" => "#D40E1F")
        );

        $header     = "Overall Documents";
        $filename   = "Overall Documents";
    ?>

    <section id="main-content">
    	<section class="wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
					    <h3 class="page-header"><i class="fa fa-pie-chart"></i><strong>{{ $data['page'] }}</strong></h3>
                        @if($overall != 0)
                        {!! Form::open(['url' => 'document_statistics/filter', 'role' => 'form', 'autocomplete' => 'off']) !!}
                        <div class="pull-right">
                            <?php
                                $start_date = App\Models\Document::where('d_status', 'Incoming')->where('d_routingthru', '!=', 0)->orderBy('d_datetimerouted', 'asc')->value('d_datetimerouted');
                                $end_date   = App\Models\Document::where('d_status', 'Incoming')->where('d_routingthru', '!=', 0)->orderBy('d_datetimerouted', 'desc')->value('d_datetimerouted');
                                $first      = date('Y', strtotime($start_date));
                                $last       = date('Y', strtotime($end_date));

                                $years      = array_combine(range(date("Y"), $first), range(date("Y"), $first));
                            ?>
                            Year:
                            {!! Form::select('year', $years, $last, ['class' => 'chosen-select form-inline input-sm']) !!}
                            {!! Form::button('<span class="fa fa-filter"></span> Filter', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit']) !!}                        
                        </div>
                        {!! Form::close() !!}
                        @endif
            		</div>
        		</div>
		        <div class="row">
		            <div class="col-lg-12" id="BarGraph">               
		                {!! Form::open(['url'=>'document_statistics', 'class'=>'form', 'id'=>'frmStatistics']) !!}             
		                <div class="col-lg-12">
		                    <div class="panel panel-default">
		                        <div class="panel-heading panel-heading-2">
		                            <i class="fa fa-bar-chart-o fa-fw"></i>
		                            <strong>
		                                Tracked Documents: {{ $overall }}
		                            </strong>
		                        </div>
		                        <div id="container">
                                    @if($pending == 0 && $total_ontimes == 0 && $total_beyonds == 0)
                                    <h5 class="text-danger"><strong><i class="fa fa-info-circle"></i> No document(s) found for this year.</strong></h5>
                                    @else
                                    <div id="chartContainer"></div>
                                    @endif
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
                var chart = new CanvasJS.Chart("chartContainer",
                {
                    theme: "theme2",
                    title:{
                        text: "Overall Documents"
                    },
                    exportFileName: "Overall Document Statistics",
                    exportEnabled: true,
                    animationEnabled: true,     
                    data: [
                    {       
                        type: "pie",
                        showInLegend: true,
                        toolTipContent: "{name}: <strong>{y}%</strong>",
                        indexLabel: "{name} {y}%",
                        dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                    }]
                });
                chart.render();
            });
        <?php } ?>
    </script>
    {!! Form::close() !!}
@stop