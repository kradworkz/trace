<?php

namespace App\Services;

use App\Mail\EmailUser;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotifyUser {

    public static function sms($users,$title,$doc) {

        $count = count($users);

        for($i=0; $i<$count; $i++) {
            $user = User::where('u_id',$users[$i])->first();
            $message = "You have been tagged to the document with  Routing Slip : ".$doc." and a subject : ".$title.".";
            $no = $user->u_mobile;
            $name = $user->u_fname.' '.$user->u_mname.', '.$user->u_lname;
            $email = "kradjumli@gmail.com";
            $text = "Hi ".$name.", You have been tagged to the document with Routing Slip : ".$doc." and a subject : ".$title.".";

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.dost9.ph/sms/messages');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $post = array(
                'recipient' => $no,
                'message' => $text,
                'title' => 'Trace Notification System'
            );
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    
            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
    
            $data = array("name" => $name, "body" => $message);
            Mail::send("email.demo", $data, function($message) use ($name, $email,$title) {
                $message->to($email, $name)->subject("Trace Notification : ".$title);
                $message->from("dost9ict@gmail.com","DOST IX - Trace");
            });
    
            return true; 
        }

        // $message = "You have been tagged to the document with  Routing Slip : '".$doc."' and a subject '".$title."'.";
        // $user = User::where('u_id',$users[$i])->first();
        // $no = $user->u_mobile;
        // $name = $user->u_fname.' '.$user->u_mname.', '.$user->u_lname;
        // $email = "kradjumli@gmail.com";
        // $text = "Hi ".$name.", You have been tagged to the document with Routing Slip : '".$doc."' and a subject '".$title."'.";
        
        // $ch = curl_init();

        // curl_setopt($ch, CURLOPT_URL, 'https://api.dost9.ph/sms/messages');
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // $post = array(
        //     'recipient' => $no,
        //     'message' => $text,
        //     'title' => 'Trace Notification System'
        // );
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        // $result = curl_exec($ch);
        // if (curl_errno($ch)) {
        //     echo 'Error:' . curl_error($ch);
        // }
        // curl_close($ch);

        // $data = array("name" => $name, "body" => $message);
        // Mail::send("email.demo", $data, function($message) use ($name, $email,$title) {
        //     $message->to($email, $name)->subject("Trace Notification : ".$title);
        //     $message->from("dost9ict@gmail.com","DOST IX - Trace");
        // });

        // return true; 

    }

    public static function email($email,$name,$mess,$title,$id) {

     
        Mail::to($email)->send(new EmailUser($name,$mess,$title,$id));

    }
}