<?php

namespace App\Http\Controllers;

use DB;
use PDF;
use Auth;
use View;
use Input;
use Carbon;
use Request;
use App\Models\User;
use App\Models\Zoom;
use App\Models\Group;
use App\Models\Event;
use App\Models\Comment;
use App\Models\EventSeen;
use App\Models\GroupMember;
use App\Models\DocumentType;
use App\Models\ECommentSeen;
use App\Models\EventAttachment;


use App\Http\Requests;
use App\Http\Controllers\Controller;

class T_ZoomController extends Controller
{
    public function __construct() {
		$data = [ 'page' => 'Zoom Schedule' ];
		View::share('data', $data);
	}

	public function index() {		
		$search 	= '';
    	$option 	= 'View';
    	$url 		= Request::segment(2);

    	if($url == '') {
    		$events = Event::where('e_online', 1)->orderBy('e_start_date', 'desc')->Paginate(20);
    	} elseif($url == 'approval') {
    		$events = Event::where('e_online', 1)->where('e_zoom_approved', 0)->orderBy('e_start_date', 'desc')->Paginate(20);
    	}
    	
		return view('zoom.index', compact('search', 'option', 'events'));
	}

	public function search() {
		$option 	= 'View';
		$search 	= Input::get('search');    	
    	$events 	= Event::where('e_name', 'LIKE', "%$search%")->orWhere('e_type', 'LIKE', "%$search%")->orWhere('e_keywords', 'LIKE', "%$search%")->orderBy('e_start_date', 'desc')->Paginate(20);			
    	return view('events.index', compact('search', 'option', 'events'));
	}

	public function edit($id) {
		$option 	= 'Edit';
		$event 		= Event::findOrFail($id);
		$s_check_one= Event::where('e_id', '!=', $event->e_id)->where('e_start_date', $event->e_start_date)->pluck('e_id');
		$s_check_two= Event::where('e_id', '!=', $event->e_id)->where('e_start_date', $event->e_end_date)->pluck('e_id');
		$merge_one 	= $s_check_one->merge($s_check_two);

		$e_check_one= Event::where('e_id', '!=', $event->e_id)->where('e_end_date', $event->e_start_date)->pluck('e_id');
		$e_check_two= Event::where('e_id', '!=', $event->e_id)->where('e_end_date', $event->e_end_date)->pluck('e_id');
		$merge_two 	= $e_check_one->merge($e_check_two);

		$merged 	= $merge_one->merge($merge_two);
		$conflicts 	= Event::whereIn('e_id', $merged)->get();

		$attendees 	= EventSeen::where('e_id', $id)->where('es_invited', 1)->orderBy('u_id', 'asc')->Paginate(15);

		$zooms 		= Zoom::orderBy('zs_id', 'asc')->pluck('zs_email', 'zs_id');
		return view('zoom.form', compact('option', 'event', 'id', 'types', 'members', 'groups', 'attendees', 'conflicts', 'zooms'));
	}

	public function save(Request $request) {
		$id 						= Input::get('id');
		$pwd_check 					= Input::get('e_pwd');

		$event 						= Event::findOrFail($id);
		$event->zs_id 				= Input::get('zooms');
		if($pwd_check != NULL) {
			$event->e_zoom_pw 		= Input::get('e_pwd');
		}
		$event->e_zoom_approved 	= Input::get('e_zoom_approved');
		$event->e_zoom_date 		= Carbon\Carbon::now();
		$event->e_zoom_link 		= Input::get('e_zoom_link');
		$event->e_zoom_mtgid 		= Input::get('e_zoom_mtgid');
		$event->e_zoom_reason 		= Input::get('e_zoom_reason');
		$event->e_zoom_seen 		= 0;
		$event->update();

		return redirect('zoom_schedules/approval')->with('success', 'You have successfully approved the request!');
	}
}