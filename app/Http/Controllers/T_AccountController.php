<?php

namespace App\Http\Controllers;

use Auth;
use View;
use Hash;
use Input;
use Carbon;
use App\Models\User;
use App\Models\Group;
use App\Models\Document;
use App\Models\UserGroup;
use App\Models\EventSeen;
use App\Models\Participant;
use App\Models\GroupMember;
use App\Models\DCommentSeen;
use App\Models\ECommentSeen;
use App\Models\DocumentRouting;
use Illuminate\Http\Request;

use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;

class T_AccountController extends Controller
{
    public function __construct() {
		$data = [ 'page' => 'Account' ];
		View::share('data', $data);
	}

	public function index() {
		$search 	= '';
    	$option 	= 'View';
    	$users 		= User::where('u_active', 1)->orderBy('u_lname', 'asc')->Paginate(25);
		return view('accounts.index', compact('search', 'option', 'users'));
	}

	public function search() {
		$search 	= Input::get('search');
    	$option 	= 'View';
    	$users 		= User::where('u_active', 1)->where('u_fname', 'LIKE', "%$search%")->orWhere('u_lname', 'LIKE', "%$search%")->orderBy('u_lname', 'asc')->Paginate(25);
		return view('accounts.index', compact('search', 'option', 'users'));	
	}

	public function add() {
		$option 	= 'Add';
		$user 		= '';
		$id 		= 0;
		$groups 	= Group::pluck('group_name', 'group_id');
		$usergroups = UserGroup::pluck('ug_name', 'ug_id');
		return view('accounts.form', compact('option', 'user', 'id', 'groups', 'usergroups'));
	}

	public function view($id) {
		$option 	= 'View';
		$user 		= User::findOrFail($id);
		$groups 	= Group::pluck('group_name', 'group_id');
		$usergroups = UserGroup::pluck('ug_name', 'ug_id');
		return view('accounts.form', compact('option', 'user', 'id', 'groups', 'usergroups'));
	}

	public function edit($id) {
		$option 	= 'Edit';
		$user 		= User::findOrFail($id);
		$groups 	= Group::pluck('group_name', 'group_id');
		$usergroups = UserGroup::pluck('ug_name', 'ug_id');
		return view('accounts.form', compact('option', 'user', 'id', 'groups', 'usergroups'));
	}

	public function save(UserRequest $request)  {
		$id  = Input::get('id');
		if($id == 0) {
			$user 					= new User;
			$user->u_username 		= Input::get('u_username');
			$user->u_email 			= Input::get('u_email');
			$user->u_password 		= Input::get('u_password');
			$user->u_fname 			= Input::get('u_fname');
			$user->u_mname 			= Input::get('u_mname');
			$user->u_lname 			= Input::get('u_lname');
			$user->u_mobile 		= Input::get('u_mobile');
			$user->ug_id 			= Input::get('usergroups');
			$user->group_id 		= Input::get('groups');
			$user->u_position 		= Input::get('u_position');		    
		    if($request->file('u_picture') != NULL) {
		      $request->file('u_picture')->move('upload/profile/', $user->u_id.".png");
		      $user->u_picture   	= 'upload/profile/'.$user->u_id.".png";
		      $user->update();
		    } else {
		      $user->u_picture  	= 'upload/profile/no-user-photo.png';
		      $user->update();
		    }
		    $user->u_active 		= Input::get('u_active');
		    $user->u_administrator 	= Input::get('u_administrator');
		    $user->u_head 			= Input::get('u_head');
		    $user->u_zoom_mgr 		= Input::get('u_zoom_mgr');
		    $user->created_at 		= Carbon\Carbon::now();
		    $user->save();

		    $gm 					= new GroupMember;
		    $gm->group_id 			= Input::get('groups');
		    $gm->u_id 				= User::orderBy('u_id', 'desc')->value('u_id');
		    $gm->created_at 		= Carbon\Carbon::now();
		    $gm->save();

            return redirect('accounts')->with('success', 'New user successfully added.');
        } else {
			$user 					= User::find($request->get('id'));
			$user->update($request->all());

			if(Input::get('u_active') == 2) {
				GroupMember::where('u_id', $id)->delete();
				User::where('u_id', $id)->update(['u_active'=>2, 'updated_at'=>Carbon\Carbon::now()]);
				return redirect('accounts')->with('success', 'Account has been deactivated.');
			} else {
				if(Auth::user()->u_administrator == 1) {
					$request->except('usergroups');
	            	$user->ug_id  	= Input::get('usergroups');
				}
			    $request->except('groups');
			    $user->group_id  	= Input::get('groups');
			    $request->except('u_picture');		    
				if($request->file('u_picture') != NULL) {
				  	$request->file('u_picture')->move('upload/profile/', $user->u_id.".png");
				   	$user->u_picture = 'upload/profile/'.$user->u_id.".png";
				   	$user->update();
				}
			    $user->updated_at 	= Carbon\Carbon::now();
			    $user->update();
	            return redirect('accounts')->with('update', 'User successfully updated.');
			}
        }
	}

