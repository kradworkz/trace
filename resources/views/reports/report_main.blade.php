<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Detailed Pending Report</title>
		<link rel="stylesheet" href="{{ asset('css/main_report.css') }}" type="text/css">
	</head>
	<body>
		<table class="headertable" width="100%">
		    <tr>
			    <td class="dostlogo" width="30%"><img src="{{ asset('images/logos/dost_logo.png') }}" width="70" height="70"/></td>
			    <td style="border:none !important;">
			    <label class="dost">&nbsp;DEPARTMENT OF SCIENCE AND TECHNOLOGY</label><br>
			    <label>&nbsp;PENDING DOCUMENTS REPORT</label><br>
			    &nbsp;as of {{ date('F Y', strtotime(Carbon\Carbon::now())) }}
			    </td>
		    </tr>
		</table>
		<br>

		<table class="maintable" width="100%">
			<tr>
				<td class="title">SUBJECT</td>				
				<td class="title">DOCUMENT TYPE</td>
				<td class="title">ROUTING SLIP</td>
				<td class="title">DATE/TIME ROUTED</td>				
				<td class="title">NO. OF PERSONS</td>
				<td class="title">PENDING</td>
			</tr>
			@foreach($documents as $document)
			<?php
				$total_routed 	= App\Models\DocumentRouting::where('d_id', $document->d_id)->count();
				$finished 		= App\Models\DocumentRouting::where('d_id', $document->d_id)->where('dr_completed', 1)->count();
				$pen 			= App\Models\DocumentRouting::where('d_id', $document->d_id)->where('dr_completed', 0)->count();
				$employees 		= App\Models\DocumentRouting::where('d_id', $document->d_id)->where('dr_completed', 0)->get();
			?>
			<tr>
				<td>{{ $document->d_subject }}</td>
				<td>{{ $document->dtypes->dt_type }}</td>
				<td>{{ $document->d_routingslip }}</td>
				<td>{{ date('F d, Y h:i A', strtotime($document->d_datetimerouted)) }}</td>
				<td>
					<font color="green">{{ $finished }} / {{ $total_routed }}</font><br>
					<font color="red">{{ $pen }} / {{ $total_routed }}</font>
				</td>
				<td>					
					@foreach($employees as $employee)
						@if($employee->dr_seen == 1 && $employee->dr_completed == 1)
						<font color="green">
						@elseif($employee->dr_seen == 1 && $employee->dr_completed == 0)
						<font color="orange">
						@elseif($employee->dr_seen == 0 && $employee->dr_seen == 0)
						<font color="red">
						@endif
						{{ $employee->seen->u_fname }} {{ $employee->seen->u_lname }};
						</font>
					@endforeach					
				</td>
			</tr>
			@endforeach
		</table>
	</body>
</html>