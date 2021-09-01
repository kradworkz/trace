<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use View;
use Input;
use App\Models\User;
use App\Models\Meeting;
use App\Models\Participant;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class T_MeetingController extends Controller
{
    public function __construct() {
		$data = [ 'page' => "Meetings" ];
		View::share('data', $data);
	}

	public function index() {
		$search 		= '';    	
    	if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3) {    		
    		$meetings 	= Meeting::orderBy('m_enddate', 'desc')->Paginate(25);
    	} else {
			$meet 		= Participant::where('u_id', Auth::user()->u_id)->pluck('m_id');
			$meetings 	= Meeting::whereIn('m_id', $meet)->orWhere('m_encodedby', Auth::user()->u_id)->orderBy('m_id', 'desc')->Paginate(20);
    	}
		return view('schedule.index', compact('search', 'meetings'));
	}

	public function search() {
		$search 		= Input::get('search');
    	if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3) {
    		$meetings 	= Meeting::where('m_purpose', 'LIKE', "%$search%")->orWhere('m_startdate', 'LIKE', "%$search%")->orderBy('m_enddate', 'desc')->Paginate(15);
    	} else {
    		$meetings 	= DB::table('t_meetings')->join('t_participants', 't_meetings.m_id', '=', 't_participants.m_id')		
    						->where('t_meetings.m_encodedby', Auth::user()->u_id)->where('t_participants.u_id', Auth::user()->u_id)					
							->orWhere('t_meetings.m_purpose', 'LIKE', "%$search%")->orWhere('t_meetings.m_destination', 'LIKE', "%$search%")							
							->orderBy('t_meetings.created_at', 'desc')->groupBy('t_meetings.m_id')->Paginate(20);
    	}
		return view('schedule.index', compact('search', 'meetings'));
	}

	public function edit($id) {
		$option 		= 'Edit';
		$schedule 		= Meeting::findOrFail($id);
		$party			= Participant::where('m_id', $id)->pluck('u_id');
		$members 		= User::whereNotIn('u_id', $party)->where('u_active', 1)->get();
		$participants 	= Participant::where('m_id', $id)->Paginate(10);
		return view('schedule.form', compact('option', 'schedule', 'id', 'members', 'participants'));
	}

	public function unapproved() {
		$search 	= '';    	
    	$meetings 	= Meeting::where('m_status', 'Pending')->where('m_datechecked', NULL)->orderBy('m_startdate', 'asc')->Paginate(25);
		return view('schedule.index', compact('search', 'meetings'));
	}
}
