<?php

namespace App\Http\Controllers;

use View;
use Input;
use Carbon;
use App\Models\DocumentType;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class T_DocumentTypeController extends Controller
{
    public function __construct() {
		$data = [ 'page' => 'Document Types' ];
		View::share('data', $data);
	}

	public function index() {
		$search 	= '';		
    	$types 		= DocumentType::orderBy('dt_id', 'asc')->Paginate(25);
		return view('types.index', compact('search', 'types'));
	}

	public function search() {
		$search 	= Input::get('search');    	
    	$types 		= DocumentType::where('dt_type', 'LIKE', "%$search%")->orderBy('dt_id', 'asc')->Paginate(25);
		return view('types.index', compact('search', 'types'));
	}

	public function add() {
		$id 		= 0;
    	$type 	   	= '';
    	$option 	= 'Add';
    	return view('types.form', compact('id', 'option', 'type'));
	}

	public function edit($id) {		
    	$type 	   	= DocumentType::findOrFail($id);
    	$option 	= 'Add';
    	return view('types.form', compact('id', 'option', 'type'));
	}

	public function save(Request $request) {
		$id 		= Input::get('id');
		if($id == 0) {
			$type 				= new DocumentType;
			$type->dt_type 		= Input::get('dt_type');
			$type->created_at 	= Carbon\Carbon::now();
			$type->save();

			return redirect('document_types')->with('success', 'Document Type was successfully edited.');
		} else {
			$type 				= DocumentType::findOrFail($id);
			$type->dt_type 		= Input::get('dt_type');
			$type->updated_at 	= Carbon\Carbon::now();
			$type->update();

			return redirect('document_types')->with('update', 'Document Type was successfully updated!');
		}	
	}
}