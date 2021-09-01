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

class T_EventController extends Controller
{
    public function __construct() {
		$data = [ 'page' => 'Events' ];
		View::share('data', $data);
	}

	public function index() {		
		$search 	= '';
    	$option 	= 'View';
    	$events 	= Event::orderBy('e_start_date', 'desc')->Paginate(20);
		return view('events.index', compact('search', 'option', 'events'));
	}

	public function confirmation() {
		$search 	= '';
		$option 	= 'View';
		$events 	= EventSeen::where('u_id', Auth::user()->u_id)->where('es_invited', 1)->where('e_confirm', 1)->where('es_confirmed', 99)->Paginate(20);
		return view('events.confirmation', compact('search', 'option', 'events'));
	}

	public function invitations() {
		$search 	= '';
    	$option 	= 'View';
    	$invites 	= EventSeen::where('u_id', Auth::user()->u_id)->where('es_invited', 1)->pluck('e_id');
    	$events 	= Event::whereIn('e_id', $invites)->orderBy('e_start_date', 'desc')->Paginate(20);
		return view('events.index', compact('search', 'option', 'events'));
	}

	public function search() {
		$option 	= 'View';
		$search 	= Input::get('search');    	
    	$events 	= Event::where('e_name', 'LIKE', "%$search%")->orWhere('e_type', 'LIKE', "%$search%")->orWhere('e_keywords', 'LIKE', "%$search%")->orderBy('e_start_date', 'desc')->Paginate(20);			
    	return view('events.index', compact('search', 'option', 'events'));
	}

	public function add() {
		$id 		= 0;		
		$event 		= '';
		$option 	= 'Add';
		$groups 	= Group::orderBy('group_id', 'asc')->get();
		$types 		= DocumentType::orderBy('dt_id', 'asc')->pluck('dt_type', 'dt_id');
		$members 	= User::where('u_id', '!=', Auth::user()->u_id)->where('u_active', 1)->orderBy('u_lname', 'asc')->get();		
		return view('events.form', compact('option', 'event', 'id', 'types', 'members', 'groups'));
	}

	public function view($id) {
		$option 	= 'View';
		$event 		= Event::findOrFail($id);		
		$types 		= DocumentType::orderBy('dt_id', 'asc')->pluck('dt_type', 'dt_id');
		$invited 	= EventSeen::where('e_id', $id)->where('es_invited', 1)->pluck('u_id');
		$members 	= User::where('u_id', '!=', Auth::user()->u_id)->whereNotIn('u_id', $invited)->where('u_active', 1)->orderBy('u_lname', 'asc')->get();
		$groups 	= Group::orderBy('group_id', 'asc')->get();
		$attendees 	= EventSeen::where('e_id', $id)->where('es_invited', 1)->orderBy('u_id', 'asc')->Paginate(15);
		$comments 	= Comment::where('comm_event', 1)->where('comm_reference', $id)->get();
		$seens 		= EventSeen::where('e_id', $id)->where('es_seen', 1)->orderBy('updated_at', 'desc')->get();
		$files 		= EventAttachment::where('e_id', $id)->Paginate(5);		
		$ev_tags 	= User::where('u_id', '!=', Auth::user()->u_id)->where('u_active', 1)->orderBy('u_fname', 'asc')->get();

		$checker 	= EventSeen::where('e_id', $id)->where('u_id', Auth::user()->u_id)->count();
		if($checker > 0) {			
			EventSeen::where('e_id', $id)->where('u_id', Auth::user()->u_id)->update(['es_seen'=>1, 'updated_at'=>Carbon\Carbon::now()]);
			ECommentSeen::where('e_id', $id)->where('u_id', Auth::user()->u_id)->where('ecs_seen', 0)->update(['ecs_seen'=>1, 'updated_at'=>Carbon\Carbon::now()]);
		} else {
			$seen 				= new EventSeen;
			$seen->e_id 		= $id;
			$seen->u_id 		= Auth::user()->u_id;
			$seen->es_seen 		= 1;
			$seen->es_invited 	= 0;
			$seen->created_at 	= Carbon\Carbon::now();
			$seen->updated_at 	= Carbon\Carbon::now();
			$seen->save();
		}

		if(Auth::user()->u_id == $event->u_id && $event->e_zoom_seen == 0) {
			Event::where('e_id', $id)->update(['e_zoom_seen'=>1, 'updated_at'=>Carbon\Carbon::now()]);
		}

		if(Auth::user()->ug_id == 1) {
			Comment::where('comm_event', 1)->where('comm_reference', $id)->where('comm_rd', 0)->update(['comm_rd'=>1]);
		}

		return view('events.form', compact('option', 'event', 'id', 'types', 'members', 'groups', 'attendees', 'comments', 'seens', 'files', 'ev_tags'));
	}

