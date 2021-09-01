@extends('layouts.main')
@section('content')
	<!-- Calendar -->
    <link rel="stylesheet" href="{{ asset('extensions/fullcalendar/fullcalendar.css') }}" type="text/css">
	<script src="{{ asset('extensions/fullcalender/lib/jquery.min.js') }}"></script>
	<script src="{{ asset('extensions/fullcalendar/lib/moment.min.js') }}"></script>	
	<script src="{{ asset('extensions/fullcalendar/fullcalendar.js') }}"></script>

	<style type="text/css">
		.modal-content{
    		background:#fff;
    		color: black;
		}
		
		.modal-dialog{
		    position:absolute;
		    width:800px;
		    margin-left: -400px;
		    margin-top: 40px;
		    color: black;
		}		
	</style>

	<section id="main-content">
    	<section class="wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="pull-left">
							<h3 class="page-header"><i class="fa fa-calendar"></i><strong> {{ $data['page'] }}</strong> <a href="{{ url('events/add') }}" class="header-link" title="Events">[Add Event</a> | <a href="{{ url('events') }}" class="header-link" title="Events">List View]</a>
						</div>
					</div>
				</div>
				<div class="panel panel-default panel-plain">
					<br><br>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12">
							{!! $calendar->calendar() !!}
							{!! $calendar->script() !!}
							</div>
						</div>						
					</div>
				</div>
				<div id="calendarModal" class="modal fade">
					<div class="modal-dialog wrapper">
					    <div class="modal-content">
					        <div class="modal-header">
					            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
					            <h4 id="modalTitle" class="modal-title"></h4>
					        </div>
					        <div id="modalBody" class="modal-body"></div>
					    </div>
					</div>
				</div>
			</div>
		</section>
	</section>
@stop