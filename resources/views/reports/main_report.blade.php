@extends('layouts.main')
@section('content')
	<?php
	    $start_date     = App\Models\Transaction::orderBy('created_at', 'asc')->value('created_at');
	    $end_date       = App\Models\Transaction::orderBy('created_at', 'desc')->value('created_at');
	    $first          = date('Y', strtotime($start_date));
	    $last           = date('Y', strtotime($end_date));
	    $years          = array_combine(range(date("Y"), $first), range(date("Y"), $first));
	    $reports 		= App\Models\Report::orderBy('rep_id', 'asc')->get();
    	$accounts 		= App\Models\AccountType::orderBy('at_id', 'asc')->get();
    	$rcodes 		= App\Models\RCCode::orderBy('rc_id', 'asc')->get(); 
	?>

	<link rel="stylesheet" href="{{ asset('extensions/chosen/chosen.css') }}" type="text/css">
	<link rel="stylesheet" href="{{ asset('extensions/chosen/chosen-bootstrap.css') }}" type="text/css">
	<script src="{{ asset('extensions/chosen/chosen.jquery.min.js') }}"></script>

	<section id="main-content">
    	<section class="wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						@if(Session::has('unauthorize'))
				        	<div class="alert alert-danger fade in" id="alert">
				            	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				          		<strong><span class="fa fa-exclamation-circle"></span> {{ Session::get('unauthorize') }}</strong>
				        	</div>
				      	@endif
				      	@if(Session::has('update'))
				        	<div class="alert alert-warning fade in" id="alert">
				            	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				          		<strong><span class="fa fa-check-circle"></span> {{ Session::get('update') }}</strong>
				        	</div>
				      	@endif
				      	@if(Session::has('success'))
				        	<div class="alert alert-success fade in" id="alert">
				            	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					          	<strong><span class="fa fa-check-circle"></span> {{ Session::get('success') }}</strong>
				        	</div>
				        @endif
						<div class="pull-left">
							<h3 class="page-header"><i class="fa fa-file-o"></i><strong>{{ $data['page'] }}</strong> 
						</div>
					</div>
				</div>
				{!! Form::open(['url' => 'reports/show', 'role' => 'form', 'autocomplete' => 'off', 'target'=>'_blank']) !!}
				<div class="panel panel-default panel-plain">
					<div class="panel-heading">
						<h1 class="panel-title"><strong>Report Parameters</strong></h1>
					</div>
					<div class="panel-body">
						<div class="form-row">
							<div class="col-md-12">
								<div class="input-group">
									<span class="input-group-addon">Report Type</span>
									<select class="form-control input-sm" name="reportSearch" id="reportSearch">	                                    
	                                    @foreach($reports as $report)
	                                        <option value="<?php echo $report['rep_id']; ?>"><?php echo $report['rep_name']; ?></option>
	                                    @endforeach
	                                </select>	
								</div>
							</div>
						</div>
						<br><br>
						<div class="form-row">
							<div class="col-md-2">
								<div class="input-group">
									<span class="input-group-addon">Account No.</span>
									<select class="form-control input-sm" name="accountSearch" id="accountSearch">	                                    
	                                    @foreach($accounts as $acct)
	                                        <option value="<?php echo $acct['at_id']; ?>"><?php echo $acct['at_account_code']; ?></option>
	                                    @endforeach
	                                </select>									
								</div>
							</div>
							<div class="col-md-3">
								<div class="input-group">
									<span class="input-group-addon">RC Code</span>
									<select class="chosen-select form-control" id="rcSearch" name="rcSearch">	                                    
	                                    @foreach($rcodes as $rcode)
	                                    	<option value="<?php echo $rcode['rc_id']; ?>"><?php echo $rcode['rc_code']; ?></option>
	                                    @endforeach 
	                                </select>	
								</div>
							</div>
							<div class="col-md-2">
								<div class="input-group">
									<span class="input-group-addon">Year</span>
									{!! Form::select('yearSearch', $years, $last, ['class' => 'chosen-select form-control', 'id' => 'yearSearch', 'name' => 'yearSearch']) !!}
								</div>
							</div>
							<div class="col-md-2">
								<div class="input-group">
									<span class="input-group-addon">Month</span>
									{!! Form::select('monthSearch', [
	                                    '01' => 'January',
	                                    '02' => 'February',
	                                    '03' => 'March',
	                                    '04' => 'April',
	                                    '05' => 'May',
	                                    '06' => 'June',
	                                    '07' => 'July',
	                                    '08' => 'August',
	                                    '09' => 'September',
	                                    '10' => 'October',
	                                    '11' => 'November',                                             
	                                    '12' => 'December'],
	                                    null, ['class' => 'form-control input-sm', 'id' => 'monthSearch', 'name' => 'monthSearch']) 
	                                !!}
								</div>
							</div>
							<div class="col-md-1">
								<div class="input-group">
									<span class="input-group-addon">Day</span>
									{!! Form::select('daySearch', [
	                                    '01' => '01',
	                                    '02' => '02',
	                                    '03' => '03',
	                                    '04' => '04',
	                                    '05' => '05',
	                                    '06' => '06',
	                                    '07' => '07',
	                                    '08' => '08',
	                                    '09' => '09',
	                                    '10' => '10',
	                                    '11' => '11',                                             
	                                    '12' => '12',
	                                    '13' => '13',
	                                    '14' => '14',
	                                    '15' => '15', 
	                                    '16' => '16',
	                                    '17' => '17',
	                                    '18' => '18', 
	                                    '19' => '19',
	                                    '20' => '20',
	                                    '21' => '21',
	                                    '22' => '22',
	                                    '23' => '23',
	                                    '24' => '24',
	                                    '25' => '25',
	                                    '26' => '26',
	                                    '27' => '27',
	                                    '28' => '28',
	                                    '29' => '29',
	                                    '30' => '30',
	                                    '31' => '31'],
	                                    null, ['class' => 'form-control input-sm', 'id' => 'daySearch', 'name' => 'daySearch']) 
	                                !!}
								</div>
							</div>
							<div class="col-md-2">
								{!! Form::button('Search', ['class' => 'btn btn-primary btn-sm', 'type' => 'submit', 'title' => 'Search', 'id'=>'search-btn', 'name'=>'search-btn']) !!}
								<a href="{{ url('reports') }}" class="btn btn-danger btn-sm"><span class="fa fa-ban"></span> Reset</a>
							</div>
						</div>
					</div>
				</div>
				{!! Form::close() !!}
			</div>
		</section>
	</section>

	<script type="text/javascript" src="{{ asset('extensions/validation/form-validator/jquery.form-validator.min.js') }}"></script>
	<script type="text/javascript">
		if($(".chosen-select").length) {
			$(".chosen-select").chosen();
		}
		$.validate({
			form: '#groupForm'
		});
	</script>
@stop