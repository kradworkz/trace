<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Group;
use App\Models\UserLog;
use App\Models\Document;
use App\Models\GroupMember;
use App\Models\DocumentRouting;
use Illuminate\Http\Request;

class T_ManagementReview extends Controller
{
    public function index() {
    	$incoming 			= Document::where('d_status', 'Incoming')->where('d_istrack', 1)->where('created_at', 'LIKE', "2019-%")->count();

    	//TOTAL NUMBER OF RECORDS PER STATUS
    	$pending 			= Document::where('d_status', 'Incoming')->where('d_istrack', 1)->where('d_iscompleted', 0)->where('created_at', 'LIKE', "2019-%")->count();
    	$completed 			= Document::where('d_status', 'Incoming')->where('d_istrack', 1)->where('d_iscompleted', 1)->where('created_at', 'LIKE', "2019-%")->get();
    	$total_ontimes 		= 0;
        $total_beyonds 		= 0;
    	foreach($completed as $complete) {
    		$routed         = $complete->d_datetimerouted;
            $route_con      = strtotime($routed);

            $done 			= $complete->d_datecompleted;
            $done_con 		= strtotime($done);

            $diff 			= abs($done_con - $route_con);

            $years 			= floor($diff / (365*60*60*24)); 
            $months 		= floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
            $days 			= floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24)); 

            if($days <= 9) {
            	$ontime_counts = 1;
                $total_ontimes += $ontime_counts;
            } else {
            	$beyond_counts = 1;
                $total_beyonds += $beyond_counts;
            }
    	}
    	//END

    	//NUMBER OF RECORDS FOR 2019 PER GROUP
    	/*$inc  				= Document::where('d_status', 'Incoming')->where('d_istrack', 1)->where('created_at', 'LIKE', "2019-%")->pluck('d_id');
    	$ord_mems			= GroupMember::where('group_id', 1)->pluck('u_id');
    	$groups 			= Group::where('group_id', '<', 23)->get();


    	foreach($groups as $group) {    		
    		$members 		= GroupMember::where('group_id', $group->group_id)->get();;
    		foreach($members as $member) {
    			$routes		= DocumentRouting::where('u_id', $member->u_id)->whereIn('d_id', $inc)->get();
    		}

    	}*/

    	//echo $dr;
    	//$ord_active 		= User::where('u_active', 1)->whereIn('u_id', $ord_mems);

    	//END


    	

    }
}
