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
				<td class="title">NAME</td>
				<td class="title">SUBJECT</td>
				<td class="title">ROUTING SLIP</td>				
				<td class="title">NO. OF DAYS PENDING</td>
			</tr>
			@foreach($documents as $document)
			<?php				
				$start_day 		= date('Y-m-d', strtotime($document->created_at));
				$current_day 	= date('Y-m-d');
				$difference		= (strtotime($current_day) - strtotime($start_day));
				$no_of_days 	= floor($difference/(60*60*24));
			?>
			<tr>
				<td>{{ $document->seen->u_fname }} {{ $document->seen->u_lname }}</td>
				<td>{{ $document->docs->d_subject }}</td>
				<td>{{ $document->docs->d_routingslip }}</td>
				<td>{{ $no_of_days }}</td>
			</tr>
			@endforeach
		</table>
	</body>
</html>