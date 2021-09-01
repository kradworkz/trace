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
		}
		
		.modal-dialog{
		    position:absolute;
		    width:800px;
		    margin-left: -400px;
		    margin-top: 40px;
		}
	</style>

	<section id="main-content">
    	<section class="wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="pull-left">
							<h3 class="page-header"><i class="fa fa-calendar"></i><strong> {{ $data['page'] }}</strong> <a href="{{ url('rd_schedule/add') }}" class="header-link" title="Schedule">[Schedule A Meeting</a> | <a href="{{ url('meetings') }}" class="header-link" title="Schedule">List View]</a>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-info alert-dismissable" id="reminder">
		                    <label>LEGENDS: <br/>
		                        <div class="label-alert"><h5><span class="label label-success"><i class="fa fa-sticky-note-o"></i> GREEN&nbsp;&nbsp;&nbsp;&nbsp;</span> <font color="green">- Meeting has been APPROVED.</font></h5></div>
								<div class="label-alert"><h5><span class="label label-warning"><i class="fa fa-sticky-note-o"></i> YELLOW</span> <font color="#CCC106">- Meeting is in PENDING/POSTPONED status.</font></h5></div>
								<div class="label-alert"><h5><span class="label label-danger"><i class="fa fa-sticky-note-o"></i> RED&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <span class="text-danger">- Meeting was CANCELED.</span></h5></div>
		                    </label>
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
					        <div class="panel-body">
								<div class="row">
									<div class="col-md-12">
								        <div class="form-group">
								        	<a id="editUrl" class="btn btn-primary btn-md" title="Edit"><span class="fa fa-pencil-square-o"></span> Edit</a>
										</div>
									</div>
								</div>
							</div>
					    </div>
					</div>
				</div>
			</div>
		</section>
	</section>
@stop