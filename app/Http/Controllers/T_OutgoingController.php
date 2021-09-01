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
use App\Models\Company;
use App\Models\Comment;
use App\Models\Document;
use App\Models\GroupMember;
use App\Models\DocumentType;
use App\Models\DCommentSeen;
use App\Models\DocumentRouting;
use App\Models\DocumentAttachment;
use App\Jobs\NotifyUser;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class T_OutgoingController extends Controller
{
    public function __construct() {    			
		$data = [ 'page' => 'Outgoing Documents' ];
		View::share('data', $data);
	}

	public function index() {
		$search 		= '';
    	$option 		= 'View';
    	$reportSearch 	= '';
    	if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3) {
    		if(Request::segment(1) == 'my_outgoing') {
    			$documents 	= DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_documents.d_status', 'Outgoing')
							->where('t_document_routings.u_id', Auth::user()->u_id)->orWhere('t_documents.d_routingfrom', Auth::user()->u_id)
							->orderBy('t_document_routings.created_at', 'desc')->orderBy('t_document_routings.dr_seen', 'asc')
							->groupBy('t_documents.d_id')->Paginate(20);
    		} else {
    			$documents 	= Document::where('d_status', 'Outgoing')->orderBy('d_id', 'desc')->Paginate(20);
    		}    		
    	} else {
    		if(Request::segment(1) == 'my_outgoing') {
    			$documents 	= Document::where('d_status', 'Outgoing')->where('d_routingfrom', Auth::user()->u_id)->orderBy('d_id', 'desc')->Paginate(20);
    		} else {    			
    			$docs 		= DocumentRouting::where('u_id', Auth::user()->u_id)->pluck('d_id');
    			$documents 	= Document::where('d_status', 'Outgoing')->whereIn('d_id', $docs)->orderBy('d_id', 'desc')->Paginate(25);
    		}
    	}
		return view('outgoing.index', compact('search', 'option', 'documents', 'reportSearch'));
	}	

	public function search() {
		$search 		= Input::get('search');
		$reportSearch   = Input::get('reportSearch');
    	$option 		= 'View';
    	$where 				= '';
    	if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3) {
    		if(Request::segment(1) == 'my_outgoing') {
    			$documents 	= DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_documents.d_status', 'Outgoing')
    						->where('t_documents.d_subject', 'LIKE', "%$search%")->orWhere('t_documents.d_keywords', 'LIKE', "%$search%")->orWhere('t_documents.d_routingslip', 'LIKE', "%$search%")
    						->where('t_document_routings.u_id', Auth::user()->u_id)->orderBy('t_document_routings.created_at', 'desc')->orderBy('t_document_routings.dr_seen', 'asc')->groupBy('t_documents.d_id')
    						->Paginate(20);
    		} else {
    			$documents 	= Document::where('d_status', 'Outgoing')->where('d_subject', 'LIKE', "%$search%")->orWhere('d_routingslip', 'LIKE', "%$search")->orWhere('d_keywords', 'LIKE', "%$search")->orWhere('d_addressee', 'LIKE', "%$search")->orderBy('d_id', 'desc')->Paginate(20);
    		}    		
    	} else {
    		if(Request::segment(1) == 'my_outgoing') {    			
    			$where 		.= "((d_keywords LIKE ?) OR (d_subject LIKE ?) OR (d_routingslip LIKE ?))";
    			$values[] 	= "%$search%";
    			$values[] 	= "%$search%";
    			$values[] 	= "%$search%";
    			$documents 	= Document::where('d_status', 'Outgoing')->where('d_routingfrom', Auth::user()->u_id)->whereRaw($where, $values)->orderBy('d_id', 'desc')->Paginate(25);
    		} else {
    			$docs 		= DocumentRouting::where('u_id', Auth::user()->u_id)->pluck('d_id');
    			$where 		.= "((d_keywords LIKE ?) OR (d_subject LIKE ?) OR (d_routingslip LIKE ?))";
    			$values[] 	= "%$search%";
    			$values[] 	= "%$search%";
    			$values[] 	= "%$search%";    			
    			$documents  = Document::where('d_status', 'Outgoing')->whereIn('d_id', $docs)->whereRaw($where, $values)->orderBy('d_id', 'desc')->Paginate(25);
    		}
    	}
		return view('outgoing.index', compact('search', 'option', 'documents', 'reportSearch'));
	}

	public function add() {
		$option 		= 'Add';
		$document 		= '';
		$id 			= 0;		
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

		$members 		= User::where('u_id', '!=', Auth::user()->u_id)->where('u_active', 1)->get();
		$groups 		= Group::get();
		return view('outgoing.form', compact('option', 'document', 'dtypes', 'id', 'routingslip', 'members', 'groups'));
	}

	public function view($id) {		
		$option 		= 'View';
		$document 		= Document::findOrFail($id);		
		$dtypes 		= DocumentType::pluck('dt_type', 'dt_id');
		$routed 		= DocumentRouting::where('d_id', $id)->pluck('u_id');
		$members 		= User::where('u_id', '!=', Auth::user()->u_id)->whereNotIn('u_id', $routed)->where('u_active', 1)->orderBy('u_lname', 'asc')->get();	
		$groups 		= Group::get();
		$files 			= DocumentAttachment::where('d_id', $id)->get();
		$routes 		= DocumentRouting::where('d_id', $id)->Paginate(10);
		$comments 		= Comment::where('comm_document', 1)->where('comm_reference', $id)->get();
		$seens 			= DocumentRouting::where('d_id', $id)->where('dr_seen', 1)->get();
		DocumentRouting::where('d_id', $id)->where('u_id', Auth::user()->u_id)->update(['dr_seen'=>1, 'updated_at'=>Carbon\Carbon::now()]);
		DCommentSeen::where('d_id', $id)->where('u_id', Auth::user()->u_id)->where('dcs_seen', 0)->update(['dcs_seen'=>1, 'updated_at'=>Carbon\Carbon::now()]);
		$inc_ref 		= Document::where('d_id', $id)->value('d_incomingreference');
		$references 	= Document::where('d_routingslip', $inc_ref)->get();
		$from 			= Document::where('d_id', $id)->value('d_routingthru');
		$comm_tags 		= User::where('u_id', '!=', Auth::user()->u_id)->where('u_active', 1)->whereIn('u_id', $routed)->orWhere('ug_id', 1)->orWhere('u_id', $from)->orderBy('u_fname', 'asc')->get();
		return view('outgoing.form', compact('option', 'document', 'dtypes', 'id', 'members', 'groups', 'files', 'routes', 'comments', 'seens', 'references', 'comm_tags'));
	}

	public function edit($id) {
		$option 		= 'Edit';
		$document 		= Document::findOrFail($id);
		$dtypes 		= DocumentType::pluck('dt_type', 'dt_id');		
		$members 		= User::where('u_id', '!=', Auth::user()->u_id)->where('u_active', 1)->get();
		$groups 		= Group::get();
		$attachments 	= DocumentAttachment::where('d_id', $id)->Paginate(5);
		$routes 		= DocumentRouting::where('d_id', $id)->where('u_id', '!=', Auth::user()->u_id)->Paginate(10);
		return view('outgoing.form', compact('option', 'document', 'dtypes', 'id', 'members', 'groups', 'attachments', 'routes'));
	}

	public function save(Request $request) {
		$id 							= Input::get('id');
		$option 						= Input::get('d_option');

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
			$out->d_routingslip 		= $routingslip;
			$out->d_routingthru 		= Auth::user()->u_id;
			$out->d_routingfrom 		= Auth::user()->u_id;
			$out->d_datetimerouted 		= Carbon\Carbon::now();
			$out->d_encoded_by 			= Auth::user()->u_id;
			$out->d_group_encoded 		= Auth::user()->group_id;
			$out->created_at 			= Carbon\Carbon::now();			
			$out->save();

			$title = Input::get('d_subject');
			$doc_no = $routingslip;

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
								
								NotifyUser::dispatch($tagged[$i],$title,$doc_no,$document_id)->delay(now()->addSeconds(10));
							}
						}
						// $notify->sms($tagged,$title,$doc_no);
				
						DB::table('t_document_routings')->insert($items);
					}
				}				
			} elseif(Input::get('tagMode') == 1) {
				$tagged 			= Input::get('groupTag');
				if($tagged > 0) {
					$tagged_users 		= GroupMember::whereIn('group_id', $tagged)->get();					
					if(count($tagged_users) > 0) {
						foreach($tagged_users as $tagged_user) {
							$count = DocumentRouting::where('d_id',$id)->where('u_id',$tagged_user['u_id'])->count();

							if($count == 0){
								$item 		= [ 'd_id'			=> $document_id,
												'u_id'			=> $tagged_user['u_id'],
												'dr_completed'	=> 1,
												'dr_status'		=> 0,
												'created_at'	=> Carbon\Carbon::now()];
								$items[]	= $item;

								NotifyUser::dispatch($tagged_user['u_id'],$title,$doc_no,$document_id)->delay(now()->addSeconds(10));
							}
						}
						DB::table('t_document_routings')->insert($items);
					}
				}
			} elseif(Input::get('tagMode') == 2) {
				$tagged_users 		= User::where('u_id', '!=', Auth::user()->u_id)->where('u_active', 1)->orderBy('u_id', 'asc')->get();
				if(count($tagged_users) > 0) {
					foreach ( $tagged_users as $tagged_user ) {
						$count = DocumentRouting::where('d_id',$id)->where('u_id',$tagged_user['u_id'])->count();
						if($count == 0){
							$item 		= [ 'd_id'			=> $document_id,
											'u_id'			=> $tagged_user['u_id'],
											'dr_completed'	=> 1,
											'dr_status'		=> 0,
											'created_at' 	=> Carbon\Carbon::now()];
							$items[]	= $item;
						}
						NotifyUser::dispatch($tagged_user['u_id'],$title,$doc_no,$document_id)->delay(now()->addSeconds(10));
					}
					
					DB::table('t_document_routings')->insert($items);
				}
			}

			return redirect('outgoing')->with('success', 'You have successfully created a new outgoing document.');

		} else {			
			if($option == 'Edit') {
				$out 						= Document::findOrFail($id);			
				$out->d_subject 			= Input::get('d_subject');
				$out->dt_id 				= Input::get('dtypes');
				$out->d_documentdate 		= Input::get('d_documentdate');
				$out->d_datesent 			= Input::get('d_datesent');
				$out->d_addressee 			= Input::get('d_addressee');
				$out->c_id 					= $companyname;
				$out->d_keywords 			= Input::get('d_keywords');
				$out->d_remarks 			= Input::get('d_remarks');
				$out->updated_at 			= Carbon\Carbon::now();
				$out->save();	

				$title = $out->d_subject;
				$doc_no = $out->d_routingslip;

				$files 						= Request::file('d_file');
		        $daslip 					= Document::where('d_id', $id)->value('d_routingslip');
				if (!empty($files[0])) {
		            $filesData = array();
		            foreach($files as $file) {
		                $destinationPath 	= 'upload/outgoing/'.$daslip."/";
		                $filename 			= $file->getClientOriginalName();
		                $file->move($destinationPath, $filename);
		                $filesData[] 		= ['d_id' => $id, 'da_file'=>$destinationPath . $filename, 'created_at' => Carbon\Carbon::now()];
		            } 			
		 			DB::table('t_document_attachments')->insert($filesData);
		        }
			}

			$title = Document::where('d_id', $id)->value('d_subject');
			$doc_no = Document::where('d_id', $id)->value('d_routingslip');

			if(Input::get('tagMode') == 0) {
				$tagged 			= Input::get('individualTag');
				if($tagged > 0) {
					$tag_count 		= count($tagged);
					$items 			= array();
					if($tag_count > 0) {
						for($i=0; $i<$tag_count; $i++) {
							$count = DocumentRouting::where('d_id',$id)->where('u_id', $tagged[$i])->count();

							if($count == 0){
								$item 	= [ 'd_id'			=> $id,
											'u_id'			=> $tagged[$i],
											'dr_completed'	=> 1,
											'dr_status'		=> 0,
											'created_at'	=> Carbon\Carbon::now()];
								$items[]= $item;

								NotifyUser::dispatch($tagged[$i],$title,$doc_no,$id)->delay(now()->addSeconds(10));
							}
						}
						DB::table('t_document_routings')->insert($items);
					}	
				}				
			} elseif(Input::get('tagMode') == 1) {
				$tagged 			= Input::get('groupTag');
				if($tagged > 0) {
					$tagged_users 	= GroupMember::whereIn('group_id', $tagged)->get();				
					if(count($tagged_users) > 0) {
						foreach($tagged_users as $tagged_user) {
							$count = DocumentRouting::where('d_id',$id)->where('u_id', $tagged_user['u_id'])->count();
							if($count == 0){
								$item 	= [ 'd_id'			=> $id,
											'u_id'			=> $tagged_user['u_id'],
											'dr_completed'	=> 1,
											'dr_status'		=> 0,
											'created_at'	=> Carbon\Carbon::now()];
								$items[]= $item;

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
							$item 		= [ 'd_id'			=> $id,
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
			}
								
	        if(Input::get('comm_text') != "") {
	        	$routed 			= Input::get('commTag');
				if($routed != NULL) {
					$ct 			= 1;
				} else {
					$ct 			= 0;
				}
				$c 					= new Comment;
				$c->comm_document	= 1;
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
			}

	        if(Input::get('up_comment') != "") {
				$ec 				= Comment::findOrFail(Input::get('editID'));
				$ec->comm_text 		= Input::get('up_comment');
				$ec->updated_at 	= Carbon\Carbon::now();
				$ec->save();
			}

			return redirect('outgoing/view/'.$id)->with('success', 'You have successfully done your action on the document.');
		}
	}

	public function removeTag($id) {
		$doc_id = DocumentRouting::where('dr_id', $id)->value('d_id');
		DocumentRouting::where('dr_id', $id)->delete();
		return redirect('outgoing/view/'.$doc_id)->with('success', 'You have untagged the employee in this document.');
	}

	public function loremipsum($id){
		$doc = DocumentRouting::where('u_id', $id)->update(['dr_seen' => 1,'updated_at' => now()]);

		return true;
	}	

	public function deleteAttachment($id) {
		$doc_id = DocumentAttachment::where('da_id', $id)->value('d_id');
		DocumentAttachment::where('da_id', $id)->delete();
		return redirect('outgoing/view/'.$doc_id)->with('success', 'You have deleted the file attachment.');
	}

	public function addressees() {
		$addressees	= Document::select('d_addressee')->groupBy('d_addressee')->get();
		return $addressees;
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
		if(Auth::user()->ug_id == 3) {
			if($wholeYear == "Yes") {
				$documents 	= Document::where('d_status', 'Outgoing')->where('created_at', 'LIKE', "$year%")->where('d_subject', 'LIKE', "%$rSearch%")->get();
			} else {
				$documents 	= Document::where('d_status', 'Outgoing')->where('created_at', 'LIKE', "$date%")->get();
			}
		} else {
			$documents 	= Document::where('d_status', 'Outgoing')->where('d_routingfrom', Auth::user()->u_id)->where('created_at', 'LIKE', "$date%")->get();
		}		

		$pdf 		= PDF::loadView('outgoing.report_pdf', compact('year', 'documents', 'month', 'date', 'wholeYear'))->setPaper('legal', 'landscape');;
		return $pdf->stream('MonthlyReport_'.$date.'.pdf');
	}
}