	public function unapproved() {
		$search 	= '';
    	$users 		= User::where('u_active', 0)->orderBy('u_id', 'asc')->Paginate(15);
		return view('accounts.index', compact('users', 'search'));
	}

	public function deactivate($id) {
		GroupMember::where('u_id', $id)->delete();
		User::where('u_id', $id)->update(['u_active'=>2, 'updated_at'=>Carbon\Carbon::now()]);
		DCommentSeen::where('u_id', $id)->where('dcs_seen', 0)->update(['dcs_seen'=>1, 'updated_at'=>Carbon\Carbon::now()]); //For unseen document comments
		ECommentSeen::where('u_id', $id)->where('ecs_seen', 0)->update(['ecs_seen'=>1, 'updated_at'=>Carbon\Carbon::now()]); //For unseen event comments
		EventSeen::where('u_id', $id)->where('es_seen', 0)->update(['es_seen'=>1, 'updated_at'=>Carbon\Carbon::now()]); //For unseen events
		EventSeen::where('u_id', $id)->where('es_invited', 1)->where('e_confirm', 1)->where('es_confirmed', 99)->update(['es_confirmed'=>1, 'updated_at'=>Carbon\Carbon::now()]); //For un-confirmed invitations
		Participant::where('u_id', $id)->where('p_seen', 0)->update(['p_seen'=>1, 'updated_at'=>Carbon\Carbon::now()]); //For unseen meeting invitations
		DocumentRouting::where('u_id', $id)->where('dr_status', 0)->where('dr_seen', 0)->update(['dr_seen'=>1, 'updated_at'=>Carbon\Carbon::now()]); //For unseen outgoing documents
		DocumentRouting::where('u_id', $id)->where('dr_status', 1)->where('dr_completed', 1)->where('dr_seen', 0)->update(['dr_seen'=>1, 'updated_at'=>Carbon\Carbon::now()]); //For unseen incoming documents
		//DocumentRouting::where('u_id', $id)->where('dr_status', 1)->where('dr_completed', 0)->update(['dr_seen'=>1, 'dr_completed'=>1, 'dr_date'=>Carbon\Carbon::now(), 'updated_at'=>Carbon\Carbon::now()]); //For Un-actioned Tracked Incoming		

		//For un-actions tracked incoming documents
		$documents = DocumentRouting::where('u_id', $id)->where('dr_status', 1)->where('dr_completed', 0)->get();
		foreach($documents as $document) {
			$doc 				= DocumentRouting::findOrFail($document->dr_id);			
			$doc->dr_seen 		= 1;
			$doc->dr_completed 	= 1;
			$doc->dr_date 		= Carbon\Carbon::now();
			$doc->updated_at 	= Carbon\Carbon::now();
			$doc->update();

			$other_doc_id 		= DocumentRouting::where('dr_id', $document->dr_id)->value('d_id');
			$other_docs 		= DocumentRouting::where('d_id', $other_doc_id)->where('dr_completed', 0)->count();
			if($other_docs == 0) {
				Document::where('d_id', $other_doc_id)->update(['d_iscompleted'=>1, 'd_datecompleted'=>Carbon\Carbon::now()]);
			}
		}		

		return redirect('accounts')->with('success', 'Account has been deactivated.');
	}

	public function resetPassword($id) {
		$string 		= '12345678';
		$new_password 	= Hash::make($string);
		User::where('u_id', $id)->update(['u_password'=>$new_password]);
		return redirect('accounts')->with('update', 'Administrator has reset the password.');
	}
}