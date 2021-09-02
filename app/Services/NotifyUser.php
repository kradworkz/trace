<?php

namespace App\Services;

use App\Mail\EmailUser;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotifyUser {

    public static function sms($users,$title,$doc) {

       

    }

    public static function email($email,$name,$mess,$title,$id) {

     
        Mail::to($email)->send(new EmailUser($name,$mess,$title,$id));

    }
}