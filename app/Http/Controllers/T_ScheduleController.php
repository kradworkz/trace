<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use View;
use Input;
use Carbon;
use App\Models\User;
use App\Models\Meeting;
use App\Models\UserRight;
use App\Models\Participant;
use App\Models\UserGroupRight;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class T_ScheduleController extends Controller
{
	public function __construct() {
		$data = [ 'page' => "RD's Calendar" ];
		View::share('data', $data);
	}

    public function index() {    	
		$members 		= User::where('u_active', 1)->orderBy('u_lname')->get();

		$meetings		= [];		
		$meeting 		= Meeting::where('m_status', '!=', 'Canceled')->get();
		$cancelations 	= [];
		$cancelation 	= Meeting::where('m_status', 'Canceled')->get();
		$participants 	= '';

		foreach($meeting as $meet) {			
			$user_ids 	= Participant::where('m_id', $meet->m_id)->where('p_ord', 0)->get();
			foreach($user_ids as $user_id) {			
				$participants .= $user_id->users->u_fname." ".ucfirst(substr($user_id->users->u_mname, 0, 1)).". ".$user_id->users->u_lname."<br>";
			}

			$meetings[] = \Calendar::event(
					User::where('u_id', $meet->m_encodedby)->value('u_fname').' '.User::where('u_id', $meet->m_encodedby)->value('u_lname').' ('.$meet->m_starttime.')',
					true,
					$meet->m_startdate,
					Carbon\Carbon::parse($meet->m_enddate),
					$meet->m_id,
						[
							'color' 		=> $meet->m_status == 'Approved' ? '#5cb85c' : '#f0ad4e',
							'url' 			=> '#',
							'm_id'			=> $meet->m_id,
							'm_date'		=> '<strong class="text-info">Date: </strong>'. date('F d, Y', strtotime($meet->m_startdate))."<br>",
							'm_time' 		=> '<strong class="text-info">Scheduled Time: </strong>'.$meet->m_starttime."<br>",
							'm_purpose'		=> '<strong class="text-info">Purpose of Meeting: </strong>'.$meet->m_purpose."<br>",
							'm_destination'	=> '<strong class="text-info">Venue: </strong>'.$meet->m_destination."<br>",
							'm_staff'		=> '<strong class="text-info">Participant/s: </strong><br>'.$participants."<br>",
							'm_others'		=> '<strong class="text-info">Other Participant/s: </strong>'.$meet->m_others."<br><br>",
							'm_reason'		=> '<strong class="text-info">Reason for Cancelation: </strong>'.$meet->m_reason."<br>",
						]
				);
			$participants 	= '';	
		}

		foreach($cancelation as $cancel) {
			$user_ids 	= Participant::where('m_id', $meet->m_id)->where('p_ord', 0)->get();
			foreach($user_ids as $user_id) {			
				$participants .= $user_id->users->u_fname." ".ucfirst(substr($user_id->users->u_mname, 0, 1)).". ".$user_id->users->u_lname."<br>";
			}

			$meetings[] = \Calendar::event(
					User::where('u_id', $cancel->m_encodedby)->value('u_fname').' '.User::where('u_id', $cancel->m_encodedby)->value('u_lname').' ('.$cancel->m_starttime.')',
					true,
					$cancel->m_startdate,
					Carbon\Carbon::parse($cancel->m_enddate),
					$cancel->m_id,
						[
							'color' 		=> '#d9534f',							
							'url' 			=> '#',
							'm_id'			=> 	$cancel->m_id,
							'm_date'		=> '<strong class="text-info">Date: </strong>'. date('F d, Y', strtotime($cancel->m_startdate))."<br>",
							'm_time' 		=> '<strong class="text-info">Scheduled Time: </strong>'.$cancel->m_starttime."<br>",
							'm_purpose'		=> '<strong class="text-info">Purpose of Meeting: </strong>'.$cancel->m_purpose."<br>",
							'm_destination'	=> '<strong class="text-info">Venue: </strong>'.$meet->m_destination."<br>",
							'm_staff'		=> '<strong class="text-info">Participant/s: </strong><br>'.$participants."<br>",
							'm_others'		=> '<strong class="text-info">Other Participant/s: </strong>'.$cancel->m_others."<br><br>",
							'm_reason'		=> '<strong class="text-info">Reason for Cancelation: </strong>'.$cancel->m_reason."<br>",
						]
				);
			$participants 	= '';	
		}

		$icon 		= "<span class='fa fa-sticky-note-o'></span> ";
		$calendar 	= \Calendar::addEvents($meetings, [
						'className' => 'calendarData',
					])->setOptions([
						'header' 		=> ['left' => 'title today', 'center' => '', 'right' => 'prev next'],
						'eventLimit' 	=> true,
						'columnFormat' 	=> 'dddd',
						'aspectRatio' 	=> '2',
						'contentHeight' => 1000,
						'eventOrder'	=> 'm_time',
					])->setCallbacks([
						'eventRender' 	=> 'function(meeting, element) {
												element.find(".fc-title").prepend("'.$icon.'");
												element.find(".fc-content").addClass("no-spill");
												element.attr("href", "javascript:void(0);");
												element.click(function() {
													$("#eventUrl").attr("href", meeting.url);
													$("#modalTitle").html(meeting.title);
													$("#modalBody").html(meeting.m_date + meeting.m_time + meeting.m_purpose + meeting.m_destination + meeting.m_staff + meeting.m_others + meeting.m_reason);
													if(meeting.m_status != "Canceled") {
														$("#editUrl").attr("href","rd_schedule/edit/" + meeting.m_id);
													}													
													$("#calendarModal").modal();
												});
											}',
					]);

		return view('schedule.calendar', compact('calendar', 'members'));
	}

	public function add() {	
		$id 		= 0;
		$schedule 	= '';
		$option 	= 'Add';		
		$members 	= User::where('u_active', 1)->get();
		return view('schedule.form', compact('option', 'schedule', 'id', 'members'));
	}

	public function edit($id) {
		$option 		= 'Edit';
		$schedule 		= Meeting::findOrFail($id);
		$party			= Participant::where('m_id', $id)->pluck('u_id');
		$members 		= User::whereNotIn('u_id', $party)->where('u_active', 1)->get();
		$participants 	= Participant::where('m_id', $id)->Paginate(10);

		$encoded 		= Meeting::where('m_id', $id)->value('m_encodedby');
		$status 		= Meeting::where('m_id', $id)->value('m_status');
		if(Auth::user()->u_id == $encoded) {
			if($status == 'Pending') {				
				return view('schedule.form', compact('option', 'schedule', 'id', 'members', 'participants'));	
			} elseif($status == 'Approved')  {
				if(Auth::user()->ug_id == 3) {			
					return view('schedule.form', compact('option', 'schedule', 'id', 'members', 'participants'));
				} else {
					return redirect('meetings')->with('unauthorize', 'Sorry, the meeting is not in pending status. You cannot edit the details anymore.');	
				}
			} else {
				return redirect('meetings')->with('unauthorize', 'Sorry, the meeting is not in pending status. You cannot edit the details anymore.');				
			}
		} else {
			return redirect('meetings')->with('unauthorize', 'Sorry, you are not allowed to edit the meeting.');
		}
	}

	public function view($id) {
		$option 		= 'View';
		$schedule 		= Meeting::findOrFail($id);
		$party			= Participant::where('m_id', $id)->pluck('u_id');
		$members 		= User::whereNotIn('u_id', $party)->where('u_active', 1)->get();
		$participants 	= Participant::where('m_id', $id)->Paginate(10);
		Participant::where('m_id', $id)->where('u_id', Auth::user()->u_id)->update(['p_seen'=>1, 'updated_at'=>Carbon\Carbon::now()]);
		return view('schedule.form', compact('option', 'schedule', 'id', 'members', 'participants'));
	}

	public function approve($id) {
		Meeting::where('m_id', $id)->update(['m_status'=>'Approved', 'm_datechecked'=>date('Y-m-d'), 'm_stat'=>1]);
		Participant::where('m_id', $id)->update(['p_notif'=>'Approved', 'updated_at'=>Carbon\Carbon::now()]);
		return redirect()->back()->with('success', 'You have successfully approved the scheduled meeting.');		
	}

	public function postpone() {
		$id 			= Input::get('postponemeetingID');
		$startdate 		= Input::get('m_tstartdate');
		$enddate 		= Input::get('m_tenddate');
		$starttime 		= Input::get('m_tstarttime');
		$endtime 		= Input::get('m_tendtime');

		if($startdate != "" && $enddate != "" && $starttime != "" && $endtime != "") {
			$sched 					= Meeting::findOrFail($id);
			$sched->m_tstartdate 	= $startdate;
			$sched->m_tenddate 		= $enddate;
			$sched->m_tstarttime 	= $starttime;
			$sched->m_tendtime 		= $endtime;
			$sched->m_status 		= 'Pending';
			$sched->m_postponedby 	= Auth::user()->u_id;
			$sched->m_reason 		= Input::get('m_postponed');
			if(Meeting::where('m_id', $id)->value('m_encodedby') == Auth::user()->u_id) {
				$sched->m_stat 		= 0;
			} else {
				$sched->m_stat 		= 1;
			}
			$sched->save();

			Participant::where('m_id', $id)->update(['p_seen'=>0, 'updated_at'=>'0000-00-00 00:00:00']);

			return redirect('meetings')->with('update', 'You have requested to re-schedule the meeting. Please wait for the approval.');
		} else {
			return redirect('meetings')->with('unauthorize', 'Sorry, re-scheduling did not proceed.');
		}		
	}

	public function cancel() {
		$id 			= Input::get('cancelmeetingID');
		if(Input::get('m_reason') != "") {
			Meeting::where('m_id', $id)->update(['m_status'=>'Canceled', 'm_reason'=>Input::get('m_reason'), 'm_datechecked'=>date('Y-m-d'), 'm_postponedby'=>Auth::user()->u_id]);
			if(Meeting::where('m_id', $id)->value('m_encodedby') == Auth::user()->u_id) {
				Meeting::where('m_id', $id)->update(['m_stat'=>0]);
			} else {
				Meeting::where('m_id', $id)->update(['m_stat'=>1]);
			}

			Participant::where('m_id', $id)->update(['p_seen'=>0, 'updated_at'=>'0000-00-00 00:00:00']);
		}

		return redirect('meetings')->with('success', 'You have cancelled the schedule.');
	}

	public function save(Request $request) {
		$id = Input::get('id');
		if($id == 0) {
            $meeting 				= new Meeting;
            $meeting->m_startdate 	= Input::get('m_startdate');
            $meeting->m_enddate 	= Input::get('m_enddate');
            $meeting->m_starttime 	= Input::get('m_starttime');
            $meeting->m_endtime 	= Input::get('m_endtime');
            $meeting->m_purpose 	= Input::get('m_purpose');
            $meeting->m_destination = Input::get('m_destination');
            $meeting->m_others 		= Input::get('m_others');
            $meeting->m_encodedby	= Auth::user()->u_id;

            $ug_id 					= Auth::user()->ug_id;
			$ur_id 					= UserRight::where('ur_name', Input::get('page'))->value('ur_id');
	    	$ugr_add 				= UserGroupRight::where('ug_id', $ug_id)->where('ur_id', $ur_id)->value('ugr_add');

	    	if($ug_id == 1 || $ug_id == 3) {
	    		$meeting->m_status 	= 'Approved';
	    		$meeting->m_stat 	= 1;
	    	}
		    
		    $meeting->created_at 	= Carbon\Carbon::now();
		    $meeting->save();

		    $meeting_id 			= Meeting::orderBy('m_id', 'desc')->value('m_id');
		    $tagged 				= Input::get('individualTag');
		    if($tagged > 0) {
		    	$tag_count 			= count($tagged);
				$items 				= array();
				if($tag_count > 0) {
					for($i=0; $i<$tag_count; $i++) {
						$item 		= [ 'm_id'			=> $meeting_id,
										'u_id'			=> $tagged[$i],
										'created_at'	=> Carbon\Carbon::now()];
						$items[] 	= $item;
					}
					DB::table('t_participants')->insert($items);
				}
		    }

            return redirect('meetings')->with('success', 'Meeting was scheduled. Please wait for confirmation.');
        } else {
        	if(Input::get('option') == 'Edit') {
        		if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3) {
        			$meeting 				= Meeting::findOrFail($id);
					$meeting->m_startdate 	= Input::get('m_startdate');
		            $meeting->m_enddate 	= Input::get('m_enddate');
		            $meeting->m_starttime 	= Input::get('m_starttime');
		            $meeting->m_endtime 	= Input::get('m_endtime');		        
		            $meeting->m_status 		= 'Approved';
		            $meeting->m_datechecked = date('Y-m-d');		            
		            $meeting->m_stat 		= 1;
		            $meeting->save();
        		}
        	}

			if(Input::get('startdate') != "") {
				$meeting 				= Meeting::findOrFail($id);
				$meeting->m_startdate 	= Input::get('startdate');
	            $meeting->m_enddate 	= Input::get('enddate');
	            $meeting->m_starttime 	= Input::get('starttime');
	            $meeting->m_endtime 	= Input::get('endtime');
	            $meeting->m_status 		= 'Approved';
	            $meeting->m_datechecked = date('Y-m-d');
	            $meeting->m_postponedby = NULL;
	            $meeting->m_stat 		= 1;
	            $meeting->save();

				Participant::where('m_id', $id)->update(['p_seen'=>0, 'updated_at'=>Carbon\Carbon::now()]);

				return redirect('meetings')->with('success', 'You have approved the new schedule.');
			} else {				
				$meeting 				= Meeting::findOrFail($id);	            
	            $meeting->m_purpose 	= Input::get('m_purpose');
	            $meeting->m_destination = Input::get('m_destination');
	            $meeting->m_others 		= Input::get('m_others');
	            $meeting->updated_at 	= Carbon\Carbon::now();
	            $meeting->save();
			}

			$tagged 				= Input::get('individualTag');
			if($tagged > 0) {
				$tag_count 				= count($tagged);
				$items 					= array();
				if($tag_count > 0) {
					for($i=0; $i<$tag_count; $i++) {
						$item 			= [ 'm_id'			=> $id,
											'u_id'			=> $tagged[$i],										
											'created_at'	=> Carbon\Carbon::now()];
						$items[] 		= $item;
					}
					DB::table('t_participants')->insert($items);
				}
			}			

			return redirect('meetings')->with('success', 'Meeting was edited.');
        }
	}

	public function remove($id) {
		$meet_id = Participant::where('p_id', $id)->value('m_id');
		Participant::where('p_id', $id)->delete();
		return redirect('rd_schedule/edit/'.$meet_id)->with('success', 'You have untagged the participant in this meeting.');
	}

	public function destinations() {
		$destinations = Meeting::select('m_destination')->groupBy('m_destination')->get();
		return $destinations;
	}
}