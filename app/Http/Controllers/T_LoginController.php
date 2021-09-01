<?php

namespace App\Http\Controllers;

use Auth;
use Input;
use Carbon;
use Session;
use App\Models\User;
use App\Models\Group;
use App\Models\Region;
use App\Models\UserLog;
use Illuminate\Http\Request;
//use Request;

use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;

class T_LoginController extends Controller
{
  public function login() {
 		$page  			= 'Login';
  	$error_message 	= '';
		if(Session::has('error_message')) {
			$error_message = Session::get('error_message');
			Session::put('error_message', '');
		}
		return view('login.login', compact('error_message', 'page'));
  }

  public function handleLogin(Request $request) {
    $userdata = [   'u_username'=> $request->get('u_username'),
                    'password'  => $request->get('u_password'),
                    'u_active'  => 1  ];      



    if(Auth::attempt($userdata, true)) {
      $ul               = new UserLog;
      $ul->u_id         = Auth::id();
      $ul->ul_ip        = $request->getClientIp();
      //$ul->ul_ip        = $request::ip();
      //$ul->ul_location  = Location::get($ul->ul_ip);
      $ul->ul_session   = Session::getId();
      $ul->created_at   = Carbon\Carbon::now();
      $ul->save();
      return redirect('dashboard');
    }
    return redirect('/')->with('error_message', 'Invalid username or password.');
  }

  public function logout() {    
    UserLog::where('ul_session', Session::getId())->update(['updated_at' => Carbon\Carbon::now()]);
    Session::flush();
    Auth::logout();
    return redirect('/');
  }

  public function register() {
  	$page 		= 'Register';
    $groups   = Group::pluck('group_name', 'group_id');    
  	return view('login.register', compact('page', 'groups'));
  }

  public function save(UserRequest $request) {
    $user = User::create($request->all());
    $request->except('groups');
    $user->group_id  = Input::get('groups');
    $request->except('u_picture');
    if($request->file('u_picture') != NULL) {
      $request->file('u_picture')->move('upload/profile/', $user->u_id.".png");
      $user->u_picture  = 'upload/profile/'.$user->u_id.".png";
      $user->update();
    } else {
      $user->u_picture  = 'upload/profile/no-user-photo.png';
      $user->update();
    }
    $user->created_at = Carbon\Carbon::now();
    $user->save();
    return redirect('/')->with('success', 'Please wait for your account to be confirmed by the administrator. Thank you.');
  }

  public function maintenance() {
    $page       = 'Maintenance';
    return view('login.maintenance', compact('page'));
  }

}