	public function edit($id) {
		$option 	= 'Edit';
		$event 		= Event::findOrFail($id);		
		$types 		= DocumentType::orderBy('dt_id', 'asc')->pluck('dt_type', 'dt_id');
		$invited 	= EventSeen::where('e_id', $id)->pluck('u_id');
		$members 	= User::where('u_id', '!=', Auth::user()->u_id)->whereNotIn('u_id', $invited)->where('u_active', 1)->orderBy('u_lname', 'asc')->get();
		$groups 	= Group::orderBy('group_id', 'asc')->get();
		$attendees 	= EventSeen::where('e_id', $id)->where('es_invited', 1)->orderBy('u_id', 'asc')->Paginate(15);
		$files 		= EventAttachment::where('e_id', $id)->Paginate(5);
		if(Event::where('e_id', $id)->value('u_id') != Auth::user()->u_id) {
			return redirect('events')->with('unauthorize', 'You have no permission to edit the event.');
		} else {
			return view('events.form', compact('option', 'event', 'id', 'types', 'members', 'groups', 'attendees', 'files'));
		}		
	}

	public function save(Request $request) {
		$id 					= Input::get('id');
		$option 				= Input::get('option');
		if(Input::get('e_confirm') == 1) {
	        $confirmation		= 99;
	        $econfirm 			= 1;
        } else {
        	$confirmation 		= 0;
        	$econfirm 			= 0;
        }

        $zoom_need 				= Input::get('e_zoom');

		if($id == 0) {
			$event  			= new Event;
			$event->e_name 		= Input::get('e_name');
			$event->e_type 		= Input::get('e_type');
			$event->e_start_date= Input::get('e_start_date');
			$event->e_start_time= Input::get('e_start_time');
			$event->e_end_date 	= Input::get('e_end_date');
			$event->e_end_time 	= Input::get('e_end_time');
			$event->e_keywords 	= Input::get('e_keywords');
			$event->e_staff  	= Input::get('e_staff');
			$event->e_venue 	= Input::get('e_venue');
			$event->u_id 		= Auth::user()->u_id;
			$event->e_confirm 	= Input::get('e_confirm');
			$event->e_online 	= Input::get('e_online');
			$event->e_zoom 		= Input::get('e_zoom');
			$event->e_zoom_link = Input::get('e_zoom_link');
			$event->e_zoom_mtgid= Input::get('e_zoom_mtgid');
			$event->e_zoom_pw 	= Input::get('e_zoom_pw');
			$event->created_at 	= Carbon\Carbon::now();
			$event->save();

			$files 						= Request::file('ea_file');			
	        $eventid 					= Event::orderBy('e_id', 'desc')->value('e_id');	        
			if (!empty($files[0])) {
	            $filesData = array();
	            foreach($files as $file) {
	                $destinationPath 	= 'upload/event_files/'.$eventid."/";
	                $filename 			= $file->getClientOriginalName();                
	                $file->move($destinationPath, $filename);
	                $filesData[] 		= ['e_id' => $eventid, 'ea_file'=>$destinationPath . $filename, 'created_at' => Carbon\Carbon::now()];
	            } 			
	 			DB::table('t_event_attachments')->insert($filesData);
	        }

			if(Input::get('tagMode') == 0) {
				$tagged 		= Input::get('individualTag');
				if($tagged > 0) {
					$tag_count 		= count($tagged);
					$items 			= array();

					if($tag_count > 0) {
						for( $i=0; $i<$tag_count; $i++ ) {
							$item 	= [ 'e_id'			=> $eventid,
										'u_id'			=> $tagged[$i],
										'es_invited' 	=> 1,
										'e_confirm' 	=> $econfirm,
										'es_confirmed'	=> $confirmation,
										'created_at' 	=> Carbon\Carbon::now()];
							$items[]= $item;
						}
						DB::table('t_event_seen')->insert($items);
					}
				}				
			} elseif(Input::get('tagMode') == 1) {
				$tagged 		= Input::get('groupTag');
				if($tagged > 0) {
					$tagged_users 	= GroupMember::whereIn('group_id', $tagged)->get();
					if(count($tagged_users) > 0)  {
						foreach($tagged_users as $tagged_user) {
							$item 	= [ 'e_id'			=> $eventid,
										'u_id'			=> $tagged_user['u_id'],
										'es_invited'	=> 1,
										'e_confirm' 	=> $econfirm,
										'es_confirmed'	=> $confirmation,
										'created_at' 	=> Carbon\Carbon::now()];
							$items[]= $item;
						}
						DB::table('t_event_seen')->insert($items);
					}
				}				
			} elseif(Input::get('tagMode') == 2) {
				$tagged_users 	= User::where('u_id', '!=', Auth::user()->u_id)->where('u_active', 1)->orderBy('u_id', 'asc')->get();
				if(count($tagged_users) > 0) {
					foreach($tagged_users as $tagged_user) {
						$item 	= [ 'e_id'			=> $eventid,
									'u_id'			=> $tagged_user['u_id'],
									'es_invited'	=> 1,
									'e_confirm' 	=> $econfirm,
									'es_confirmed'	=> $confirmation,
									'created_at'	=> Carbon\Carbon::now()];
						$items[]= $item;
					}
					DB::table('t_event_seen')->insert($items);
				}
			}
			return redirect('events')->with('success', 'You have successfully added the event.');
		} else {
			if($option == 'Edit') {
				$event 				= Event::findOrFail($id);
				$event->e_name 		= Input::get('e_name');
				$event->e_type 		= Input::get('e_type');
				$event->e_start_date= Input::get('e_start_date');
				$event->e_start_time= Input::get('e_start_time');
				$event->e_end_date 	= Input::get('e_end_date');
				$event->e_end_time 	= Input::get('e_end_time');
				$event->e_keywords 	= Input::get('e_keywords');
				$event->e_staff  	= Input::get('e_staff');
				$event->e_venue 	= Input::get('e_venue');
				$event->e_confirm 	= Input::get('e_confirm');
				$event->e_online 	= Input::get('e_online');
				$event->e_zoom 		= Input::get('e_zoom');
				$event->e_zoom_pw 	= Input::get('e_zoom_pw');
				$event->e_zoom_mtgid= Input::get('e_zoom_mtgid');
				$event->e_zoom_link = Input::get('e_zoom_link');
				$event->updated_at 	= Carbon\Carbon::now();
				$event->save();
			}
			
			$files 						= Request::file('ea_file');			
	        $eventid 					= $id;
			if (!empty($files[0])) {
	            $filesData = array();
	            foreach($files as $file) {
	                $destinationPath 	= 'upload/event_files/'.$eventid."/";
	                $filename 			= $file->getClientOriginalName();                
	                $file->move($destinationPath, $filename);
	                $filesData[] 		= ['e_id' => $eventid, 'ea_file'=>$destinationPath . $filename, 'created_at' => Carbon\Carbon::now()];
	            } 			
	 			DB::table('t_event_attachments')->insert($filesData);
	        }

	        if(Input::get('rep_comment') != "") {
				$comm 					= new Comment;
				$comm->comm_event		= 1;
				$comm->comm_reference 	= Comment::where('comm_id', Input::get('repID'))->value('comm_reference');
				$comm->u_id 			= Auth::user()->u_id;
				$comm->comm_text 		= Input::get('rep_comment');
				$comm->comm_reply 		= Input::get('repID');
				$comm->created_at 		= Carbon\Carbon::now();
				$comm->save();

				$rep 					= new ECommentSeen;
				$rep->e_id 				= Comment::where('comm_id', Input::get('repID'))->value('comm_reference');
				$rep->comm_id 			= Input::get('repID');
				$rep->u_id 				= Comment::where('comm_id', Input::get('repID'))->value('u_id');
				$rep->created_at 		= Carbon\Carbon::now();
				$rep->save();
			}

			if(Input::get('comm_text') != "") {
				$routed 			= Input::get('commTag');
				if($routed != NULL) {
					$ct 			= 1;
				} else {
					$ct 			= 0;
				}
				$c 					= new Comment;
				$c->comm_event 		= 1;
				$c->comm_reference 	= $id;
				$c->u_id 			= Auth::user()->u_id;
				$c->comm_text 		= Input::get('comm_text');
				$c->comm_tag 		= $ct;
				$c->created_at 		= Carbon\Carbon::now();
				$c->save();
				
				$comment_id 		= Comment::orderBy('comm_id', 'desc')->value('comm_id');
				$event_done 		= Event::where('e_id', $id)->value('u_id');
				if($routed != NULL) {
					$tag_comm 		= count($routed);
					$c_items 		= array();
					if($tag_comm > 0) {
						for($tc=0; $tc<$tag_comm; $tc++) {
							$c_item 	= [ 'e_id'			=> $id,
											'comm_id'		=> $comment_id,
											'u_id'			=> $routed[$tc],												
											'created_at'	=> Carbon\Carbon::now()];
							$c_items[] 	= $c_item;
						}
					}
					DB::table('t_ecomment_seen')->insert($c_items);					
				}				
			}						

			if(Input::get('tagMode') == 0) {
				$tagged 		= Input::get('individualTag');
				if($tagged > 0) {
					$tag_count 		= count($tagged);
					$items 			= array();

					if($tag_count > 0) {
						for( $i=0; $i<$tag_count; $i++ ) {
							$item 	= [ 'e_id'			=> $id,
										'u_id'			=> $tagged[$i],
										'es_invited'	=> 1,
										'e_confirm'		=> $econfirm,
										'es_confirmed'	=> $confirmation,
										'created_at'	=> Carbon\Carbon::now()];
							$items[]= $item;
						}
						DB::table('t_event_seen')->insert($items);
					}
				}				
			} elseif(Input::get('tagMode') == 1) {
				$tagged 		= Input::get('groupTag');
				if($tagged > 0) {
					$tagged_users 	= GroupMember::whereIn('group_id', $tagged)->get();
					if(count($tagged_users) > 0)  {
						foreach($tagged_users as $tagged_user) {
							$item 	= [ 'e_id'			=> $id,
										'u_id'			=> $tagged_user['u_id'],
										'es_invited'	=> 1,
										'e_confirm'		=> $econfirm,
										'es_confirmed'	=> $confirmation,
										'created_at'	=> Carbon\Carbon::now()];
							$items[]= $item;
						}
						DB::table('t_event_seen')->insert($items);
					}
				}				
			} elseif(Input::get('tagMode') == 2) {
				$tagged_users 	= User::where('u_id', '!=', Auth::user()->u_id)->where('u_active', 1)->orderBy('u_id', 'asc')->get();
				if(count($tagged_users) > 0) {
					foreach($tagged_users as $tagged_user) {
						$item 	= [ 'e_id'			=> $id,
									'u_id'			=> $tagged_user['u_id'],
									'es_invited'	=> 1,
									'e_confirm'		=> $econfirm,
									'es_confirmed'	=> $confirmation,
									'created_at'	=> Carbon\Carbon::now()];
						$items[]= $item;
					}
					DB::table('t_event_seen')->insert($items);					
				}
			}

			if(Input::get('up_comment') != "") {				
				$ec 				= Comment::findOrFail(Input::get('editID'));
				$ec->comm_text 		= Input::get('up_comment');
				$ec->updated_at 	= Carbon\Carbon::now();
				$ec->save();				
			}

			if($option == 'View') {
				if(Input::get('es_confirmed') == 1) {
					EventSeen::where('e_id', $id)->where('u_id', Auth::user()->u_id)->update(['es_confirmed'=>1]);
				} elseif(Input::get('es_confirmed') == 0) {
					EventSeen::where('e_id', $id)->where('u_id', Auth::user()->u_id)->update(['es_confirmed'=>0, 'es_reason'=>Input::get('es_reason')]);
				} elseif(Input::get('es_confirmed') == 2) {
					EventSeen::where('e_id', $id)->where('u_id', Auth::user()->u_id)->update(['es_confirmed'=>0]);
					if(Input::get('tagMode') == 0) {
						$tagged 		= Input::get('individualTag');
						$tag_count 		= count($tagged);
						$items 			= array();

						if($tag_count > 0) {
							for( $i=0; $i<$tag_count; $i++ ) {
								$item 	= [ 'e_id'		=> $id,
											'u_id'		=> $tagged[$i],
											'es_invited'=> 1,
											'e_confirm' => 1,
											'es_confirm'=> $confirmation,
											'created_at'=> Carbon\Carbon::now()];
								$items[]= $item;
							}
							DB::table('t_event_seen')->insert($items);						
						}
					}
				}
			}

			return redirect('events/view/'.$id)->with('success', 'You have successfully done your action on the event.');
		}
	}

