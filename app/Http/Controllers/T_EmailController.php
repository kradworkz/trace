<?php

namespace App\Http\Controllers;

use Auth;

use App\Mail\DemoMail;
//use DemoMail
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class T_EmailController extends Controller
{
    public function index() {
    	$email = Auth::user()->u_email;
        Mail::to($email)->send(new DemoMail());
        return view('/');
    }
}