<!DOCTYPE html>
<html><head>
		<meta charset="utf-8">
		<title>Detailed Report</title>
		<link rel="stylesheet" href="{{ asset('css/main_report.css') }}" type="text/css">
	</head><body>
		<table class="headertable" width="100%">
		    <tr>
			    <td class="dostlogo" width="30%"><img src="{{ asset('images/logos/dost_logo.png') }}" width="70" height="70"/></td>
			    <td style="border:none !important;">
			    <label class="dost">&nbsp;DEPARTMENT OF SCIENCE AND TECHNOLOGY</label><br>
			    <label>&nbsp;OUTGOING DOCUMENTS REPORT</label><br>
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
				<td class="title">DATE/TIME CREATED</td>
				@if(Auth::user()->ug_id == 3)
				<td class="title">CREATED BY</td>
				<td class="title">KEYWORDS</td>
				@else
				<td class="title">FILES UPLOADED</td>
				@endif				
			</tr>
			@foreach($documents as $document)
			<tr>
				<td>{{ $document->d_subject }}</td>
				<td>{{ $document->dtypes->dt_type }}</td>
				<td>{{ $document->d_routingslip }}</td>
				<td>{{ date('F d, Y h:i A', strtotime($document->created_at)) }}</td>
				@if(Auth::user()->ug_id == 3)
				<td>{{ $document->user->u_fname }} {{ $document->user->u_lname }}</td>
				<td>{{ $document->d_keywords }}</td>
				@else
				<td>
					<?php $files = App\Models\DocumentAttachment::where('d_id', $document->d_id)->get(); ?>
					@foreach( $files as $file )                                		
        				@if( $file->da_file != "" )
        					<?php $attachment = $file['da_file']; ?>
        					@if ( is_file($attachment) )                                            
        						{{ basename($file->da_file) }}<br>
        					@else
        						File missing.<br>
        					@endif
        				@else
        					No file uploaded.
        				@endif
        			@endforeach
				</td>
				@endif
			</tr>
			@endforeach
		</table>
	</body></html>