	public function delete($id) {
		Event::where('e_id', $id)->delete();
		Comment::where('comm_event', 1)->where('comm_reference', $id)->delete();
		EventSeen::where('e_id', $id)->delete();
		ECommentSeen::where('e_id', $id)->delete();
		return redirect('events')->with('unauthorize', 'You have deleted the event.');
	}

	public function deleteAttendee($id) {
		$eventid = EventSeen::where('es_id', $id)->pluck('e_id');
		EventSeen::where('es_id', $id)->delete();
		return redirect('events/view/'.$eventid)->with('success', 'Invitation was pulled out.');
	}

	public function deleteFile($id) {
		EventAttachment::where('ea_id', $id)->delete();
		return redirect('events')->with('success', 'Event file attachment was deleted!');
	}

	public function types() {
		$eventtypes	= Event::select('e_type')->groupBy('e_type')->get();
		return $eventtypes;
	}

	public function venues() {
		$eventvenues= Event::select('e_venue')->groupBy('e_venue')->get();
		return $eventvenues;
	}

	public function printDocument() {
		$year 		= Input::get('year');
		$month 		= Input::get('month');

		$date 		= $year."-".$month;
		$events 	= Event::where('e_start_date', 'LIKE', "$date%")->orderBy('e_start_date', 'asc')->get();

		$pdf 		= PDF::loadView('events.event_pdf', compact('year', 'events', 'month', 'date'))->setPaper('legal', 'landscape');
		return $pdf->stream('MonthlyEvents_'.$date.'.pdf');
	}

