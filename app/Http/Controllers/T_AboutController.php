<?php

namespace App\Http\Controllers;

use View;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class T_AboutController extends Controller
{
    public function __construct() {
		$data = [ 'page' => 'About The System' ];
		View::share('data', $data);
	}

	public function index() {
		return view('about.index');
	}

}