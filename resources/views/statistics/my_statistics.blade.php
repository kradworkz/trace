@extends('layouts.main')
@section('content')
    <script type="text/javascript" src="{{ asset('extensions/chart/jquery-1.12.4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('extensions/chart/canvasjs.min.js') }}"></script>

    <?php
        $pending= 0;
        $m_two  = 0;
        $m_five = 0;
        $m_nine = 0;
        $beyond = 0;

        foreach($years as $year) {                                          
            $dtrs   = $year->d_datetimerouted;
            $dts    = strtotime($dtrs);

            $two    = date('Y-m-d', strtotime($dtrs. ' + 2 days'));
            $three  = date('Y-m-d', strtotime($dtrs. ' + 5 days'));
            $nine   = date('Y-m-d', strtotime($dtrs. ' + 9 days'));
            $adds   = date('Y-m-d', strtotime($dtrs. ' + 9 days'));
            $dcs    = $year->dr_date;

            if ( $dcs == "0000-00-00") {                
                $pending_count  = 1;
                $pending        += $pending_count;
            } elseif ( $dcs <= $two ) {                
                $twodays_count  = 1;
                $m_two          += $twodays_count;
            } elseif (($dcs <= $three) && ($dcs > $two)) {                
                $threedays_count = 1;
                $m_five          += $threedays_count;
            } elseif (($dcs <= $nine) && ($dcs > $three)) {                
                $ninedays_count = 1;
                $m_nine         += $ninedays_count;
            } elseif ( $dcs > $nine ) {                
                $beyond_counts  = 1;
                $beyond         += $beyond_counts;
            }            
        }

        if ( $pending != 0 ) {
            $pend   = round((($pending / $dcount) * 100), 2);
        } else {
            $pend   = 0;
        }

        if ( $m_two != 0 ) {
            $first  = round((($m_two / $dcount) * 100), 2);
        } else {
            $first  = 0;
        }

        if ( $m_five != 0 ) {
            $second = round((($m_five / $dcount) * 100), 2);
        } else {
            $second = 0;
        }

        if ( $m_nine != 0 ) {
            $third  = round((($m_nine / $dcount) * 100), 2);
        } else {
            $third  = 0;
        }

        if ( $beyond != 0 ) {
            $fourth = round((($beyond / $dcount) * 100), 2);
        } else {
            $fourth = 0;
        }
    ?>

    <?php
        $dataPoints = array(
            array("y" => $pend, "name" => "Pending", "color" => "#FFD735", "exploded" => true),
            array("y" => $first, "name" => "1-2 Days", "color" => "#3DB80D"),
            array("y" => $second, "name" => "3-5 Days", "color" => "#1D23C4"),
            array("y" => $third, "name" => "6-9 Days", "color" => "#C75D1F"),
            array("y" => $fourth, "name" => "Beyond The Deadline", "color" => "#D40E1F")
        );

        $name       = Auth::user()->u_fname." ".Auth::user()->u_lname;
        $filename   = Auth::user()->u_fname."".Auth::user()->u_lname."_"."Statistics";
    ?>

    <section id="main-content">
        <section class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-header"><i class="fa fa-pie-chart"></i><strong><a href="{{ url('user_statistics') }}" class="header-link" title="User Statistics">{{ $data['page'] }}</a></strong></h3>
                        {!! Form::open(['url' => 'my_statistics/filter', 'role' => 'form', 'autocomplete' => 'off']) !!}
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
                    </div>
                </div>
                <div class="row">                    
                    <div id="chartContainer"></div>
                </div>
            </div>
        </section>
    </section>

    <script type="text/javascript">
        $(function () {
            var chart = new CanvasJS.Chart("chartContainer",
            {
                theme: "theme2",
                title:{
                    text: "<?php echo $name; ?>"
                },
                exportFileName: "<?php echo $filename; ?>",
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
    </script>
@stop