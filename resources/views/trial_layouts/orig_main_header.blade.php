<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>{{ $data['page'] }} || {{ App\Models\Setting::pluck('s_sysname') }}</title>
	<link href="{{ asset('images/logos/trace_logo.ico') }}" rel="shortcut icon">
	<link rel="stylesheet" href="{{ asset('extensions/chosen/chosen.min.css') }}" type="text/css">
	<link rel="stylesheet" type="text/css" href="{{ asset('extensions/bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('extensions/font-awesome/css/font-awesome.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
	<script type="text/javascript" src="{{ asset('extensions/jquery/jquery.min.js') }}"></script>
	<script src="{{ asset('extensions/chosen/chosen.jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('extensions/bootstrap/js/bootstrap.min.js') }}"></script>

	<!-- Timeline CSS Style: Comment Thread CSS -->
	<link href="{{ asset('extensions/chat/timeline.css') }}" rel="stylesheet">
</head>
<body>
	<nav class="navbar navbar-default navbar-inverse navbar-static-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			        <span class="sr-only">Toggle navigation</span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
      			</button>
      			<a href="" class="navbar-brand">
      				<span class="trace-logo">{{-- {{ App\Models\Setting::pluck('s_sysname') }} --}}</span>
      			</a>
			</div>			
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-right">
					<li {{(Request::is('dashboard') ? 'class=active' : '') }}><a href="{{ url('dashboard')}}"><span class="fa fa-home fa-fw"></span> Home</a></li>

					<li {{(Request::segment(1) == 'incoming' || (Request::segment(1) == 'outgoing') ? 'class=active' : '') }}>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspop="true" aria-expanded="false"><span class="fa fa-files-o fa-fw"></span> Documents <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li {{(Request::segment(1) == 'incoming' ? 'class=active' : '') }}><a href="{{ url('incoming') }}">Incoming Documents <span class="badge badge-danger"></span></a></li>
							<li {{(Request::segment(1) == 'outgoing' ? 'class=active' : '') }}><a href="{{ url('outgoing') }}">Outgoing Documents</a></li>
						</ul>
					</li>

					<li {{(Request::segment(1) == 'rd_schedule' || (Request::segment(1) == 'meetings') ? 'class=active' : '') }}>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspop="true" aria-expanded="false"><span class="fa fa-calendar-check-o fa-fw"></span> RD's Schedule <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li {{(Request::segment(1) == 'rd_schedule' ? 'class=active' : '') }}><a href="{{ url('rd_schedule') }}">RD's Calendar </a></li>
							<li {{(Request::segment(1) == 'meetings' ? 'class=active' : '') }}><a href="{{ url('meetings') }}">Meetings</a></li>
						</ul>
					</li>

					<li {{(Request::segment(1) == 'events' ? 'class=active' : '') }}><a href="{{ url('events')}}"><span class="fa fa-calendar fa-fw"></span> Events</a></li>

					<li {{(Request::segment(1) == 'document_statistics' || (Request::segment(1) == 'user_statistics') || (Request::segment(1) == 'unit_statistics') ? 'class=active' : '') }}>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspop="true" aria-expanded="false"><span class="fa fa-pie-chart fa-fw"></span> Statistics <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li {{(Request::segment(1) == 'document_statistics' ? 'class=active' : '') }}><a href="{{ url('document_statistics') }}">Document Statistics </a></li>
							<li {{(Request::segment(1) == 'user_statistics' ? 'class=active' : '') }}><a href="{{ url('user_statistics') }}">User Statistics</a></li>
							<li {{(Request::segment(1) == 'unit_statistics' ? 'class=active' : '') }}><a href="{{ url('unit_statistics') }}">Unit Statistics</a></li>
						</ul>
					</li>

					<li {{(Request::segment(1) == 'company' ? 'class=active' : '') }}><a href="{{ url('company')}}"><span class="fa fa-building fa-fw"></span> Company Information</a></li>

					<li {{(Request::segment(1) == 'groups' || (Request::segment(1) == 'accounts') ? 'class=active' : '') }}>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspop="true" aria-expanded="false"><span class="fa fa-users fa-fw"></span> Employees <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li {{(Request::segment(1) == 'groups' ? 'class=active' : '') }}><a href="{{ url('groups') }}">Groups </a></li>
							<li {{(Request::segment(1) == 'accounts' ? 'class=active' : '') }}><a href="{{ url('accounts') }}">Accounts</a></li>
						</ul>
					</li>

					<li {{(Request::segment(1) == 'my_profile' || (Request::segment(1) == 'settings') || (Request::segment(1) == 'user_groups') ? 'class=active' : '') }}>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspop="true" aria-expanded="false" title="Account"><span class="fa fa-user fa-fw"></span> {{ Auth::user()->u_fname }} {{ Auth::user()->u_lname }} <span class="caret"></span></span></a>
						<ul class="dropdown-menu" role="menu">
							<li {{(Request::segment(1) == 'my_profile' ? 'class=active' : '')}}><a href="{{ url('my_profile/'.Auth::user()->u_id) }}">My Profile</a></li>							
							@if(Auth::user()->u_administrator == 1)							
							<li {{(Request::segment(1) == 'settings' ? 'class=active' : '')}}><a href="{{ url('settings') }}">System Settings</a></li>
							<li {{(Request::segment(1) == 'user_groups' ? 'class=active' : '')}}><a href="{{ url('user_groups') }}">User Groups</a></li>
							@endif							
							<li><a href="{{ url('logout') }}">Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>	
		</div>
	</nav>
	<div class="container">
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
	</div>

	<script type="text/javascript">
		$("#alert").fadeTo(2000, 500).slideUp(500, function(){
		    $("#alert").slideUp(500);
		});
	</script>