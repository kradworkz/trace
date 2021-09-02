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
use App\Models\Action;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Document;
use App\Models\UserRight;
use App\Models\ActionDone;
use App\Models\GroupMember;
use App\Models\DCommentSeen;
use App\Models\DocumentType;
use App\Models\UserGroupRight;
use App\Models\DocumentRouting;
use App\Models\DocumentAttachment;
use App\Jobs\NotifyUser;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class T_IncomingController extends Controller
{
    public function __construct() {	
    	$data 				= [ 'page' 		=> "Incoming Documents" ];
		View::share('data', $data);    	
	}

	public function index() {
		$search 			= '';
		$where 				= '';
    	$option 			= 'View';
    	$reportSearch 		= '';
    	if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3) {
    		if(Request::segment(1) == 'my_incoming') {
    			$documents 	= DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_documents.d_status', 'Incoming')
							->where('t_document_routings.u_id', Auth::user()->u_id)->orderBy('t_document_routings.created_at', 'desc')->orderBy('t_document_routings.dr_seen', 'asc')
							->Paginate(30);
    		} else {    			
    			$documents 	= Document::where('d_status', 'Incoming')->orderBy('d_datetimerouted', 'desc')->Paginate(30);
    		}    		
    	} else {
    		$docs 			= DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 1)->pluck('d_id');
    		$documents 		= Document::whereIn('d_id', $docs)->orderBy('d_id', 'desc')->Paginate(30);
    	}
		return view('incoming.index', compact('search', 'option', 'documents', 'reportSearch'));
	}

	public function search() {
		$option 			= 'View';
		$search 			= Input::get('search');
		$where 				= '';
		$reportSearch 		= Input::get('reportSearch');
    	if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3) {
    		if(Request::segment(1) == 'my_incoming') {
    			$documents 	= DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_documents.d_status', 'Incoming')
    						->where('t_documents.d_subject', 'LIKE', "%$search%")
    						->where('t_document_routings.u_id', Auth::user()->u_id)->orderBy('t_document_routings.created_at', 'desc')->orderBy('t_document_routings.dr_seen', 'asc')->groupBy('t_documents.d_id')
    						->Paginate(20);
    		} else {
    			$documents 	= Document::where('d_status', 'Incoming')->where('d_keywords', 'LIKE', "%$search%")->orWhere('d_routingslip', 'LIKE', "%$search")->orWhere('d_sender', 'LIKE', "%$search%")->orWhere('d_subject', 'LIKE', "%$search%")->orderBy('d_id', 'desc')->Paginate(25);
    		}    		
    	} else {
    		$docs 			= DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 1)->pluck('d_id');
    		$where 			.= "((d_keywords LIKE ?) OR (d_subject LIKE ?) OR (d_routingslip LIKE ?))";
    		$values[] 		= "%$search%";
    		$values[] 		= "%$search%";
    		$values[] 		= "%$search%";    		
    		$documents  	= Document::whereIn('d_id', $docs)->where('d_status', 'Incoming')->where('d_routingthru', '!=', 0)->whereRaw($where, $values)->orderBy('d_id', 'desc')->Paginate(25);    		
    	}
		return view('incoming.index', compact('search', 'option', 'documents', 'reportSearch'));
	}

	public function thruIndex() {
		$search 		= '';
		$reportSearch 	= '';
		$documents 		= Document::where('d_status', 'Incoming')->where('d_routingthru', Auth::user()->u_id)->orderBy('d_datetimerouted', 'desc')->Paginate(30);
		return view('incoming.index', compact('search', 'documents', 'reportSearch'));
	}

	public function thruSearch() {
		$search 		= Input::get('search');
		$reportSearch 	= Input::get('reportSearch');
		$documents 		= Document::where('d_status', 'Incoming')->where('d_routingthru', Auth::user()->u_id)->where('d_subject', 'LIKE', "%$search%")->orderBy('d_datetimerouted', 'desc')->Paginate(30);
		return view('incoming.index', compact('search', 'documents', 'reportSearch'));		
	}

	public function unrouted() {
		$search 		= '';
		$reportSearch 	= '';
		$documents 		= Document::where('d_status', 'Incoming')->where('d_routingthru', 0)->Paginate(25);
		return view('incoming.index', compact('search', 'documents', 'reportSearch'));
	}

	public function unroutedSearch() {
		$search 		= Input::get('search');
		$reportSearch 	= Input::get('reportSearch');
		$documents 		= Document::where('d_status', 'Incoming')->where('d_routingthru', 0)->where('d_keywords', 'LIKE', "%$search%")->orderBy('d_id', 'desc')->Paginate(25);
		return view('incoming.index', compact('search', 'documents', 'reportSearch'));
	}

	public function pending() {
		$search 		= '';
		$reportSearch 	= '';
    	if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3) {
    		if(Request::segment(1) == 'my_pending') {
    			$documents 	= DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_documents.d_status', 'Incoming')
							->where('t_document_routings.u_id', Auth::user()->u_id)->where('t_document_routings.dr_completed', 0)->orderBy('t_document_routings.created_at', 'desc')
							->orderBy('t_document_routings.dr_seen', 'asc')->Paginate(20);
    		} else {
    			$documents 	= Document::where('d_status', 'Incoming')->where('d_routingthru', '!=', 0)->where('d_istrack', 1)->where('d_iscompleted', 0)->orderBy('d_id', 'desc')->Paginate(20);
    		}
    	} else {
			$docs 			= DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 1)->where('dr_completed', 0)->pluck('d_id');
			$documents 		= Document::where('d_status', 'Incoming')->whereIn('d_id', $docs)->Paginate(25);
    	}
		return view('incoming.pending', compact('search', 'documents', 'reportSearch'));
	}

	public function pendingSearch() {
		$search 		= Input::get('search');
		$reportSearch 	= Input::get('reportSearch');
		$where 			= '';
    	if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3) {
    		$where 			.= "((d_keywords LIKE ?) OR (d_subject LIKE ?) OR (d_routingslip LIKE ?))";
    		$values[] 		= "%$search%";
    		$values[] 		= "%$search%";
    		$values[] 		= "%$search%";

    		$documents 	= Document::where('d_status', 'Incoming')->where('d_iscompleted', 0)->whereRaw($where, $values)->orderBy('d_id', 'desc')->Paginate(20);
    	} else {
    		$docs 		= DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 1)->pluck('d_id');    		
    		$documents  = Document::whereIn('d_id', $docs)->where('d_status', 'Incoming')->where('d_keywords', 'LIKE', "%$search%")->where('d_routingthru', '!=', 0)->orderBy('d_id', 'desc')->Paginate(25);
    	}
		return view('incoming.pending', compact('search', 'documents', 'reportSearch'));
	}

	public function unseen() {
		$search 			= '';
		$reportSearch 		= '';
		$where 				= '';
    	$option 			= 'View';    	
    	$docs 				= DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 1)->where('dr_seen', 0)->pluck('d_id');
    	$documents 			= Document::whereIn('d_id', $docs)->orderBy('d_id', 'desc')->Paginate(30);    	
		return view('incoming.index', compact('search', 'option', 'documents', 'reportSearch'));
	}
	
	public function add() {
		$option 		= 'Add';
		$document 		= '';
		$id 			= 0;		
		$dtypes 		= DocumentType::pluck('dt_type', 'dt_id');
		$actions 		= Action::orderBy('a_number', 'asc')->get();

		$year 			= date('Y');
		$month 			= date('m');
		$slip 			= $year."-IN";

		$last_doc 		= Document::where('d_routingslip', 'LIKE', '%'.$slip.'%')->orderBy('d_id', 'desc')->value('d_routingslip');
		if ( $last_doc != NULL ) {
			list($a,$b,$c,$last_doc_in) = explode('-', $last_doc);
		} else {
			$last_doc_in= 0;
		}
		$last_doc_in	= $last_doc_in + 1;
		$type = env('TYPE');
		$routingslip 	= $year."-IN".$month."-".$type."-".$last_doc_in;

		$rd_fname		= User::where('ug_id', 1)->where('u_active', 1)->value('u_fname');
		$rd_lname 		= User::where('ug_id', 1)->where('u_active', 1)->value('u_lname');
		$rd 			= $rd_fname." ".$rd_lname;

		$leaders 		= User::select('u_id', DB::raw('concat(u_fname, " ", u_lname) as full_name'))->where('u_active', 1)->pluck('full_name', 'u_id')->toArray();
		
		return view('incoming.form', compact('option', 'document', 'dtypes', 'id', 'actions', 'routingslip', 'rd', 'leaders'));
	}

	public function route($id) {
		$option 		= 'Route';
		$document 		= Document::findOrFail($id);			
		$dtypes 		= DocumentType::pluck('dt_type', 'dt_id');
		$actions 		= Action::orderBy('a_number', 'asc')->get();

		$year 			= date('Y');
		$month 			= date('m');
		$slip 			= $year."-IN";

		$rd_fname		= User::where('ug_id', 1)->where('u_active', 1)->value('u_fname');
		$rd_lname 		= User::where('ug_id', 1)->where('u_active', 1)->value('u_lname');
		$rd 			= $rd_fname." ".$rd_lname;
		$leaders 		= User::select('u_id', DB::raw('concat(u_fname, " ", u_lname) as full_name'))->where('u_head', 1)->where('u_active', 1)->pluck('full_name', 'u_id')->toArray();
		$members 		= User::where('u_active', 1)->where('u_id', '!=', Auth::user()->u_id)->get();
		$groups 		= Group::get();

		$files 			= DocumentAttachment::where('d_id', $id)->get();

		return view('incoming.form', compact('option', 'document', 'dtypes', 'id', 'actions', 'rd', 'leaders', 'members', 'groups', 'files'));
	}

	public function view($id) {
		$option 		= 'View';
		$document 		= Document::findOrFail($id);
		$dtypes 		= DocumentType::pluck('dt_type', 'dt_id');
		$actions 		= Action::orderBy('a_number', 'asc')->get();
		$files 			= DocumentAttachment::where('d_id', $id)->get();
		$routed 		= DocumentRouting::where('d_id', $id)->pluck('u_id');
		$members 		= User::where('u_id', '!=', Auth::user()->u_id)->whereNotIn('u_id', $routed)->where('u_active', 1)->orderBy('u_lname', 'asc')->get();		
		$groups 		= Group::get();
		$comments 		= Comment::where('comm_document', 1)->where('comm_reference', $id)->get();
		$seens 			= DocumentRouting::where('d_id', $id)->orderBy('updated_at', 'desc')->get();
		$notifs			= DocumentRouting::where('d_id', $id)->Paginate(10);
		DocumentRouting::where('d_id', $id)->where('u_id', Auth::user()->u_id)->update(['dr_seen'=>1, 'updated_at'=>Carbon\Carbon::now()]);		
		DCommentSeen::where('d_id', $id)->where('u_id', Auth::user()->u_id)->where('dcs_seen', 0)->update(['dcs_seen'=>1, 'updated_at'=>Carbon\Carbon::now()]);

		if(Auth::user()->ug_id == 1) {
			Comment::where('comm_document', 1)->where('comm_reference', $id)->where('comm_rd', 0)->update(['comm_rd'=>1]);
		}
		$inc_ref 		= Document::where('d_id', $id)->value('d_routingslip');
		$references 	= Document::where('d_incomingreference', $inc_ref)->get();
		$ard_thru 		= Document::where('d_id', $id)->value('d_routingthru');
		$comm_tags 		= User::where('u_id', '!=', Auth::user()->u_id)->where('u_active', 1)->whereIn('u_id', $routed)->orWhere('ug_id', 1)->orWhere('u_id', $ard_thru)->orderBy('u_fname', 'asc')->get();
		return view('incoming.form', compact('id', 'option', 'document', 'dtypes', 'actions', 'files', 'members', 'groups', 'comments', 'seens', 'notifs', 'references', 'comm_tags'));
	}

	public function edit($id) {
		$option 		= 'Edit';
		$document 		= Document::findOrFail($id);
		$dtypes 		= DocumentType::pluck('dt_type', 'dt_id');
		$actions 		= Action::orderBy('a_number', 'asc')->get();
		$files 			= DocumentAttachment::where('d_id', $id)->get();
		$attachments 	= DocumentAttachment::where('d_id', $id)->Paginate(5);
		$leaders 		= User::select('u_id', DB::raw('concat(u_fname, " ", u_lname) as full_name'))->where('u_head', 1)->where('u_active', 1)->pluck('full_name', 'u_id')->toArray();
		return view('incoming.form', compact('id', 'option', 'document', 'dtypes', 'actions', 'files', 'attachments', 'leaders'));
	}

	public function save(NotifyUser $notify, Request $request) {
		$id 							= Input::get('id');
		$option 						= Input::get('option');
		if($option == 'Add' || $option == 'Edit') {
			$cname 						= Input::get('c_name');
			if(Company::where('c_name', $cname)->count() != 0) {
				$companyname 			= Company::where('c_name', $cname)->value('c_id');
			} else {
				$company 				= new Company;
				$company->c_name 		= Input::get('c_name');
				$company->u_id 			= Auth::user()->u_id;
				$company->created_at 	= Carbon\Carbon::now();
				$company->save();
				$companyname 			= Company::orderBy('c_id', 'desc')->value('c_id');
			}
		}
		

		if($id == 0) {
			$year 						= date('Y');
			$month 						= date('m');
			$slip 						= $year."-IN";

			$last_doc 					= Document::where('d_routingslip', 'LIKE', '%'.$slip.'%')->orderBy('d_id', 'desc')->value('d_routingslip');
			if ( $last_doc != NULL ) {
				list($a,$b,$c,$last_doc_in) = explode('-', $last_doc);
			} else {
				$last_doc_in 			= 0;
			}
			$last_doc_in				= $last_doc_in + 1;
			$type = env('TYPE');
			$routingslip 				= $year."-IN".$month."-".$type."-".$last_doc_in;

			$in 						= new Document;
			$in->d_status 				= 'Incoming';
			$in->d_subject 				= Input::get('d_subject');
			$in->dt_id 					= Input::get('dtypes');
			$in->d_documentdate 		= Input::get('d_documentdate');
			$in->d_datereceived 		= Input::get('d_datereceived');
			$in->d_sender 				= Input::get('d_sender');
			$in->c_id 					= $companyname;
			$in->d_keywords 			= Input::get('d_keywords');
			$in->d_routingslip 			= $routingslip;
			$in->d_routingfrom 			= User::where('ug_id', 1)->where('u_active', 1)->value('u_id');
			$in->d_encoded_by 			= Auth::user()->u_id;
			$in->d_group_encoded 		= Auth::user()->group_id;
			$in->created_at 			= Carbon\Carbon::now();			
			$in->save();

			$title = Input::get('d_subject');
			$doc_no = $routingslip;

			$files 						= Request::file('d_file');
			$filestwo 					= Request::file('d_filetwo');
	        $documentid 				= Document::orderBy('d_id', 'desc')->value('d_id');        
	        $daslip 					= Document::where('d_id', $documentid)->value('d_routingslip');
			if (!empty($files[0])) {
	            $filesData = array();
	            foreach($files as $file) {
	                $destinationPath 	= 'upload/incoming/'.$daslip."/";                
	                $filename 			= $file->getClientOriginalName();                
	                $file->move($destinationPath, $filename);
	                $filesData[] 		= ['d_id' => $documentid, 'da_file'=>$destinationPath . $filename, 'created_at' => Carbon\Carbon::now()];
	            } 			
	 			DB::table('t_document_attachments')->insert($filesData);
	        }

	        if (!empty($filestwo[0])) {
	        	$filesDataTwo = array();
	        	foreach($filestwo as $filetwo) {
	        		$destinationPath2 	= 'upload/incoming/'.$daslip."/";
	        		$filename2 			= $filetwo->getClientOriginalName();
	        		$filetwo->move($destinationPath2, $filename2);
	        		$filesDataTwo[] 	= ['d_id' => $documentid, 'da_file'=>$destinationPath2 . $filename2, 'created_at' => Carbon\Carbon::now()];
	        	}
	        	DB::table('t_document_attachments')->insert($filesDataTwo);
	        }

	        return redirect('incoming')->with('success', 'Incoming Document successfully created.');
		} else {
			if(Input::get('option') == 'Route') {
				$ActionsNeeded 			= implode(",", Input::get('d_actions'));				

				$action_checkers  		= Input::get('d_actions');
				if((strpos($ActionsNeeded, '11') !== false) || (strpos($ActionsNeeded, '1')) || (strpos($ActionsNeeded, '3'))) {
					$tracker 			= 1;
				} else {
					$tracker 			= 0;
				}

				$in 					= Document::findOrFail($id);
				$in->d_remarks 			= Input::get('d_remarks');
				$in->d_routingthru 		= Input::get('d_routingthru');
				$in->d_actions 			= $ActionsNeeded;
				$in->d_datetimerouted 	= Carbon\Carbon::now();
				$in->d_istrack 			= $tracker;
				$in->save();
				$title = $in->d_subject;
				$track_check 			= Document::where('d_id', $id)->value('d_istrack');

				if($track_check == 1) {
					$track 	= 0;
				} else {
					$track 	= 1;
				}
				$title = Document::where('d_id', $id)->value('d_subject');
				$doc_no = Document::where('d_id', $id)->value('d_routingslip');
				
				if(Input::get('tagMode') == 0) {
					$tagged 			= Input::get('individualTag');
					$tag_count 			= count($tagged);
					$items 				= array();

					if($tag_count > 0) {
						for($i=0; $i<$tag_count; $i++) {
							$count = DocumentRouting::where('d_id',$id)->where('u_id', $tagged[$i])->count();

							if($count == 0){
								$item 		= [ 'd_id'			=> $id,
												'u_id'			=> $tagged[$i],
												'dr_completed'	=> $track,
												'created_at'	=> Carbon\Carbon::now()];
								$items[] 	= $item;
								NotifyUser::dispatch($tagged[$i],$title,$doc_no,$id)->delay(now()->addSeconds(10));
							}
						}
						DB::table('t_document_routings')->insert($items);
					}
				} elseif(Input::get('tagMode') == 1) {
					$tagged 			= Input::get('groupTag');
					$tagged_users 		= GroupMember::whereIn('group_id', $tagged)->get();
					$tags 				= GroupMember::whereIn('group_id', $tagged)->pluck('u_id');
					if(count($tagged_users) > 0) {
						foreach($tagged_users as $tagged_user) {

							$count = DocumentRouting::where('d_id',$id)->where('u_id', $tagged_user['u_id'])->count();
							if($count == 0){
								$item 		= [ 'd_id'			=> $id,
												'u_id'			=> $tagged_user['u_id'],
												'dr_completed'	=> $track,
												'created_at'	=> Carbon\Carbon::now()];
								$items[]	= $item;
							
								NotifyUser::dispatch($tagged_user['u_id'],$title,$doc_no,$id)->delay(now()->addSeconds(10));
							}
						}
						DB::table('t_document_routings')->insert($items);
					}
				} elseif(Input::get('tagMode') == 2) {
					$tagged_users 		= User::where('u_id', '!=', Auth::user()->u_id)->where('u_active', 1)->orderBy('u_id', 'asc')->get();
					if(count($tagged_users) > 0) {
						foreach ( $tagged_users as $tagged_user ) {
							$count = DocumentRouting::where('d_id',$id)->where('u_id', $tagged_user['u_id'])->count();
							if($count == 0){
								$item 		= [ 'd_id'			=> $id,
												'u_id'			=> $tagged_user['u_id'],
												'dr_completed'	=> $track,
												'created_at' 	=> Carbon\Carbon::now()];
								$items[]	= $item;
								NotifyUser::dispatch($tagged_user['u_id'],$title,$doc_no,$id)->delay(now()->addSeconds(10));
							}
						}
						DB::table('t_document_routings')->insert($items);
					}
				}
				return redirect('incoming')->with('success', 'Incoming Document was successfully routed!');

			} elseif(Input::get('option') == 'Edit') {
				$in 					= Document::findOrFail($id);
				$in->d_subject 			= Input::get('d_subject');
				$in->dt_id 				= Input::get('dtypes');
				$in->d_documentdate 	= Input::get('d_documentdate');
				$in->d_datereceived 	= Input::get('d_datereceived');
				$in->d_sender 			= Input::get('d_sender');
				$in->c_id 				= $companyname;
				$in->d_keywords 		= Input::get('d_keywords');				
				$in->updated_at 		= Carbon\Carbon::now();
				$in->save();

				$files 						= Request::file('d_file');		        
				$daslip 					= Document::where('d_id', $id)->value('d_routingslip');
				
				$title = Input::get('d_subject');
				$doc_no = $in->d_routingslip;

				if (!empty($files[0])) {
		            $filesData = array();
		            foreach($files as $file) {
		                $destinationPath 	= 'upload/incoming/'.$daslip."/";                
		                $filename 			= $file->getClientOriginalName();                
		                $file->move($destinationPath, $filename);
		                $filesData[] 		= ['d_id' => $id, 'da_file'=>$destinationPath . $filename, 'created_at' => Carbon\Carbon::now()];
		            } 			
		 			DB::table('t_document_attachments')->insert($filesData);
		        }

				return redirect('incoming')->with('success', 'Incoming Document was successfully edited!');
			} elseif(Input::get('option') == 'View') {
				if(Input::get('IsTrack') == 1) {
					DocumentRouting::where('d_id', $id)->where('u_id', Auth::user()->u_id)->update(['dr_completed'=>1, 'dr_date'=>date('Y-m-d'), 'updated_at'=>Carbon\Carbon::now()]);
					$checker 			= DocumentRouting::where('d_id', $id)->where('dr_completed', 0)->count();
					if($checker < 1) {
						Document::where('d_id', $id)->update(['d_iscompleted'=>1, 'd_datecompleted'=>date('Y-m-d')]);
					}
				}

				if(Input::get('rep_comment') != "") {
					$comm 					= new Comment;
					$comm->comm_document	= 1;
					$comm->comm_reference 	= Comment::where('comm_id', Input::get('repID'))->value('comm_reference');
					$comm->u_id 			= Auth::user()->u_id;
					$comm->comm_text 		= Input::get('rep_comment');
					$comm->comm_reply 		= Input::get('repID');
					$comm->created_at 		= Carbon\Carbon::now();
					$comm->save();

					$rep 					= new DCommentSeen;
					$rep->d_id 				= Comment::where('comm_id', Input::get('repID'))->value('comm_reference');
					$rep->comm_id 			= Input::get('repID');
					$rep->u_id 				= Comment::where('comm_id', Input::get('repID'))->value('u_id');
					$rep->created_at 		= Carbon\Carbon::now();
					$rep->save();

					/*if(Input::get('ReplyIsTrack') == 1) {
						DocumentRouting::where('d_id', $id)->where('u_id', Auth::user()->u_id)->update(['dr_completed'=>1, 'dr_date'=>date('Y-m-d'), 'updated_at'=>Carbon\Carbon::now()]);
						$checker 			= DocumentRouting::where('d_id', $id)->where('dr_completed', 0)->count();
						if($checker < 1) {
							Document::where('d_id', $id)->update(['d_iscompleted'=>1, 'd_datecompleted'=>date('Y-m-d')]);
						}
					}*/
				}
				
				if(Input::get('comm_text') != "") {
					$routed 			= Input::get('commTag');
					if($routed != NULL) {
						$ct 			= 1;
					} else {
						$ct 			= 0;
					}
					$c 					= new Comment;
					$c->comm_document 	= 1;
					$c->comm_reference 	= $id;
					$c->u_id 			= Auth::user()->u_id;
					$c->comm_text 		= Input::get('comm_text');
					$c->comm_tag 		= $ct;
					$c->created_at 		= Carbon\Carbon::now();
					$c->save();
					
					$comment_id 		= Comment::orderBy('comm_id', 'desc')->value('comm_id');					
					if($routed != NULL) {
						$tag_comm 		= count($routed);
						$c_items 		= array();

						if($tag_comm > 0) {
							for($tc=0; $tc<$tag_comm; $tc++) {
								$c_item 	= [ 'd_id'			=> $id,
												'comm_id'		=> $comment_id,
												'u_id'			=> $routed[$tc],												
												'created_at'	=> Carbon\Carbon::now()];
								$c_items[] 	= $c_item;
							}
							DB::table('t_dcomment_seen')->insert($c_items);
						}					
					}

					if(Input::get('IsTrack') == 1) {
						$adone 					= new ActionDone;
						$adone->comm_id 		= $comment_id;
						$adone->d_id 			= $id;
						$adone->u_id 			= Auth::user()->u_id;
						$adone->created_at 		= Carbon\Carbon::now();
						$adone->save();
					}
				}

				if(Input::get('up_comment') != "") {				
					$ec 				= Comment::findOrFail(Input::get('editID'));
					$ec->comm_text 		= Input::get('up_comment');
					$ec->updated_at 	= Carbon\Carbon::now();
					$ec->save();

					return redirect('incoming/view/'.$id)->with('success', 'You have successfully edited your comment.');
				}

				$tracked 				= Document::where('d_id', $id)->value('d_istrack');
				if($tracked == 0) {
					$track = 1;
				} else {
					$track = 0;
				}			
				if(Input::get('tagMode') == 0) {
					$tagged 				= Input::get('individualTag');
					if($tagged > 0) {
						$tag_count 			= count($tagged);
						$items 				= array();

						if($tag_count > 0) {
							for($i=0; $i<$tag_count; $i++) {
								$count = DocumentRouting::where('d_id',$id)->where('u_id', $tagged[$i])->count();
								if($count == 0){
									$item 		= [ 'd_id'			=> $id,
													'u_id'			=> $tagged[$i],
													'dr_completed'	=> $track,
													'created_at'	=> Carbon\Carbon::now()];
									$items[] 	= $item;
									NotifyUser::dispatch($tagged[$i],$title,$doc_no,$id)->delay(now()->addSeconds(10));
								}
							}
							DB::table('t_document_routings')->insert($items);
						}
					}					
				} elseif(Input::get('tagMode') == 1) {
					$tagged 				= Input::get('groupTag');
					if($tagged > 0) {
						$orig				= DocumentRouting::where('d_id', $id)->pluck('u_id');
						$tagged_users 		= GroupMember::whereIn('group_id', $tagged)->whereNotIn('u_id', $orig)->get();
						if(count($tagged_users) > 0) {
							foreach($tagged_users as $tagged_user) {
								$count = DocumentRouting::where('d_id',$id)->where('u_id', $tagged_user['u_id'])->count();
								if($count == 0){
									$item 		= [ 'd_id'			=> $id,
													'u_id'			=> $tagged_user['u_id'],
													'dr_completed'	=> $track,
													'created_at'	=> Carbon\Carbon::now()];
									$items[]	= $item;
									NotifyUser::dispatch($tagged_user['u_id'],$title,$doc_no,$id)->delay(now()->addSeconds(10));
								}
							}
							DB::table('t_document_routings')->insert($items);
						}
					}					
				} elseif(Input::get('tagMode') == 2) {
					$orig 				= DocumentRouting::where('d_id', $id)->pluck('u_id');
					$tagged_users 		= User::where('u_id', '!=', Auth::user()->u_id)->where('u_active', 1)->whereNotIn('u_id', $orig)->get();
					if(count($tagged_users) > 0) {
						foreach ( $tagged_users as $tagged_user ) {
							$count = DocumentRouting::where('d_id',$id)->where('u_id', $tagged_user['u_id'])->count();
							if($count == 0){	
								$item 		= [ 'd_id'			=> $id,
												'u_id'			=> $tagged_user['u_id'],
												'dr_completed'	=> $track,
												'created_at' 	=> Carbon\Carbon::now()];
								$items[]	= $item;
								NotifyUser::dispatch($tagged_user['u_id'],$title,$doc_no,$id)->delay(now()->addSeconds(10));
							}
						}
						DB::table('t_document_routings')->insert($items);
					}
				}
				return redirect('incoming/view/'.$id)->with('success', 'You have successfully done your action in the document.');
			}	
		}
	}

	public function reply($id) {		
		$option 		= 'Add';
		$document 		= Document::findOrFail($id);		
		$dtypes 		= DocumentType::pluck('dt_type', 'dt_id');		

		$year 			= date('Y');
		$month 			= date('m');
		$slip 			= $year."-OUT";

		$last_doc 		= Document::where('d_routingslip', 'LIKE', '%'.$slip.'%')->orderBy('d_id', 'desc')->value('d_routingslip');
		if ( $last_doc != NULL ) {
			list($a,$b,$c,$last_doc_in) = explode('-', $last_doc);
		} else {
			$last_doc_in= 0;
		}
		$last_doc_in	= $last_doc_in + 1;
		$type = env('TYPE');
		$routingslip 	= $year."-OUT".$month."-".$type."-".$last_doc_in;

		$originals 		= DocumentRouting::where('d_id', $id)->pluck('u_id');
		$members 		= User::where('u_id', '!=', Auth::user()->u_id)->whereNotIn('u_id', $originals)->where('u_active', 1)->get();
		$groups 		= Group::get();
		return view('incoming.reply', compact('option', 'document', 'dtypes', 'id', 'routingslip', 'members', 'groups'));		
	}

	public function saveReply($id) {
		$cname 						= Input::get('c_name');
		if(Company::where('c_name', $cname)->count() != 0) {
			$companyname 			= Company::where('c_name', $cname)->value('c_id');
		} else {
			$company 				= new Company;
			$company->c_name 		= Input::get('c_name');
			$company->u_id 			= Auth::user()->u_id;
			$company->created_at 	= Carbon\Carbon::now();
			$company->save();
			$companyname 			= Company::orderBy('c_id', 'desc')->value('c_id');
		}

		$out 						= new Document;
		$out->d_status 				= 'Outgoing';
		$out->d_subject 			= Input::get('d_subject');
		$out->dt_id 				= Input::get('dtypes');
		$out->d_documentdate 		= Input::get('d_documentdate');
		$out->d_datesent 			= Input::get('d_datesent');
		$out->d_addressee 			= Input::get('d_addressee');
		$out->c_id 					= $companyname;
		$out->d_keywords 			= Input::get('d_keywords');
		$out->d_remarks 			= Input::get('d_remarks');
		$out->d_routingslip 		= Input::get('d_routingslip');
		$out->d_incomingreference 	= Document::where('d_id', $id)->value('d_routingslip');
		$out->d_routingthru 		= Auth::user()->u_id;
		$out->d_routingfrom 		= Auth::user()->u_id;
		$out->d_encoded_by 			= Auth::user()->u_id;
		$out->d_group_encoded 		= Auth::user()->group_id;
		$out->created_at 			= Carbon\Carbon::now();			
		$out->save();

		$files 						= Request::file('d_file');			
        $document_id 				= Document::orderBy('d_id', 'desc')->value('d_id');
        $daslip 					= Document::where('d_id', $document_id)->value('d_routingslip');        
		if (!empty($files[0])) {
            $filesData = array();
            foreach($files as $file) {
                $destinationPath 	= 'upload/outgoing/'.$daslip."/";                
                $filename 			= $file->getClientOriginalName();                
                $file->move($destinationPath, $filename);
                $filesData[] 		= ['d_id' => $document_id, 'da_file'=>$destinationPath . $filename, 'created_at' => Carbon\Carbon::now()];
            } 			
 			DB::table('t_document_attachments')->insert($filesData);
        }

        $orig_tag 					= DocumentRouting::where('d_id', $id)->get();
        if(count($orig_tag) > 0) {
        	$orig_routed 				= array();
		    foreach($orig_tag as $orig) {
		    	$orig_routed[]			= ['d_id' => $document_id, 'u_id' => $orig['u_id'], 'dr_status' => 0, 'created_at' => Carbon\Carbon::now()];        
		    }
		    DB::table('t_document_routings')->insert($orig_routed);
        }

        if(Input::get('tagMode') == 0) {
			$tagged 			= Input::get('individualTag');
			if($tagged > 0) {
				$tag_count 			= count($tagged);
				$items 				= array();

				if($tag_count > 0) {
					for($i=0; $i<$tag_count; $i++) {
						$count = DocumentRouting::where('d_id',$id)->where('u_id', $tagged[$i])->count();
						
						if($count == 0){
							$item 		= [ 'd_id'			=> $document_id,
											'u_id'			=> $tagged[$i],
											'dr_completed'	=> 1,
											'dr_status'		=> 0,
											'created_at'	=> Carbon\Carbon::now()];
							$items[] 	= $item;
							NotifyUser::dispatch($tagged[$i],$title,$doc_no,$id)->delay(now()->addSeconds(10));
						}
					}
					DB::table('t_document_routings')->insert($items);
				}
			}			
		} elseif(Input::get('tagMode') == 1) {
			$tagged 			= Input::get('groupTag');
			if($tagged > 0) {
				$tagged_users 		= GroupMember::whereIn('group_id', $tagged)->get();
				$tags 				= GroupMember::whereIn('group_id', $tagged)->pluck('u_id');
				if(count($tagged_users) > 0) {
					foreach($tagged_users as $tagged_user) {
						$count = DocumentRouting::where('d_id',$id)->where('u_id', $tagged_user['u_id'])->count();
						if($count == 0){
							$item 		= [ 'd_id'			=> $document_id,
											'u_id'			=> $tagged_user['u_id'],
											'dr_completed'	=> 1,
											'dr_status'		=> 0,
											'created_at'	=> Carbon\Carbon::now()];
							$items[]	= $item;
							NotifyUser::dispatch($tagged_user['u_id'],$title,$doc_no,$id)->delay(now()->addSeconds(10));
						}
					}
					DB::table('t_document_routings')->insert($items);
				}
			}			
		} elseif(Input::get('tagMode') == 2) {
			$tagged_users 		= User::where('u_id', '!=', Auth::user()->u_id)->where('u_active', 1)->orderBy('u_id', 'asc')->get();
			if(count($tagged_users) > 0) {
				foreach ( $tagged_users as $tagged_user ) {
					$count = DocumentRouting::where('d_id',$id)->where('u_id', $tagged_user['u_id'])->count();
					if($count == 0){
						$item 		= [ 'd_id'			=> $document_id,
										'u_id'			=> $tagged_user['u_id'],
										'dr_completed'	=> 1,
										'dr_status'		=> 0,
										'created_at' 	=> Carbon\Carbon::now()];
						$items[]	= $item;
						NotifyUser::dispatch($tagged_user['u_id'],$title,$doc_no,$id)->delay(now()->addSeconds(10));
					}
				}
				DB::table('t_document_routings')->insert($items);
			}
		}

		$is_tracked 	= Document::where('d_id', $id)->value('d_istrack');
		if($is_tracked == 1) {
			$doc_checker 	= DocumentRouting::where('d_id', $id)->where('u_id', Auth::user()->u_id)->value('dr_completed');
			if($doc_checker == 0) {
				DocumentRouting::where('d_id', $id)->where('u_id', Auth::user()->u_id)->where('dr_completed', 0)->update(['dr_completed'=>1, 'dr_date'=>Carbon\Carbon::now()]);
			}

			$complete 		= DocumentRouting::where('d_id', $id)->where('dr_completed', 0)->count();
			if($complete == 0) {
				Document::where('d_id', $id)->update(['d_iscompleted'=>1, 'd_datecompleted'=>Carbon\Carbon::now()]);
			}
		}

		return redirect('incoming')->with('success', 'You have successfully inputted the outgoing reply.');
	}

	public function senders() {		
		$senders	= Document::select('d_sender')->groupBy('d_sender')->get();		
		return $senders;
	}

	public function companies() {
		$companies 	= Company::select('c_name')->groupBy('c_name')->get();		
		return $companies;
	}

	public function printDocument() {
		$wholeYear  = Input::get('wholeYear');
		$year 		= Input::get('year');
		$month 		= Input::get('month');
		$rSearch 	= Input::get('reportSearch');

		$date 		= $year."-".$month;
		if($wholeYear == "Yes") {
			$documents 	= Document::where('d_status', 'Incoming')->where('created_at', 'LIKE', "$year%")->where('d_subject', 'LIKE', "%$rSearch%")->get();
		} else {
			$documents 	= Document::where('d_status', 'Incoming')->where('d_datetimerouted', 'LIKE', "$date%")->get();
		}

		$pdf 		= PDF::loadView('pdf.pdf_incoming', compact('year', 'documents', 'month', 'date', 'wholeYear'))->setPaper('legal', 'landscape');;
		return $pdf->stream('MonthlyReport_'.$date.'.pdf');
	}

	public function removeTag($id) {
		DocumentRouting::where('dr_id', $id)->delete();
		return back()->with('success', 'You have untagged the employee from the document.');
	}

	public function removeAttachment($id) {
		DocumentAttachment::where('da_id', $id)->delete();
		return back()->with('success', 'You have deleted the attachment.');
	}

	public function deleteDocument($id) {
		ActionDone::where('d_id', $id)->delete();
		Comment::where('comm_reference', $id)->delete();
		DCommentSeen::where('d_id', $id)->delete();
		DocumentAttachment::where('d_id', $id)->delete();
		DocumentRouting::where('d_id', $id)->delete();
		Document::where('d_id', $id)->delete();
		return redirect('unrouted')->with('success', 'You have successfully deleted the document!');
	}

	public function encoded() {
		$search 			= Input::get('search');
		$reportSearch 		= Input::get('reportSearch');
    	$option 			= 'View';

    	if($search == '') {
    		$documents 		= Document::where('d_status', 'Incoming')->where('d_group_encoded', Auth::user()->group_id)->orderBy('d_datetimerouted', 'desc')->Paginate(30);
    	} else {
    		$documents 		= Document::where('d_status', 'Incoming')->where('d_group_encoded', Auth::user()->group_id)->where('d_keywords', 'LIKE', "%$search%")->orderBy('d_datetimerouted', 'desc')->Paginate(30);
    	}
		return view('incoming.encoded', compact('search', 'option', 'documents', 'reportSearch'));
	}
}