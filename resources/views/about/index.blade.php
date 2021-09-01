@extends('layouts.main')
@section('content')
	<style type="text/css">
		.about {
			color: black;
			text-align: justify;
		}
	</style>
	<section id="main-content">
    	<section class="wrapper">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="pull-left">
							<h3 class="page-header"><i class="fa fa-info-circle"></i><strong>{{ $data['page'] }}</strong></h3>
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<div class="panel-body">
						<center>
							<img src="{{ asset('images/logos/trace_logo.png') }}"/><br>
							<font size="5">T</font>racking, <font size="5">R</font>etrieval, <font size="5">A</font>rchiving of <font size="5">C</font>ommunications for <font size="5">E</font>fficiency
							<br><br>
							<label class="about"><font size="5">I</font>mplemented in June 2016, TRACE or Tracking, Retrieval and Archiving of Communications for Efficiency is a web-based platform for the storage and tracking of incoming and outgoing 
							organizational communications. It  ensures that all communications are acted upon accordingly and facilitates interaction between the top management, supervisor and employee in the 
							delegation and performance of tasks relative to the communication. TRACE also digitizes all communications thereby making it easier for staff to check and act on those assigned to 
							them wherever and whenever. Productivity tracking-related statistics which informs the top management of the efficiency of a functional unit and employee with respect to the speed 
							in taking action to an assigned task, is also included in TRACE.
							</label>
						</center>									
					</div>
				</div>
			</div>
		</section>
	</section>
@stop