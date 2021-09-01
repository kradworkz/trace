<?php

namespace App\Http\Controllers;

use Auth;
use View;
use Carbon;
use App\Models\ActionDone;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class T_ActionDoneController extends Controller
{
	public function __construct() {
		$data = [ 'page' => 'Action Done' ];
		View::share('data', $data);
	}

    public function index() {
    	$search 		= '';
    	if(Auth::user()->ug_id == 1) {
    		$actions 	= ActionDone::where('ad_rd', 0)->Paginate(20);
    	} else {
    		$actions 	= ActionDone::where('ad_seen', 0)->Paginate(20);
    	}
    	
    	if(Auth::user()->ug_id == 1) {
    		ActionDone::where('ad_rd', 0)->update(['ad_rd'=>1]);
    	} else {
    		ActionDone::where('ad_seen', 0)->update(['ad_seen'=>1]);
    	}
    	
    	return view('actionsdone.index', compact('search', 'actions'));    	
    }

    public function actionRead() {
        if(Auth::user()->ug_id == 1) {
            ActionDone::where('ad_rd', 0)->update(['ad_rd'=>1, 'updated_at'=>Carbon\Carbon::now()]);
        } else {
            ActionDone::where('ad_seen', 0)->update(['ad_rd'=>1, 'updated_at'=>Carbon\Carbon::now()]);
        }

        return redirect('dashboard')->with('success', 'Actions done marked as read.');
    }
}
