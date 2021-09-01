<?php

namespace App\Http\Controllers;

use DB;
use PDF;
use Auth;
use View;
use Excel;
use Input;
use Carbon;
use App\Models\User;
use App\Models\Group;
use App\Models\UserLog;
use App\Models\Document;
use App\Models\DocumentRouting;
use Illuminate\Http\Request;

class T_ReportController extends Controller
{
    public function __construct() {
        $data = [ 'page' => 'Report' ];
        View::share('data', $data);
    }

    public function index() {
    	$search 			= '';
    	$option 			= 'View';
    	//$users 				= User::where('u_active', 1)->orderBy('u_lname', 'asc')->get();    	
        if(Auth::user()->ug_id == 2) {
            $id_check       = DocumentRouting::where('dr_status', 1)->where('dr_completed', 0)->pluck('d_id');
            $d_id_check     = DocumentRouting::whereIn('d_id', $id_check)->where('u_id', Auth::user()->u_id)->pluck('d_id');
            $documents      = Document::whereIn('d_id', $d_id_check)->orderBy('d_datetimerouted', 'asc')->Paginate(50);
        } else {
            $documents      = Document::where('d_status', 'Incoming')->where('d_routingthru', '!=', NULL)->where('d_istrack', 1)->where('d_iscompleted', 0)->orderBy('d_datetimerouted', 'asc')->Paginate(50);      
        }
    	
		return view('reports.index', compact('search', 'option', 'documents', 'search'));
    }

    public function search() {
    	$option 			= 'View';
    	$search 			= Input::get('individualTag');    	
    	$users 				= User::where('u_active', 1)->orderBy('u_lname', 'asc')->get();
    	$pendocs 			= DocumentRouting::where('u_id', $search)->pluck('d_id');
    	$documents 			= Document::where('d_routingthru', '!=', NULL)->whereIn('d_id', $pendocs)->orderBy('d_datetimerouted', 'asc')->Paginate(50);
    	return view('reports.index', compact('search', 'option', 'documents', 'users', 'search'));
    }

    public function printMain() {
		$date 		= Carbon\Carbon::now();
		$documents 	= Document::where('d_status', 'Incoming')->where('d_routingthru', '!=', NULL)->where('d_istrack', 1)->where('d_iscompleted', 0)->orderBy('d_datetimerouted', 'asc')->get();
		$pdf 		= PDF::loadView('reports.report_main', compact('year', 'documents', 'month', 'date'))->setPaper('legal', 'landscape');;
		return $pdf->stream('MonthlyReport_'.$date.'.pdf');
    }

    public function downloadPending() {
        $date       = Carbon\Carbon::now();
        $documents  = DocumentRouting::where('dr_status', 1)->where('dr_completed', 0)->where('created_at', '<', '2019-11-11 00:00:00')->orderBy('u_id', 'asc')->get();
        $pdf        = PDF::loadView('reports.pending', compact('year', 'documents', 'month', 'date'))->setPaper('legal', 'landscape');
        return $pdf->stream('MonthlyReport_'.$date.'.pdf');
    }

    public function utilizationIndex() {
        return view('reports.utilization_index');
    }

    public function downloadPDF() {
        $year           = Input::get('year');
        $utilization    = Input::get('reportUtilization');

        if($utilization == 'Outgoing') {
            $documents  = Document::where('d_status', 'Outgoing')->where('created_at', 'LIKE', "$year%")->orderBy('d_id', 'asc')->get();
            $groups     = Group::where('group_id', '!=', 23)->where('group_id', '!=', 24)->get();
            $users      = User::where('u_active', 1)->orderBy('u_fname', 'asc')->get();
            $pdf        = PDF::loadView('pdf.pdf_outgoing', compact('year', 'documents', 'groups', 'users'))->setPaper('legal', 'landscape');
        return $pdf->stream('OutgoingUtilizationFor_'.$year.'.pdf');
        } elseif($utilization == 'Event') {
            $year       = Input::get('year');
            $groups     = Group::where('group_id', '!=', 23)->where('group_id', '!=', 24)->get();
            $pdf        = PDF::loadView('pdf.pdf_event', compact('year', 'groups'))->setPaper('legal', 'landscape');
            return $pdf->stream('EventUtilizationFor_'.$year.'.pdf');
        } elseif($utilization == 'User') {
            $year       = Input::get('year');
            $users      = User::where('u_active', 1)->orderBy('u_fname', 'asc')->get();
            $pdf        = PDF::loadView('pdf.pdf_userlogs', compact('year', 'users'))->setPaper('legal', 'landscape');
            return $pdf->stream('UserLogsFor_'.$year.'.pdf');
        }
    }

