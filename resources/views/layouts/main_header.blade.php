<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="author" content="Department of Science and Technology - Region IVA - MIS Unit">
	<title>{{ $data['page'] }} || {{ App\Models\Setting::orderBy('s_id', 'asc')->value('s_sysname') }}</title>
	<link href="{{ asset('images/logos/trace_logo.ico') }}" rel="shortcut icon">
	<link rel="stylesheet" href="{{ asset('extensions/chosen/chosen.min.css') }}" type="text/css">
	<link rel="stylesheet" type="text/css" href="{{ asset('extensions/bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('extensions/font-awesome/css/font-awesome.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
	<script type="text/javascript" src="{{ asset('extensions/jquery/jquery.min.js') }}"></script>
	<script src="{{ asset('extensions/chosen/chosen.jquery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('extensions/bootstrap/js/bootstrap.min.js') }}"></script>

	<!-- Timeline Chat CSS -->
	<link href="{{ asset('extensions/chat/timeline.css') }}" rel="stylesheet">

  <!-- NiceAdmin Template -->
	<!-- Bootstrap CSS -->    
  <link href="{{ asset('extensions/NiceAdmin/css/bootstrap.min.css') }}" rel="stylesheet">
  
  <!-- bootstrap theme -->
  <link href="{{ asset('extensions/NiceAdmin/css/bootstrap-theme.css') }}" rel="stylesheet">
  
  <!--external css-->
  <!-- font icon -->
  <link href="{{ asset('extensions/NiceAdmin/css/elegant-icons-style.css') }}" rel="stylesheet" />
  <link href="{{ asset('extensions/NiceAdmin/css/font-awesome.min.css') }}" rel="stylesheet" />

  <!-- full calendar css-->
  <link href="{{ asset('extensions/NiceAdmin/assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css') }}" rel="stylesheet" />
	<link href="{{ asset('extensions/NiceAdmin/assets/fullcalendar/fullcalendar/fullcalendar.css') }}" rel="stylesheet" />
  
  <!-- easy pie chart-->
  <link href="{{ asset('extensions/NiceAdmin/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css') }}" rel="stylesheet" type="text/css" media="screen"/>
  
  <!-- owl carousel -->
  <link rel="stylesheet" href="{{ asset('css/extensions/NiceAdmin/owl.carousel.css') }}" type="text/css">
	<link href="{{ asset('extensions/NiceAdmin/css/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet">

  <!-- Custom styles -->
	<link rel="stylesheet" href="{{ asset('extensions/NiceAdmin/css/fullcalendar.css') }}">
	<link href="{{ asset('extensions/NiceAdmin/css/widgets.css') }}" rel="stylesheet">
  <link href="{{ asset('extensions/NiceAdmin/css/style.css') }}" rel="stylesheet">
  <link href="{{ asset('extensions/NiceAdmin/css/style-responsive.css') }}" rel="stylesheet" />
	<link href="{{ asset('extensions/NiceAdmin/css/xcharts.min.css') }}" rel=" stylesheet">	
	<link href="{{ asset('extensions/NiceAdmin/css/jquery-ui-1.10.4.min.css') }}" rel="stylesheet">
