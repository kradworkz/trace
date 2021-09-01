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
			    <label>&nbsp;TRACE USER LOGS</label><br>
			    &nbsp;Year: {{ $year }}
			    </td>
		    </tr>
		</table>
		<br>

		<table class="maintable" width="100%">
			<tr>
				<td class="title">EMPLOYEE NAME</td>
				<td class="title">JAN</td>
				<td class="title">FEB</td>
				<td class="title">MAR</td>
				<td class="title">APR</td>
				<td class="title">MAY</td>
				<td class="title">JUN</td>
				<td class="title">JUL</td>
				<td class="title">AUG</td>
				<td class="title">SEP</td>
				<td class="title">OCT</td>
				<td class="title">NOV</td>
				<td class="title">DEC</td>
				<td class="title">TOTAL</td>
				<td class="title">LAST LOG IN</td>
			</tr>
			@foreach($users as $user)
			<tr>
				<td>{{ $user->u_fname }} {{ $user->u_lname }}</td>
				<td>{{ App\Models\UserLog::where('u_id', $user->u_id)->where('created_at', 'LIKE', "$year-01-%")->count() }}</td>
				<td>{{ App\Models\UserLog::where('u_id', $user->u_id)->where('created_at', 'LIKE', "$year-02-%")->count() }}</td>
				<td>{{ App\Models\UserLog::where('u_id', $user->u_id)->where('created_at', 'LIKE', "$year-03-%")->count() }}</td>
				<td>{{ App\Models\UserLog::where('u_id', $user->u_id)->where('created_at', 'LIKE', "$year-04-%")->count() }}</td>
				<td>{{ App\Models\UserLog::where('u_id', $user->u_id)->where('created_at', 'LIKE', "$year-05-%")->count() }}</td>
				<td>{{ App\Models\UserLog::where('u_id', $user->u_id)->where('created_at', 'LIKE', "$year-06-%")->count() }}</td>
				<td>{{ App\Models\UserLog::where('u_id', $user->u_id)->where('created_at', 'LIKE', "$year-07-%")->count() }}</td>
				<td>{{ App\Models\UserLog::where('u_id', $user->u_id)->where('created_at', 'LIKE', "$year-08-%")->count() }}</td>
				<td>{{ App\Models\UserLog::where('u_id', $user->u_id)->where('created_at', 'LIKE', "$year-09-%")->count() }}</td>
				<td>{{ App\Models\UserLog::where('u_id', $user->u_id)->where('created_at', 'LIKE', "$year-10-%")->count() }}</td>
				<td>{{ App\Models\UserLog::where('u_id', $user->u_id)->where('created_at', 'LIKE', "$year-11-%")->count() }}</td>
				<td>{{ App\Models\UserLog::where('u_id', $user->u_id)->where('created_at', 'LIKE', "$year-12-%")->count() }}</td>
				<td><strong>{{ App\Models\UserLog::where('u_id', $user->u_id)->where('created_at', 'LIKE', "$year%")->count() }}</strong></td>
				<td>{{ App\Models\UserLog::where('u_id', $user->u_id)->orderBy('created_at', 'desc')->value('created_at') }}</td>
			</tr>
			@endforeach
		</table>
	</body>
</html>