    public function costReduction() {
        return view('reports.cost_reduction');
    }

    public function costredReport() {
        $decide         = Input::get('wholeData');
        $year           = Input::get('year');
        $docType        = Input::get('docType');
        $print_cost     = Input::get('printCost');
        
        if($docType == "Incoming") {
            $docs       = Document::where('d_status', 'Incoming')->where('d_routingthru', '!=', 0)->where('created_at', 'LIKE', "%$year%")->count();
            $d_id       = Document::where('d_status', 'Incoming')->where('d_routingthru', '!=', 0)->where('created_at', 'LIKE', "%$year%")->pluck('d_id');
        } elseif($docType == "Outgoing") {
            $docs       = Document::where('d_status', 'Outgoing')->where('created_at', 'LIKE', "%$year%")->count();
            $d_id       = Document::where('d_status', 'Outgoing')->where('created_at', 'LIKE', "%$year%")->pluck('d_id');
        } else {
            $docs       = Document::where('d_routingthru', '!=', 0)->where('created_at', 'LIKE', "%$year%")->count();
            $d_id       = Document::where('d_routingthru', '!=', 0)->where('created_at', 'LIKE', "%$year%")->pluck('d_id');
        }
        $people         = DocumentRouting::whereIn('d_id', $d_id)->count();
        $num_unprinted  = $people - $docs;
        $cost           = $num_unprinted * $print_cost;

        header('Content-type: application/excel');
        $filename   = 'TRACE_CostReduction.xls';
        header('Content-Disposition: attachment; filename='.$filename);     

        $data = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
        <head>
            <meta charset="utf-8">
            <title>TRACE Cost Reduction</title>          
            <style type="text/css">             
                body, html {
                    font-family: "Times New Roman";
                }

                .title {
                    font-size: 14px;
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <br>
            <br>            
            <table width="100%">
                <thead>
                    <tr>
                        <th>TOTAL NO. OF DOCUMENTS</th>
                        <th>TOTAL NO. OF TAGGED PEOPLE</th>
                        <th>COST PER PRINT</th>
                        <th>NUMBER OF UNPRINTED COPIES</th>
                        <th>COST OF UNPRINTED COPIES</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td>'.$docs.'</td>
                    <td>'.$people.'</td>
                    <td>'.$print_cost.'</td>
                    <td>'.$num_unprinted.'</td>
                    <td>'.$cost.'</td>
                </tr>
                </tbody>
            </table>
        </body></html>';

        echo $data;    
    }

    public function userStatReport() {
        $yr     = Input::get('year');
        $logs   = UserLog::where('created_at', 'LIKE', "$year%")->groupBy('u_id')->get();
     
        header('Content-type: application/excel');
        $filename = 'UserLogs-'.$year.'.xls';
        header('Content-Disposition: attachment; filename='.$filename);     

        $data = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
        <head>
            <meta charset="utf-8">
            <title>TRACE User Logs</title>          
            <style type="text/css">             
                body, html {
                    font-family: "Times New Roman";
                }

                .title {
                    font-size: 14px;
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <br>
            <br>            
            <table width="100%">
                <thead>
                    <tr>
                        <th class="title">EMPLOYEE NAME</th>
                        <th class="title">1-2 DAYS</th>
                        <th class="title">3-5 DAYS</th>
                        <th class="title">6-9 DAYS</th>
                        <th class="title">MORE THAN 9 DAYS</th>
                        <th class="title">TOTAL TRACKED</th>
                        <th class="title">TOTAL INCOMING</th>
                        <th class="title">PENDING</th>
                        <th class="title">UNOPENED</th>
                    </tr>
                </thead>
                <tbody>
                ';
                foreach($logs as $log) {                    
                    $data   .= '
                <tr>
                    <td>'.$log->user->u_fname.' '.$log->user->u_lname.'</td>
                    <td>'.UserLog::where('u_id', $log->u_id)->where('created_at', 'LIKE', "$year-01-%")->count().'</td>
                    <td>'.UserLog::where('u_id', $log->u_id)->where('created_at', 'LIKE', "$year-02-%")->count().'</td>
                    <td>'.UserLog::where('u_id', $log->u_id)->where('created_at', 'LIKE', "$year-03-%")->count().'</td>
                    <td>'.UserLog::where('u_id', $log->u_id)->where('created_at', 'LIKE', "$year-04-%")->count().'</td>
                    <td>'.UserLog::where('u_id', $log->u_id)->where('created_at', 'LIKE', "$year-05-%")->count().'</td>
                </tr>';
                }
                $data .= '
                </tbody>
            </table>
        </body></html>';

        echo $data;
    }
}