@extends('layouts.guest')
@section('content')
	<style type="text/css">
		a { color: #428bca !important; }
		input[type="text"] { width:250px }
	</style>
	<br><br><br><br>
	<div class="centering-container">
        <div class="centering-content">
            <div class="centering-horizontal">
                <div class="jumbotron">
                    <div class="container">
	                    <div class="row"> 
	                    	<div class="col-md-12">
			                	<div class="panel panel-default">
									<div class="panel-body">
										<h1><img src="{{ asset('images/logos/trace_logo.png') }}"/></h1>
										<h4>{{ App\Models\Setting::orderBy('s_id', 'asc')->value('s_sysname') }}</h4>
										<br><br>
										{!! Form::open(['url' => 'login', 'class' => 'form', 'autocomplete' => 'off']) !!}
											<h3>DOWNTIME NOTICE</h3>
											<h4>The TRACE system is currently undergoing maintenance. This website will be restored to its operational status as soon as the maintenance and updates have been completed. Thank you.</h4>
										{!! Form::close() !!}
			                		</div>
			                	</div>
		                	</div>
			            </div>
			        </div>
                </div>
            </div>
        </div>
    </div>
@stop