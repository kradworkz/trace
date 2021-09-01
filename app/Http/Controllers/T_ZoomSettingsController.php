<?php

namespace App\Http\Controllers;

use View;
use Input;
use Carbon;
use App\Models\Zoom;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class T_ZoomSettingsController extends Controller
{
    public function __construct() {
		$data = [ 'page' => 'Zoom Settings' ];
		View::share('data', $data);
	}

	public function edit($id) {		
    	$zoom 	   	= Zoom::findOrFail($id);
    	$option 	= 'Edit';
    	return view('zoom.settings', compact('id', 'option', 'zoom'));
	}

	public function save(Request $request) {
		$id 		= Input::get('id');
		$zoom 				= Zoom::findOrFail($id);
		$zoom->zs_email 	= Input::get('zs_email');
		$zoom->zs_password 	= Input::get('zs_password');
		$zoom->updated_at 	= Carbon\Carbon::now();
		$zoom->update();

		return redirect('zoom_settings/'.$id)->with('update', 'Zoom settings were successfully edited!');
	}
}