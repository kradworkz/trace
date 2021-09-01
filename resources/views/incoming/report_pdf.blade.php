<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Detailed Report</title>
		<link rel="stylesheet" href="{{ asset('css/main_report.css') }}" type="text/css">
	</head>
	<body>
		<table class="headertable" width="100%">
		    <tr>
			    <td class="dostlogo" width="30%"><img src="{{ asset('images/logos/dost_logo.png') }}" width="70" height="70"/></td>
			    <td style="border:none !important;">
			    <label class="dost">&nbsp;DEPARTMENT OF SCIENCE AND TECHNOLOGY</label><br>
			    <label>&nbsp;INCOMING DOCUMENTS REPORT</label><br>
			    @if($wholeYear == "Yes")
			    &nbsp;Year {{ $year }}
			    @else
			    &nbsp;{{ date('F Y', strtotime($date)) }}
			    @endif
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
				<td class="title">TRACKED</td>
				<td class="title">STATUS</td>				
				<td class="title">DATE COMPLETED</td>
			</tr>
			@foreach($documents as $document)
			<tr>
				<td>{{ $document->d_subject }}</td>
				<td>{{ $document->dtypes->dt_type }}</td>
				<td>{{ $document->d_routingslip }}</td>
				<td>{{ date('F d, Y h:i A', strtotime($document->d_datetimerouted)) }}</td>				
				<td>
					@if($document->d_istrack == 1)
						YES
					@else
						NO
					@endif
				</td>
				<td>
					@if($document->d_istrack == 1)
						@if($document->d_iscompleted == 0)
							<font color="red">OPEN</font>
						@else
							<font color="green">CLOSED</font>
						@endif
					@else
					---
					@endif
				</td>
				<td>
					@if($document->d_istrack == 1)
						@if($document->d_iscompleted == 0)
							<i>Not yet completed.</i>
						@else
							{{ date('F d, Y', strtotime($document->d_datecompleted)) }}
						@endif
					@else
					---
					@endif					
				</td>
			</tr>
			@endforeach
		</table>
	</body>
</html>