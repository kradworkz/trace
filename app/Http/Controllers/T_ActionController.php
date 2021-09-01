<?php

namespace App\Http\Controllers;

use View;
use Input;
use Carbon;
use App\Models\Action;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class T_ActionController extends Controller
{
    public function __construct() {
		$data = [ 'page' => 'Action Settings' ];
		View::share('data', $data);
	}

	public function index() {
		$actions = Action::orderBy('a_number', 'asc')->Paginate(20);
		return view('actions.index', compact('actions'));
	}

	public function add() {
		$id 	= 0;
		$action = '';
		$option = 'Add';
		return view('actions.form', compact('id', 'action', 'option'));
	}

	public function edit($id) {		
    	$action = Action::findOrFail($id);
    	$option = 'Edit';
    	return view('actions.form', compact('id', 'option', 'action'));
	}

	public function save(Request $request) {
		$id 		= Input::get('id');
		if($id == 0) {
			$action 			= new Action;
			$action->a_action 	= Input::get('a_action');
			$action->a_number 	= Input::get('a_number');
			$action->created_at = Carbon\Carbon::now();
			$action->save();

			return redirect('action_settings')->with('success', 'Action setting was successfully edited.');
		} else {
			$action 			= Action::findOrFail($id);
			$action->a_action 	= Input::get('a_action');
			$action->a_number 	= Input::get('a_number');
			$action->updated_at = Carbon\Carbon::now();
			$action->update();

			return redirect('action_settings')->with('update', 'Action setting was successfully updated!');
		}	
	}
}