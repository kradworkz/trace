<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use View;
use Input;
use Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Setting;
use App\Models\UserLog;
use App\Models\Document;
use App\Models\GroupMember;
use App\Models\DocumentRouting;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class T_StatisticsController extends Controller
{
    public function __construct() {
        $data = [ 'page' => 'Statistics' ];
        View::share('data', $data);
    }

    public function overAll() {
        $textbox    = Input::get('year');
        $overall    = Document::where('d_status', 'Incoming')->where('d_istrack', 1)->count();

        $first      = Document::orderBy('d_documentdate', 'asc')->value('d_documentdate');
        $last       = Document::orderBy('d_documentdate', 'desc')->value('d_documentdate');

        $from_year  = date('Y', strtotime($first));
        $to_year    = date('Y', strtotime($last));

        $pending    = Document::where('d_istrack', 1)->where('d_iscompleted', 0)->where('d_datetimerouted', 'LIKE', '%'.$textbox.'%')->count();
        $years      = Document::where('d_istrack', 1)->where('d_datetimerouted', 'LIKE', '%'.$textbox.'%')->get();

        $sets       = Document::where('d_istrack', 1)->get();
        $pday       = Setting::orderBy('s_id', 'desc')->value('s_pending_days');

        $total_ontimes = 0;
        $total_beyonds = 0;
        foreach( $years as $year ) {
            $dtrs           = $year['d_datetimerouted'];
            $dts            = date('Y-m-d', strtotime($dtrs));

            $adds           = date('Y-m-d', strtotime($dts. " + {$pday} days"));            
            $dcs            = $year['d_datecompleted'];

            if (( $dcs <= $adds ) && ( $dcs <> NULL)) {                
                $ontime_counts = 1;
                $total_ontimes += $ontime_counts;
            } elseif ( $dcs > $adds ) {                       
                $beyond_counts = 1;
                $total_beyonds += $beyond_counts;                
            }
        }

        $total_ontime = 0;
        $total_beyond = 0;
        foreach($sets as $set) {
            $dtr            = $set['d_datetimerouted'];            
            $dt             = date('Y-m-d', strtotime($dtr));

            $add            = date('Y-m-d', strtotime($dt. " + {$pday} days"));
            $dc             = $set['d_datecompleted'];
            
            if(( $dc <= $add ) && ( $dc <> NULL)) {                
                $ontime_count = 1;
                $total_ontime += $ontime_count;         
            } elseif ( $dc > $add ) {                
                $beyond_count = 1;
                $total_beyond += $beyond_count;
            } 
        }

        return view('statistics.overall', compact('textbox', 'overall', 'from_year', 'to_year', 'total_ontime', 'total_beyond', 'pending', 'total_ontimes', 'total_beyonds'));
    }

    public function userIndex() {
        $search = '';
        $stats  = User::orderBy('u_lname', 'asc')->where('u_active', 1)->Paginate(25);
        return view('statistics.users_index', compact('stats', 'search'));
    }

    public function unitIndex() {
        $search = '';
        $stats  = Group::orderBy('group_id', 'asc')->Paginate(15);
        return view('statistics.unit_index', compact('stats', 'search'));
    }

    public function search() {
        if(Request::segment(1) == 'user_statistics') {
            $search = Input::get('search');
            $stats  = User::where('u_active', 1)->where('u_fname', 'LIKE', "%$search%")->orWhere('u_lname', 'LIKE', "%$search%")->orderBy('u_lname', 'asc')->Paginate(25);
            return view('statistics.users_index', compact('stats', 'search'));
        } elseif(Request::segment(1) == 'unit_statistics') {
            $search = Input::get('search');
            $stats  = Group::where('group_name', 'LIKE', "%$search%")->orderBy('group_id', 'asc')->Paginate(15);
            return view('statistics.unit_index', compact('stats', 'search'));
        }
    }

    public function userView($id) {    
        $user       = User::findOrFail($id);
        $textbox    = Input::get('year');

        $overall    = DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_documents.d_istrack', 1)->where('t_document_routings.u_id', $user->u_id)->where('d_datetimerouted', 'LIKE', '%'.$textbox.'%')->count();
        $years      = DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_documents.d_istrack', 1)->where('t_document_routings.u_id', $user->u_id)->where('d_datetimerouted', 'LIKE', '%'.$textbox.'%')->get();
        $dcount     = DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_documents.d_status', 'Incoming')->where('t_document_routings.u_id', $user->u_id)->where('d_datetimerouted', 'LIKE', '%'.$textbox.'%')->count();        

        return view('statistics.users_graph', compact('user', 'years', 'dcount', 'overall'));
    }

    public function unitView($id) {
        $group      = Group::findOrFail($id);
        $members    = GroupMember::where('group_id', $id)->orderBy('u_id', 'asc')->lists('u_id');        
        
        $overall    = DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->whereIn('t_document_routings.u_id', $members)->where('t_documents.d_istrack', 1)->groupBy('t_document_routings.d_id')->get();
        $years      = DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->whereIn('t_document_routings.u_id', $members)->where('t_documents.d_istrack', 1)->groupBy('t_document_routings.d_id')->get();
        return view('statistics.unit_graph', compact('page', 'group', 'textbox', 'years', 'overall'));
    }

    public function myStatistics() {
        $user       = User::findOrFail(Auth::user()->u_id);
        $year       = Input::get('year');

        $overall    = DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_documents.d_istrack', 1)->where('d_datetimerouted', 'LIKE', "$year%")->where('t_document_routings.u_id', $user->u_id)->count();
        $years      = DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_documents.d_istrack', 1)->where('d_datetimerouted', 'LIKE', "$year%")->where('t_document_routings.u_id', $user->u_id)->get();
        $dcount     = DB::table('t_documents')->join('t_document_routings', 't_documents.d_id', '=', 't_document_routings.d_id')->where('t_documents.d_istrack', 1)->where('d_datetimerouted', 'LIKE', "$year%")->where('t_document_routings.u_id', $user->u_id)->count();
        return view('statistics.my_statistics', compact('user', 'years', 'dcount', 'overall'));        
    }

    //FOR MANAGEMENT REVIEW METRICS
    public function review() {
        // NUMBER OF RECORDS ACCOMPLISHED
        /*$docs       = Document::where('d_status', 'Incoming')->where('d_istrack', 1)->where('d_iscompleted', 1)->where('created_at', 'LIKE', "2019%")->get();
        $m_two      = 0;
        $m_five     = 0;
        $m_nine     = 0;
        $beyond     = 0;

        foreach($docs as $doc) {
            $dtrs       = $doc->d_datetimerouted;
            $dts        = strtotime($dtrs);

            $two            = date('Y-m-d', strtotime($dtrs. ' + 2 days'));
            $three          = date('Y-m-d', strtotime($dtrs. ' + 5 days'));
            $nine           = date('Y-m-d', strtotime($dtrs. ' + 9 days'));
            $adds           = date('Y-m-d', strtotime($dtrs. ' + 9 days'));

            $dcs            = $doc->d_datecompleted;

            if ( $dcs <= $two ) {                                            
                $twodays_count  = 1;
                $m_two          += $twodays_count;
            } elseif (($dcs <= $three) && ($dcs > $two)) {                                            
                $threedays_count = 1;
                $m_five          += $threedays_count;
            } elseif (($dcs <= $nine) && ($dcs > $three)) {                                            
                $ninedays_count = 1;
                $m_nine         += $ninedays_count;
            } elseif ( $dcs > $nine ) {                                            
                $beyond_counts  = 1;
                $beyond         += $beyond_counts;  
            }
        }

        echo "<br>";
        echo "1-2 Days: ".$m_two;
        echo "<br>";
        echo "3-5 Days: ".$m_five;
        echo "<br>";
        echo "6-9 Days: ".$m_nine;
        echo "<br>";
        echo "More Than 9 Days: ".$beyond;
        echo "<br><br>";*/
        // END

        //NUMBER OF RECORDS FOR 2019 PER GROUP
        /*$groups = Group::where('group_id', '<', 23)->get();
        foreach($groups as $group) {
            $members = GroupMember::where('group_id', $group->group_id)->pluck('u_id');

            $routed  = DocumentRouting::where('dr_status', 1)->where('created_at', 'LIKE', "2019%")->whereIn('u_id', $members)->groupBy('d_id')->get();

            echo $group->group_name.": ";
            echo count($routed);
            echo "<br>";
        }*/
        //END

        //NUMBER OF RECORDS FOR 2019 PER GROUP
        /*$groups = Group::where('group_id', '<', 23)->get();
        foreach($groups as $group) {
            $members = GroupMember::where('group_id', $group->group_id)->pluck('u_id');

            $routed  = Document::where('d_status', 'Outgoing')->where('created_at', 'LIKE', "2019%")->whereIn('d_routingfrom', $members)->get();

            echo $group->group_name.": ";
            echo count($routed);
            echo "<br>";
        }*/
        //END

        //STAFF WITH PENDING
        //$routes = DocumentRouting::where('dr_status', 1)->where('created_at', 'LIKE', "2019%")

        //AVERAGE SYSTEM LOGIN PER USER
        /*$users = User::where('u_active', 1)->get();

        foreach($users as $user) {
            $logs = UserLog::where('created_at', 'LIKE', "2019%")->where('u_id', $user->u_id)->count();            
            $logs = UserLog::where('u_id', $user->u_id)->where('created_at', 'LIKE', "2019-10%")->selectRaw('month(created_at) month, day(created_at) day, count(*) data')->groupBy('month', 'day')->orderBy('month')->get();            

            echo $user->u_fname." ".$user->u_lname;
            echo count($logs);
            echo $logs;
            echo "<br>";
        }*/
        //END

        //
        //$routes = DocumentRouting::where('dr_status', 1)->where('dr_completed', 1)->where('created_at', 'LIKE', "2019%")->
    }
}