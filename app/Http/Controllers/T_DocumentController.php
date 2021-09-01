<?php

namespace App\Http\Controllers;

use View;
use App\Models\Document;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class T_DocumentController extends Controller
{
    public function __construct() {
		$data = [ 'page' => 'Document Summary' ];
		View::share('data', $data);
	}

	public function rushIndex() {
		$search 		= '';
		$documents 		= Document::where('d_status', 'Incoming')->where('d_istrack', 1)->where('d_iscompleted', 0)->where('d_actions', 'LIKE', "1,%")->orderBy('d_id', 'asc')->Paginate(20);
		return view('documents.rush', compact('search', 'documents'));
	}
}