</head>
<body>
  <?php
    $ug_id          = Auth::user()->ug_id;
    $ur_id          = App\Models\UserRight::where('ur_name', 'Incoming Documents')->value('ur_id');
    $ugr_add        = App\Models\UserGroupRight::where('ug_id', $ug_id)->where('ur_id', $ur_id)->value('ugr_add');
    $curr_date      = date('Y-m-d');

    if($ugr_add == 1) {
      if($ug_id == 1 || $ug_id == 3) {
        $unrouted   = App\Models\Document::where('d_status', 'Incoming')->where('d_routingthru', 0)->count();
        $pending    = App\Models\Document::where('d_istrack', 1)->where('d_iscompleted', 0)->count();  
        $meetings   = App\Models\Meeting::where('m_status', 'Pending')->where('m_datechecked', NULL)->count();
      } else {
        $unrouted   = App\Models\Document::where('d_status', 'Incoming')->where('d_routingthru', 0)->where('d_group_encoded', Auth::user()->group_id)->count();
        $pending    = App\Models\DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 1)->where('dr_completed', 0)->count();  
        $meetings   = App\Models\Participant::where('u_id', Auth::user()->u_id)->where('p_seen', 0)->count();
      }
      
      $ypending     = App\Models\DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 1)->where('dr_completed', 0)->count();

      $d_comm       = App\Models\DCommentSeen::where('u_id', Auth::user()->u_id)->where('dcs_seen', 0)->count();
      $e_comm       = App\Models\ECommentSeen::where('u_id', Auth::user()->u_id)->where('ecs_seen', 0)->count();

      $for_conf     = App\Models\EventSeen::where('u_id', Auth::user()->u_id)->where('es_invited', 1)->where('e_confirm', 1)->where('es_confirmed', 99)->get();
      foreach($for_conf as $for) {
        $event_conf = App\Models\Event::where('e_id', $for->e_id)->value('e_start_date');
        if($curr_date > $event_conf ) {
          App\Models\EventSeen::where('e_id', $for->e_id)->where('u_id', Auth::user()->u_id)->update(['es_seen'=>1, 'es_confirmed'=>0, 'updated_at'=>Carbon\Carbon::now()]);
        }
      }

      $event_inv    = App\Models\EventSeen::where('u_id', Auth::user()->u_id)->where('es_seen', 0)->where('es_invited', 1)->get();
      foreach($event_inv as $event_in) {
        $event_date = App\Models\Event::where('e_id', $event_in->e_id)->value('e_start_date');
        if($curr_date > $event_date ) {
          App\Models\EventSeen::where('e_id', $event_in->e_id)->where('u_id', Auth::user()->u_id)->update(['es_seen'=>1, 'updated_at'=>Carbon\Carbon::now()]);
        }
      }
      
      $incoming     = App\Models\DocumentRouting::where('u_id', Auth::user()->u_id)->where('updated_at', NULL)->where('dr_status', 1)->where('dr_seen', 0)->count();
      $outgoing     = App\Models\DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 0)->where('dr_seen', 0)->count();
      $events       = App\Models\EventSeen::where('u_id', Auth::user()->u_id)->where('es_seen', 0)->where('es_invited', 1)->count();
      $confirmation = App\Models\EventSeen::where('u_id', Auth::user()->u_id)->where('es_invited', 1)->where('e_confirm', 1)->where('es_confirmed', 99)->count();

      if($ug_id == 1) {
        $actions    = App\Models\ActionDone::where('ad_rd', 0)->count();
      } elseif($ug_id == 3) {
        $actions    = App\Models\ActionDone::where('ad_seen', 0)->count();
      } else {
        $actions    = 0;
      }

      $unapproved   = 0;
      $routedthru   = 0;

      $zoom_app     = 0;
      $zoom_req     = App\Models\Event::where('u_id', Auth::user()->u_id)->where('e_online', 1)->where('e_zoom_seen', 0)->count();

      $total        = $unrouted + $pending + $ypending + $incoming + $outgoing + $events + $meetings + $unapproved + $actions + $confirmation + $routedthru + $zoom_app + $zoom_req;
      $total_comm   = $d_comm + $e_comm;
    } else {
      $unrouted     = 0;
      $pending      = App\Models\DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_completed', 0)->where('dr_status', 1)->count();
      $d_comm       = App\Models\DCommentSeen::where('u_id', Auth::user()->u_id)->where('dcs_seen', 0)->count();
      $e_comm       = App\Models\ECommentSeen::where('u_id', Auth::user()->u_id)->where('ecs_seen', 0)->count();
      $inc          = App\Models\DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_seen', 0)->where('dr_status', 1)->pluck('d_id');
      $incoming     = App\Models\Document::where('d_status', 'Incoming')->whereIn('d_id', $inc)->count();

      $out          = App\Models\DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_seen', 0)->where('dr_status', 0)->pluck('d_id');
      $outgoing     = App\Models\Document::where('d_status', 'Outgoing')->whereIn('d_id', $out)->count();

      $for_conf     = App\Models\EventSeen::where('u_id', Auth::user()->u_id)->where('es_invited', 1)->where('e_confirm', 1)->where('es_confirmed', 99)->get();
      foreach($for_conf as $for) {
        $event_conf = App\Models\Event::where('e_id', $for->e_id)->value('e_start_date');
        if($curr_date > $event_conf ) {
          App\Models\EventSeen::where('e_id', $for->e_id)->where('u_id', Auth::user()->u_id)->update(['es_seen'=>1, 'es_confirmed'=>0, 'updated_at'=>Carbon\Carbon::now()]);
        }
      }

      $event_inv    = App\Models\EventSeen::where('u_id', Auth::user()->u_id)->where('es_seen', 0)->where('es_invited', 1)->get();
      foreach($event_inv as $event_in) {
        $event_date = App\Models\Event::where('e_id', $event_in->e_id)->value('e_start_date');
        if($curr_date > $event_date ) {
          App\Models\EventSeen::where('e_id', $event_in->e_id)->where('u_id', Auth::user()->u_id)->update(['es_seen'=>1, 'updated_at'=>Carbon\Carbon::now()]);
        }
      }

      $events       = App\Models\EventSeen::where('u_id', Auth::user()->u_id)->where('es_seen', 0)->where('es_invited', 1)->count();  
      $meetings     = App\Models\Participant::where('u_id', Auth::user()->u_id)->where('p_seen', 0)->count();
      $confirmation = App\Models\EventSeen::where('u_id', Auth::user()->u_id)->where('es_invited', 1)->where('e_confirm', 1)->where('es_confirmed', 99)->count();
      if(Auth::user()->u_administrator == 1) {
        $unapproved = App\Models\User::where('u_active', 0)->count();
      } else {
        $unapproved = 0;
      }
      
      $routedthru   = 0;

      if(Auth::user()->u_zoom_mgr == 1) {
        $zoom_app   = App\Models\Event::where('e_online', 1)->where('e_zoom_approved', 0)->where('e_zoom_date', NULL)->count();
      } else {
        $zoom_app   = 0;
      }

      $zoom_req     = App\Models\Event::where('u_id', Auth::user()->u_id)->where('e_online', 1)->where('e_zoom_seen', 0)->count();

      $total        = $unrouted + $pending + $incoming + $outgoing + $events + $meetings + $unapproved + $confirmation + $routedthru + $zoom_app + $zoom_req;
      $total_comm   = $d_comm + $e_comm;
    }          
  ?>
  <!-- container section start -->
  	<section id="container" class="">
  		<header class="header dark-bg">
        <div class="toggle-nav">
            <div class="icon-reorder tooltips" data-original-title="Menu Tabs" data-placement="bottom"><i class="icon_menu"></i></div>
        </div>

        <!--logo start-->
        <a href="{{ url('dashboard') }}" class="logo">TRACE</a>
        <!--logo end-->

        <div class="top-nav notification-row">                
          <!-- notificatoin dropdown start-->
          <ul class="nav pull-right top-menu">
            
            <!-- COMMENTS BELL -->     
            <li id="alert_notification_bar" class="dropdown">
              <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <i class="fa fa-comment"></i>
                @if($total_comm > 0)<span class="badge bg-important">{{ $total_comm }}</span>@endif
              </a>

              <ul class="dropdown-menu extended notification">
                <div class="notify-arrow notify-arrow-blue"></div>
                <li><p class="blue">You have {{ $total_comm }} new notifications</p></li>

                @if($d_comm > 0)
                <li><a href="{{ URL::to('document_comments') }}"><i class="fa fa-comment fa-fw"></i> {{ $d_comm }} Unseen Document Comments</a></li>
                @endif

                @if($e_comm > 0)
                <li><a href="{{ URL::to('event_comments') }}"><i class="fa fa-comment fa-fw"></i> {{ $e_comm }} Unseen Event Comments</a></li>
                @endif
              </ul>
            </li>


            <!-- alert notification start-->
            <li id="alert_notification_bar" class="dropdown">
              <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <i class="fa fa-bell"></i>
                @if($total > 0)<span class="badge bg-important">{{ $total }}</span>@endif
              </a>

              <ul class="dropdown-menu extended notification">
                <div class="notify-arrow notify-arrow-blue"></div>
                <li><p class="blue">You have {{ $total }} new notifications</p></li>

                @if($ugr_add == 1 && $unrouted > 0)
                <li><a href="{{ URL::to('unrouted') }}"><i class="fa fa-thumb-tack fa-fw"></i> {{ $unrouted }} Unrouted Documents</a></li>
                @endif

                @if($ugr_add == 1)
                @if($actions > 0)
                <li><a href="{{ URL::to('actions') }}"><i class="fa fa-check-square fa-fw"></i> {{ $actions }} Actions Done</a></li>
                @endif
                @endif

                @if($pending > 0)
                <li><a href="{{ URL::to('pending') }}"><i class="fa fa-exclamation-triangle fa-fw"></i> {{ $pending }} Pending Documents</a></li>
                @endif                        

                @if($ug_id == 1 || $ug_id == 3)
                @if($ypending > 0)
                <li><a href="{{ URL::to('my_pending') }}"><i class="fa fa-exclamation-triangle fa-fw"></i> {{ $ypending }} My Pending Documents</a></li>
                @endif
                @endif                    

                @if($incoming > 0)
                <li><a href="{{ URL::to('incoming/unseen') }}"><i class="fa fa-inbox fa-fw"></i> {{ $incoming }} Incoming Document</a></li>
                @endif

                @if($outgoing > 0)
                <li><a href="{{ URL::to('outgoing') }}"><i class="fa fa-archive fa-fw"></i> {{ $outgoing }} Outgoing Documents</a></li>
                @endif

                @if($events)
                <li><a href="{{ URL::to('events/invitations') }}"><i class="fa fa-calendar fa-fw"></i> {{ $events }} Event Invitations</a></li>
                @endif

                @if($meetings)
                @if(Auth::user()->ug_id == 1)
                <li><a href="{{ URL::to('meetings/unapproved') }}"><i class="fa fa-calendar-check-o"></i> {{ $meetings }} Requested Meetings</a></li>
                @else
                <li><a href="{{ URL::to('meetings') }}"><i class="fa fa-calendar-check-o"></i> {{ $meetings }} Requested Meetings</a></li>
                @endif
                @endif

                @if($confirmation)
                <li><a href="{{ URL::to('events/confirmation') }}"><i class="fa fa-check-circle"></i> {{ $confirmation }} Event Confirmation</a></li>
                @endif                        

                @if(Auth::user()->u_zoom_mgr == 1)
                @if($zoom_app > 0)
                <li><a href="{{ URL::to('zoom_schedules/approval') }}"><i class="fa fa-video-camera fa-fw"></i> {{ $zoom_app }} Zoom Requests For Approval</a></li>
                @endif
                @endif

                @if($zoom_req > 0)
                <li><a href="{{ URL::to('events') }}"><i class="fa fa-calendar fa-fw"></i> {{ $zoom_req }} Zoom Request Event Approved</a></li>
                @endif

                @if(Auth::user()->u_administrator == 1)
                @if($unapproved)
                <li><a href="{{ URL::to('accounts/unapproved') }}"><i class="fa fa-user-times fa-fw"></i> {{ $unapproved }} Unapproved Employees</a></li>
                @endif
                @endif            
              </ul>
            </li>
            <!-- alert notification end-->                

            <!-- user login dropdown start-->
            <li class="dropdown">
              <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                  <span class="profile-ava">
                      <img alt="" src="{{ asset(Auth::user()->u_picture) }}" width="30" height="30">
                  </span>
                  <span class="username">{{ Auth::user()->u_fname }}</span>
                  <b class="caret"></b>
              </a>
              <ul class="dropdown-menu extended logout">
                  <div class="log-arrow-up"></div>
                  <li class="eborder-top"><a href="{{ url('my_profile/'.Auth::user()->u_id) }}"><i class="icon_profile"></i> My Profile</a></li>
                  @if(Auth::user()->u_zoom_mgr == 1)
                  <?php $zoom_id = App\Models\Zoom::orderBy('zs_id', 'asc')->value('zs_id'); ?>
                  <li><a href="{{ url('zoom_settings/'.$zoom_id) }}"><i class="fa fa-cog"></i> Zoom Settings</a></li>
                  @endif
                  <li><a href="{{ url('logout') }}"><i class="fa fa-users"></i> Logout</a></li>                            
              </ul>
            </li>
            <!-- user login dropdown end -->
          </ul>
          <!-- notificatoin dropdown end-->
        </div>
      </header>

      <!--sidebar start-->
    	<aside>
      	<div id="sidebar" class="nav-collapse ">
          	<!-- sidebar menu start-->
          	<ul class="sidebar-menu">                
              	<li {{(Request::is('dashboard') ? 'class=active' : '') }}><a class="" href="{{ url('dashboard') }}"><i class="icon_house_alt"></i><span> Dashboard</span></a></li>
                @if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3)
		  	        <li class="sub-menu">
                  <a href="javascript:;">
                      <i class="icon_document_alt"></i>
                        <span>My Documents</span>
                        <span class="menu-arrow arrow_carrot-right"></span>
                    </a>
                    <ul class="sub">
                      <li {{(Request::segment(1) == 'my_incoming' ? 'class=active' : '') }}><a href="{{ url('my_incoming') }}"> My Incoming</a></li>
                      <li {{(Request::segment(1) == 'my_outgoing' ? 'class=active' : '') }}><a href="{{ url('my_outgoing') }}"> My Outgoing</a></li>
                      <li {{(Request::segment(1) == 'my_pending' ? 'class=active' : '') }}><a href="{{ url('my_pending') }}"> My Pending</a></li>
                    </ul>
                </li>
                @endif
                <li class="sub-menu">
                	<a href="javascript:;">
                    	<i class="icon_document_alt"></i>
                      	<span>Documents</span>
                      	<span class="menu-arrow arrow_carrot-right"></span>
                  	</a>
                  	<ul class="sub">
                      @if(Auth::user()->ug_id == 2)
                      <li {{(Request::segment(1) == 'incoming_routed' ? 'class=active' : '') }}><a href="{{ url('incoming_routed') }}"> Routed Thru</a></li>
                      <li {{(Request::segment(1) == 'report' ? 'class=active' : '') }}><a href="{{ url('report') }}"> Monitoring</a></li>
                      @endif

                      @if(Auth::user()->ug_id == 5)
                      <li {{(Request::segment(2) == 'encoded' ? 'class=active' : '') }}><a href="{{ url('incoming/encoded') }}"> Encoded Incoming</a></li>
                      @endif

                      @if(Auth::user()->ug_id == 4 || Auth::user()->ug_id == 5)
                      <li {{(Request::segment(1) == 'incoming' ? 'class=active' : '') }}><a href="{{ url('incoming') }}"> Incoming Documents</a></li>
                      @else
                      <li {{(Request::segment(1) == 'incoming' ? 'class=active' : '') }}><a href="{{ url('incoming') }}"> Tagged Incoming</a></li>
                      @endif

                      <li {{(Request::segment(1) == 'outgoing' ? 'class=active' : '') }}><a href="{{ url('outgoing') }}"> Tagged Outgoing</a></li>
                      @if(Auth::user()->ug_id == 4 || Auth::user()->ug_id == 2 || Auth::user()->ug_id == 5)
                      <li {{(Request::segment(1) == 'my_outgoing' ? 'class=active' : '') }}><a href="{{ url('my_outgoing') }}"> My Outgoing</a></li>
                      @endif
                      @if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3)
                      <li {{(Request::segment(1) == 'incoming' ? 'class=active' : '') }}><a href="{{ url('incoming') }}"> Incoming Documents</a></li>
                      <li {{(Request::segment(1) == 'actions' ? 'class=active' : '') }}><a href="{{ url('actions') }}"> Actions Done</a></li>
                      @endif
                  	</ul>
              	</li>
              	<li class="sub-menu">
                	<a href="javascript:;">
                  	<i class="fa fa-calendar-check-o"></i>
                    	<span>RD's Schedule</span>
                    	<span class="menu-arrow arrow_carrot-right"></span>
                	</a>
                	<ul class="sub">
                  	<li {{(Request::segment(1) == 'rd_schedule' ? 'class=active' : '') }}><a href="{{ url('rd_schedule') }}"> RD's Calendar</a></li>
                    <li {{(Request::segment(1) == 'meetings' ? 'class=active' : '') }}><a href="{{ url('meetings') }}"> Meetings</a></li>
                	</ul>
              	</li>
                <li class="sub-menu">
                  <a href="javascript:;">
                    <i class="fa fa-calendar"></i>
                      <span>Events</span>
                      <span class="menu-arrow arrow_carrot-right"></span>
                  </a>
                  <ul class="sub">
                    <li {{(Request::segment(1) == 'events' ? 'class=active' : '') }}><a href="{{ url('events') }}"> Events List</a></li>
                    <li {{(Request::segment(2) == 'calendar' ? 'class=active' : '') }}><a href="{{ url('events/calendar') }}"> Calendar View</a></li>
                    <li {{(Request::segment(2) == 'invitations' ? 'class=active' : '') }}><a href="{{ url('events/invitations') }}"> Event Invitations</a></li>
                    @if(Auth::user()->u_zoom_mgr != 1)
                    <li {{(Request::segment(1) == 'zoom_schedules' ? 'class=active' : '') }}><a href="{{ url('zoom_schedules') }}"> Zoom Schedules</a></li>
                    @endif
                  </ul>
                </li>
                @if(Auth::user()->u_zoom_mgr == 1)
                <li class="sub-menu">
                  <a href="javascript:;">
                    <i class="fa fa-video-camera"></i>
                      <span>Zoom Request</span>
                      <span class="menu-arrow arrow_carrot-right"></span>
                  </a>
                  <ul class="sub">
                    <li {{(Request::segment(1) == 'zoom_schedules' ? 'class=active' : '') }}><a href="{{ url('zoom_schedules') }}"> Zoom Schedules</a></li>
                    <li {{(Request::segment(2) == 'approval' ? 'class=active' : '') }}><a href="{{ url('zoom_schedules/approval') }}"> For Approval</a></li>        
                  </ul>
                </li>
                @endif
              	@if(Auth::user()->ug_id == 1 || Auth::user()->u_administrator == 1)
                <li class="sub-menu">
                  <a href="javascript:;">
                    <i class="fa fa-pie-chart"></i>
                      <span>Statistics</span>
                      <span class="menu-arrow arrow_carrot-right"></span>
                  </a>
                  <ul class="sub">
                    <li {{(Request::segment(1) == 'document_statistics' ? 'class=active' : '') }}><a href="{{ url('document_statistics') }}">Document Statistics</a></li>
                    <li {{(Request::segment(1) == 'user_statistics' ? 'class=active' : '') }}><a href="{{ url('user_statistics') }}">User Statistics</a></li>
                    <li {{(Request::segment(1) == 'unit_statistics' ? 'class=active' : '') }}><a href="{{ url('unit_statistics') }}">Unit Statistics</a></li>
                    <li {{(Request::segment(1) == 'report' ? 'class=active' : '') }}><a href="{{ url('report') }}">Report</a></li>
                  </ul>
                </li>
                @else
                <li {{(Request::is('my_statistics') ? 'class=active' : '') }}><a class="" href="{{ url('my_statistics') }}"><i class="fa fa-pie-chart"></i><span> My Statistics</span></a></li>
                @endif
              	<li {{(Request::segment(1) == 'company' ? 'class=active' : '') }}><a class="" href="{{ url('company') }}"><i class="fa fa-building"></i><span> Company</span></a></li>
              	<li class="sub-menu">
                	<a href="javascript:;">
                    	<i class="fa fa-users"></i>
                      	<span>Employees</span>
                      	<span class="menu-arrow arrow_carrot-right"></span>
                  	</a>
                  	<ul class="sub">
                    	<li {{(Request::segment(1) == 'groups' ? 'class=active' : '') }}><a href="{{ url('groups') }}"> Groups</a></li>
                      <li {{(Request::segment(1) == 'accounts' ? 'class=active' : '') }}><a href="{{ url('accounts') }}"> Accounts</a></li>
                  	</ul>
              	</li>
                <li {{(Request::segment(1) == 'about' ? 'class=active' : '') }}><a class="" href="{{ url('about') }}"><i class="fa fa-info-circle"></i><span> About</span></a></li>
                @if(Auth::user()->u_administrator == 1)
                <li class="sub-menu">
                  <a href="javascript:;">
                      <i class="fa fa-folder"></i>
                        <span>Report</span>
                        <span class="menu-arrow arrow_carrot-right"></span>
                    </a>
                    <ul class="sub">
                      <li {{(Request::segment(2) == 'cost_reduction' ? 'class=active' : '') }}><a href="{{ url('report/cost_reduction') }}">Cost Reduction</a></li>
                      <li {{(Request::segment(2) == 'utilization' ? 'class=active' : '') }}><a href="{{ url('report/utilization') }}">Utilization</a></li>                
                    </ul>
                </li>
                <li class="sub-menu">
                  <a href="javascript:;">
                      <i class="fa fa-cog"></i>
                        <span>Admin Settings</span>
                        <span class="menu-arrow arrow_carrot-right"></span>
                    </a>
                    <ul class="sub">
                      <!-- <li {{(Request::segment(1) == 'settings' ? 'class=active' : '') }}><a href="{{ url('settings') }}"><i class="fa fa-cog"></i> Site Settings</a></li>
                      <li {{(Request::segment(1) == 'user_groups' ? 'class=active' : '') }}><a href="{{ url('user_groups') }}"><i class="fa fa-users"></i> User Groups</a></li> -->
                      <li {{(Request::segment(1) == 'document_types' ? 'class=active' : '') }}><a href="{{ url('document_types') }}"><i class="fa fa-list-alt"></i> Document Types</a></li>
                      <li {{(Request::segment(1) == 'action_settings' ? 'class=active' : '') }}><a href="{{ url('action_settings') }}"><i class="fa fa-check-square-o"></i> Action Settings</a></li>
                    </ul>
                </li>
                @endif
              	<li><a href="{{ url('logout') }}"><i class="fa fa-power-off"></i><span> Sign Out</span></a></li>
          	</ul>
          	<!-- sidebar menu end-->
      	</div>
    	</aside>
      <!--sidebar end-->        
  	<!-- container section start -->  