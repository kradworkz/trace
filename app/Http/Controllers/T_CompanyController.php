<?php

namespace App\Http\Controllers;

use Auth;
use View;
use Input;
use Carbon;
use App\Models\Company;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class T_CompanyController extends Controller
{
    public function __construct() {
		$data = [ 'page' => 'Company Information' ];
		View::share('data', $data);
	}

	public function index() {
		$search 	= '';
    	$option 	= 'View';
    	$companies 	= Company::orderBy('c_id', 'asc')->Paginate(20);
		return view('companies.index', compact('search', 'option', 'companies'));
	}

	public function search() {
		$search 	= Input::get('search');
    	$option 	= 'View';
    	$companies 	= Company::where('c_name', 'LIKE', "%$search%")->orWhere('c_address', 'LIKE', "%$search%")->orWhere('c_acronym', 'LIKE', "%$search%")->orderBy('c_id', 'asc')->Paginate(20);
		return view('companies.index', compact('search', 'option', 'companies'));
	}

	public function add() {
		$option 	= 'Add';
		$company 	= '';
		$id 		= 0;		
		return view('companies.form', compact('option', 'company', 'id'));
	}

	public function view($id) {
		$option 	= 'View';
		$company 	= Company::findOrFail($id);		
		return view('companies.form', compact('option', 'company', 'id'));
	}

	public function edit($id) {
		$option 	= 'Edit';
		$company 	= Company::findOrFail($id);		
		return view('companies.form', compact('option', 'company', 'id'));
	}

	public function save(Request $request) {
		if($request->get('id') == 0) {
            $company 				= Company::create($request->all());
		    $company->created_at 	= Carbon\Carbon::now();
		    $company->u_id 			= Auth::user()->u_id;
		    $company->save();
            return redirect('company')->with('success', 'New company successfully added.');
        } else {
			$company 				= Company::find($request->get('id'));
			$company->update($request->all());
			$company->u_id 			= Auth::user()->u_id;
		    $company->updated_at 	= Carbon\Carbon::now();		    
		    $company->update();
            return redirect('company')->with('update', 'Company successfully updated.');
        }
	}
}
