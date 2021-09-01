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
	<nav class="navbar navbar-default navbar-inverse navbar-static-top" id="navbar-menu">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			        <span class="sr-only">Toggle navigation</span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
      			</button>
      			<a href="" class="navbar-brand">
      				<span class="trace-logo">{{ App\Models\Setting::pluck('s_abbr') }}</span>
      			</a>
			</div>			
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav navbar-left">					
					<!-- <li><a href="#" id="menu_dashboard"><span class="fa fa-bars fa-fw">Show Menu Tabs</span></a></li> -->
					<li>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspop="true" aria-expanded="false"><span class="fa fa-bars fa-fw"></span> Menu Tabs <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<br>
							<li {{(Request::is('dashboard') ? 'class=active' : '') }}><a href="{{ url('dashboard')}}"><span class="fa fa-home fa-fw"></span> Dashboard</a></li>
							<hr>

							<!-- <li {{(Request::segment(1) == 'incoming' || (Request::segment(1) == 'outgoing') ? 'class=active' : '') }}> -->
							<li class="dropdown-submenu">
								<a href="#" class="dropdown-toggle test" data-toggle="dropdown" role="button" aria-haspop="true" aria-expanded="false"><span class="fa fa-files-o fa-fw"></span> Documents</a>
								<ul class="dropdown-menu" role="menu"><br>
									<li {{(Request::segment(1) == 'incoming' ? 'class=active' : '') }}><a href="{{ url('incoming') }}">Incoming Documents <span class="badge badge-danger"></span></a></li><br>
									<li {{(Request::segment(1) == 'outgoing' ? 'class=active' : '') }}><a href="{{ url('outgoing') }}">Outgoing Documents</a></li><br>
								</ul>
							</li>
							<hr>

							<!-- <li {{(Request::segment(1) == 'rd_schedule' || (Request::segment(1) == 'meetings') ? 'class=active' : '') }}> -->
							<li class="dropdown-submenu">
								<a href="#" class="dropdown-toggle test" data-toggle="dropdown" role="button" aria-haspop="true" aria-expanded="false"><span class="fa fa-calendar-check-o fa-fw"></span> RD's Schedule</a>
								<ul class="dropdown-menu" role="menu"><br>
									<li {{(Request::segment(1) == 'rd_schedule' ? 'class=active' : '') }}><a href="{{ url('rd_schedule') }}">RD's Calendar </a></li><br>
									<li {{(Request::segment(1) == 'meetings' ? 'class=active' : '') }}><a href="{{ url('meetings') }}">Meetings</a></li><br>
								</ul>
							</li>
							<hr>

							<li {{(Request::segment(1) == 'events' ? 'class=active' : '') }}><a href="{{ url('events')}}"><span class="fa fa-calendar fa-fw"></span> Events</a></li>
							<hr>

							<!-- <li {{(Request::segment(1) == 'document_statistics' || (Request::segment(1) == 'user_statistics') || (Request::segment(1) == 'unit_statistics') ? 'class=active' : '') }}> -->
							<li class="dropdown-submenu">
								<a href="#" class="dropdown-toggle test" data-toggle="dropdown" role="button" aria-haspop="true" aria-expanded="false"><span class="fa fa-pie-chart fa-fw"></span> Statistics</a>
								<ul class="dropdown-menu" role="menu"><br>
									<li {{(Request::segment(1) == 'document_statistics' ? 'class=active' : '') }}><a href="{{ url('document_statistics') }}">Document Statistics </a></li><br>
									<li {{(Request::segment(1) == 'user_statistics' ? 'class=active' : '') }}><a href="{{ url('user_statistics') }}">User Statistics</a></li><br>
									<li {{(Request::segment(1) == 'unit_statistics' ? 'class=active' : '') }}><a href="{{ url('unit_statistics') }}">Unit Statistics</a></li><br>
								</ul>
							</li>
							<hr>

							<li {{(Request::segment(1) == 'company' ? 'class=active' : '') }}><a href="{{ url('company')}}"><span class="fa fa-building fa-fw"></span> Company Information</a></li>
							<hr>

							<!-- <li {{(Request::segment(1) == 'groups' || (Request::segment(1) == 'accounts') ? 'class=active' : '') }}> -->
							<li class="dropdown-submenu">
								<a href="#" class="dropdown-toggle test" data-toggle="dropdown" role="button" aria-haspop="true" aria-expanded="false"><span class="fa fa-users fa-fw"></span> Employees</a>
								<ul class="dropdown-menu" role="menu"><br>
									<li {{(Request::segment(1) == 'groups' ? 'class=active' : '') }}><a href="{{ url('groups') }}">Groups </a></li><br>
									<li {{(Request::segment(1) == 'accounts' ? 'class=active' : '') }}><a href="{{ url('accounts') }}">Accounts</a></li><br>
								</ul>
							</li>
							<br>
						</ul>
					</li>					
				</ul>
				<ul class="nav navbar-nav navbar-right">					
					<li {{(Request::segment(1) == 'my_profile' || (Request::segment(1) == 'settings') || (Request::segment(1) == 'user_groups') ? 'class=active' : '') }}>                    
						<button class="btn btn-primary btn-image dropdown-toggle" id="pic-button" data-toggle="dropdown" type="button">							
							<img class="pull-left" src="{{ asset(Auth::user()->u_picture) }}" width="30" height="30">&nbsp;
						</button>
						<ul class="dropdown-menu" role="menu"><br>
							<center><strong>WELCOME, {{ Auth::user()->u_fname }}</strong><br>
							<i>{{ App\Models\UserGroup::where('ug_id', Auth::user()->ug_id)->pluck('ug_name') }}</i>
							<hr>
							<li {{(Request::segment(1) == 'my_profile' ? 'class=active' : '')}}><a href="{{ url('my_profile/'.Auth::user()->u_id) }}">My Profile</a></li><br>
							@if(Auth::user()->u_administrator == 1)			
							<li {{(Request::segment(1) == 'settings' ? 'class=active' : '')}}><a href="{{ url('settings') }}">System Settings</a></li><br>
							<li {{(Request::segment(1) == 'user_groups' ? 'class=active' : '')}}><a href="{{ url('user_groups') }}">User Groups</a></li><br>
							@endif
							<li><a href="{{ url('logout') }}">Logout</a></li></center><br>
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

	<script type="text/javascript"> 
	   $(function() {
	       $('#menu_dashboard').click(function() {
	           $('#menu_tabs').toggle();
	           return false;
	       });        
	   });
	</script>

	