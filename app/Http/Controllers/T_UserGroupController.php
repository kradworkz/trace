<?php

namespace App\Http\Controllers;

use View;
use Input;
use Carbon;
use App\Models\UserGroup;
use App\Models\UserRight;
use App\Models\UserGroupRight;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class T_UserGroupController extends Controller
{
    public function __construct() {
		$data = [ 'page' => 'User Groups' ];
		View::share('data', $data);
	}

	public function index() {
		$num 		= 0;
    	$search 	= '';
    	$option 	= 'View';
    	$usergroups	= UserGroup::orderBy('ug_id', 'asc')->Paginate(15);    	
    	return view('usergroups.index', compact('usergroups', 'search', 'option', 'num'));
    }

    public function search() {
        $num 		= 0;
    	$search 	= Input::get('search');
    	$option 	= 'Search';
    	$usergroups	= UserGroup::where('ug_name', 'LIKE', "%$search%")->orderBy('ug_id', 'asc')->Paginate(15);    	
    	return view('usergroups.index', compact('usergroups', 'search', 'option', 'num'));
    }

    public function edit($id) {
    	$usergroup  = UserGroup::find($id);
        $option     = 'Edit';        
        $rights     = UserGroupRight::where('ug_id', $id)->get();
        return view('usergroups.form', compact('option', 'id', 'usergroup', 'rights'));
    }

    public function save(Request $request) {
        $urname                 = UserGroup::find($request->get('id'));
        $urname->ug_name        = Input::get('ug_name');
        $urname->updated_at     = Carbon\Carbon::now();
        $urname->update();

        $id                     = Input::get('editID');
        if($id != NULL) {
            $right              = UserGroupRight::findOrFail($id);
            $right->ugr_view    = Input::get('ugr_view');
            $right->ugr_add     = Input::get('ugr_add');
            $right->ugr_edit    = Input::get('ugr_edit');
            $right->ugr_delete  = Input::get('ugr_delete');
            $right->updated_at  = Carbon\Carbon::now();
            $right->update();
        }        

        return redirect('user_groups/edit/'.$request->get('id'))->with('update', 'User Rights successfully updated.');
    }
}
