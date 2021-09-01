<?php

namespace App\Http\Controllers;

use View;
use Input;
use Carbon;
use App\Models\Setting;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class T_SettingsController extends Controller
{
    public function __construct() {
		$data = [ 'page' => 'Site Settings' ];
		View::share('data', $data);
	}

	public function view() {
    	$search 	= '';
		$option 	= 'Add';
		$settings 	= Setting::find(1);
    	return view('settings.form', compact('search', 'option', 'settings'));
    }

    public function save() {
		$settings 	= Setting::find(1);
		$settings->update(Input::all());
		$settings->updated_at = Carbon\Carbon::now();
		$settings->update();
		return redirect('settings')->with('update', 'Site settings successfully updated.');
	}
}
