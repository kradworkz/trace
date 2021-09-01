<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use View;
use Input;
use Carbon;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class T_GroupController extends Controller
{
    public function __construct() {
		$data = [ 'page' => 'Groups' ];
		View::share('data', $data);
	}

	public function index() {
		$search 	= '';
    	$option 	= 'View';
    	$groups 	= Group::orderBy('group_id', 'asc')->Paginate(20);
		return view('groups.index', compact('search', 'option', 'groups'));
	}

	public function search() {
		$search 	= Input::get('search');
    	$option 	= 'View';
    	$groups 	= Group::where('group_name', 'LIKE', "%$search%")->orderBy('group_id', 'asc')->Paginate(15);
		return view('groups.index', compact('search', 'option', 'groups'));
	}

	public function add() {
		$id 		= 0;
    	$group 	   = '';
    	$option 	= 'Add';
    	return view('groups.form', compact('id', 'option', 'group'));
	}

	public function view($id) {
		$option 	= 'View';
		$group 		= Group::findOrFail($id);
		$count 		= GroupMember::where('group_id', $id)->get();
		$members 	= GroupMember::where('group_id', $id)->Paginate(15);
		return view('groups.form', compact('option', 'group', 'id', 'count', 'members'));
	}

	public function edit($id) {
		$option 	= 'Edit';
		$group 		= Group::findOrFail($id);
		$count 		= GroupMember::where('group_id', $id)->get();
		$members 	= GroupMember::where('group_id', $id)->Paginate(15);
		$group_id 	= GroupMember::where('group_id', $id)->pluck('u_id');
		$users 		= User::whereNotIn('u_id', $group_id)->where('u_active', 1)->select('u_id', DB::raw('CONCAT(u_lname, ", ", u_fname) as full_name'))->orderBy('u_lname')->pluck('full_name', 'u_id');				
		return view('groups.form', compact('option', 'group', 'id', 'count', 'members', 'users'));
	}

	public function save(Request $request) {
		$id 		= Input::get('id');
		if($id == 0) {
			$group 				= new Group;
			$group->group_name 	= Input::get('group_name');
			$group->created_at 	= Carbon\Carbon::now();
			$group->save();

			/*Input::except('gmembers');
			if(Input::get('gmembers') == NULL) {
				$group->gmembers()->attach(Auth::user()->u_id);
			} else {
				$group->gmembers()->attach(Input::get('gmembers'));
			}*/

			return redirect('groups')->with('success', 'Group was successfully edited.');
		} else {
			$group 				= Group::findOrFail($id);
			/*Input::except('gmembers');
			if(Input::get('gmembers') == NULL) {
				$group->gmembers()->sync(array(Auth::user()->u_id));
			} else {
				$group->gmembers()->sync(Input::get('gmembers'), true);
			}
			$group->touch();*/
			$group->group_name 	= Input::get('group_name');
			$group->updated_at 	= Carbon\Carbon::now();
			$group->update();

			$add 			= Input::get('gms');
			$count 			= count($add);
			$items 			= array();
			for ( $i=0; $i<$count; $i++ ) {
				$item 		= [ 'group_id'	=> $id,
								'u_id'		=> $add[$i],
								'created_at'=> Carbon\Carbon::now()];
				$items[]	= $item;
			}
			DB::table('t_group_members')->insert($items);

			return redirect('groups')->with('update', 'Group was successfully updated!');
		}		
	}

	public function deleteMember($id) {
		GroupMember::where('gm_id', $id)->delete();
		return redirect('groups')->with('success', 'Member has been removed from the group.');
	}
}