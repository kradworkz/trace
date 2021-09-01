<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use View;
use Carbon;
use App\Models\Name;
use App\Models\User;
use App\Models\Event;
use App\Models\Comment;
use App\Models\UserLog;
use App\Models\Document;
use App\Models\DCommentSeen;
use App\Models\DocumentRouting;

use App\Models\GroupMember;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class T_DashboardController extends Controller
{
	public function __construct() {
		$data = [ 'page' => 'Dashboard' ];
		View::share('data', $data);
	}

    public function index() {
    	$events 		= Event::orderBy('e_id', 'desc')->get();
    	$latest_event 	= Event::orderBy('e_id', 'desc')->first();
    	$previous_day 	= date('Y-m-d H:i:s', strtotime("-1 day", strtotime(date('Y-m-d'))));
    	$today 			= date('Y-m-d H:i:s');

    	if(Auth::user()->ug_id == 1 || Auth::user()->ug_id == 3) {
    		$indocs 	= Document::where('d_status', 'Incoming')->where('d_routingthru', '!=', 0)->orderBy('d_id', 'desc')->get();
    		$pendocs 	= Document::where('d_istrack', 1)->where('d_iscompleted', 0)->orderBy('d_datetimerouted', 'desc')->get();
    		$outdocs 	= Document::where('d_status', 'Outgoing')->orderBy('d_id', 'desc')->get();

    		$today_docs = Document::where('d_status', 'Incoming')->where('d_routingthru', '!=', 0)->where('d_datetimerouted', '>=', date('Y-m-d').' 00:00:00')->where('d_datetimerouted', '<=', date('Y-m-d').' 23:59:59')->orderBy('d_id', 'desc')->get();
			$yester_docs= Document::where('d_status', 'Incoming')->where('d_routingthru', '!=', 0)->where('d_datetimerouted', '>=', $previous_day)->where('d_datetimerouted', '<=', date('Y-m-d').' 00:00:00')->orderBy('d_id', 'desc')->get();

			/*$outs_today = DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_documents.d_status', 'Outgoing')
							->where('t_documents.created_at', '>=', date('Y-m-d').' 00:00:00')->where('t_documents.created_at', '<=', date('Y-m-d').' 23:59:59')
							->groupBy('t_document_routings.d_id')->get();*/
			$outs_today = Document::where('d_status', 'Outgoing')->where('created_at', '>=', date('Y-m-d').' 00:00:00')->where('created_at', '<=', date('Y-m-d').' 23:59:59')->get();
			
			$unseen_comm= Comment::where('comm_rd', 0)->where('comm_document', 1)->Paginate(25);
    	} elseif(Auth::user()->ug_id == 2) {

			$incoming 	= DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 1)->pluck('d_id');
			$indocs 	= Document::where('d_status', 'Incoming')->whereIn('d_id', $incoming)->get();

			$pending 	= DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 1)->where('dr_completed', 0)->pluck('d_id');			
			$pendocs 	= Document::where('d_status', 'Incoming')->where('d_istrack', 1)->whereIn('d_id', $pending)->get();

			$outgoing 	= DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 0)->pluck('d_id');			
			$outdocs 	= Document::where('d_status', 'Outgoing')->whereIn('d_id', $outgoing)->get();

			$today 		= DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 1)->pluck('d_id');			
			$today_docs = Document::where('d_status', 'Incoming')->where('d_datetimerouted', '>=', date('Y-m-d').' 00:00:00')->where('d_datetimerouted', '<=', date('Y-m-d').' 23:59:59')->whereIn('d_id', $today)->get();

			$yester 	= DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 1)->pluck('d_id');			
			$yester_docs= Document::where('d_status', 'Incoming')->where('d_datetimerouted', '>=', $previous_day)->where('d_datetimerouted', '<=', date('Y-m-d').' 00:00:00')->where('d_routingthru', '!=', 0)->whereIn('d_id', $yester)->get();

			$outgoing 	= DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 0)->pluck('d_id');			
			$outs_today = Document::where('d_status', 'Outgoing')->where('created_at', '>=', date('Y-m-d').' 00:00:00')->where('created_at', '<=', date('Y-m-d').' 23:59:59')->whereIn('d_id', $outgoing)->get();

			$unseen_comm= DCommentSeen::where('u_id', Auth::user()->u_id)->where('dcs_seen', 0)->Paginate(15);

    	} else {

			$incoming 	= DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 1)->pluck('d_id');
			$indocs 	= Document::where('d_status', 'Incoming')->whereIn('d_id', $incoming)->get();

			$pending 	= DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 1)->where('dr_completed', 0)->pluck('d_id');			
			$pendocs 	= Document::where('d_status', 'Incoming')->where('d_istrack', 1)->whereIn('d_id', $pending)->get();

			$outgoing 	= DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 0)->pluck('d_id');			
			$outdocs 	= Document::where('d_status', 'Outgoing')->whereIn('d_id', $outgoing)->get();

			$today 		= DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 1)->pluck('d_id');			
			$today_docs = Document::where('d_status', 'Incoming')->where('d_datetimerouted', '>=', date('Y-m-d').' 00:00:00')->where('d_datetimerouted', '<=', date('Y-m-d').' 23:59:59')->whereIn('d_id', $today)->get();

			$yester 	= DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 1)->pluck('d_id');			
			$yester_docs= Document::where('d_status', 'Incoming')->where('d_datetimerouted', '>=', $previous_day)->where('d_datetimerouted', '<=', date('Y-m-d').' 00:00:00')->where('d_routingthru', '!=', 0)->whereIn('d_id', $yester)->get();

			$outgoing 	= DocumentRouting::where('u_id', Auth::user()->u_id)->where('dr_status', 0)->pluck('d_id');			
			$outs_today = Document::where('d_status', 'Outgoing')->where('created_at', '>=', date('Y-m-d').' 00:00:00')->where('created_at', '<=', date('Y-m-d').' 23:59:59')->whereIn('d_id', $outgoing)->get();

			$unseen_comm= DCommentSeen::where('u_id', Auth::user()->u_id)->where('dcs_seen', 0)->Paginate(15);

    	}

    	return view('dashboard.index', compact('indocs', 'pendocs', 'outdocs', 'events', 'today_docs', 'yester_docs', 'outs_today', 'latest_event', 'unseen_comm'));    	
    }    
}