	public function calendarView() {
		$meetings		= [];		
		$meeting 		= Event::get();

		foreach($meeting as $meet) {			
			$meetings[] = \Calendar::event(					
					$meet->e_name,
					true,
					$meet->e_start_date,
					Carbon\Carbon::parse($meet->e_end_date),
					$meet->e_id,
						[
							'color' 		=> '#5cb85c',
							'textColor'		=> '#000000',
							'url' 			=> '#',
							'e_date'		=> '<strong class="text-info"><font color="black">Date: </font></strong>'. date('F d, Y', strtotime($meet->e_start_date))." - ".date('F d, Y', strtotime($meet->e_end_date))."<br>",
							'e_time' 		=> '<strong class="text-info">Scheduled Time: </strong>'.$meet->e_start_time." - ".$meet->e_end_time."<br>",							
							'e_destination'	=> '<strong class="text-info">Venue: </strong>'.$meet->e_venue."<br>",
							'e_others'		=> '<strong class="text-info">Staff Involved: </strong>'.$meet->e_staff."<br><br>",
							'e_by'			=> '<strong class="text-info">Encoded By: </strong>'.User::where('u_id', $meet->u_id)->value('u_fname').' '.User::where('u_id', $meet->u_id)->value('u_lname')."<br>"
						]
				);
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
													$("#modalBody").html(meeting.e_date + meeting.e_time + meeting.e_destination + meeting.e_others + meeting.e_by);
													$("#calendarModal").modal();
												});
											}',
					]);

		return view('events.calendar', compact('calendar', 'meetings'));
	}
}