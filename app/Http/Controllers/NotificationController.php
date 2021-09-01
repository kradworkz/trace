<?php

namespace App\Http\Controllers;

use App\Services\NotifyUser as Nu;
use App\Models\DocumentAttachment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Jobs\NotifyUser;
use App\Mail\EmailUser;

class NotificationController extends Controller
{
    public function sendSMS(NotifyUser $notify){

        $notify->sms(148,'New title');
        // $ch = curl_init();

        // curl_setopt($ch, CURLOPT_URL, 'https://api.dost9.ph/sms/messages');
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // $post = array(
        //     'recipient' => '09557650803',
        //     'message' => 'hehehehhhe',
        //     'title' => 'Trace Notification System'
        // );
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        // $result = curl_exec($ch);
        // if (curl_errno($ch)) {
        //     echo 'Error:' . curl_error($ch);
        // }
        // curl_close($ch);

        // return $result; 
    }

    public function sendJob($id,$doc){
        $user = $id;
        $title = 'test';
        $doc_no = 'testing';
        $document_id = $doc;
        NotifyUser::dispatch($tagged[$i],$title,$doc_no,$document_id)->delay(now()->addSeconds(10));
    }


    public function sendEmail($id)
    {

        $email = "kradjumli@gmail.com";
        $name = "Ra-ouf Jumli";
        $message = "testing message";
        $title = "DOST TESTING MAIL";
        $docid = 2;
        return Nu::email($email,$name,$message,$title,$docid);

        // return env('TYPE');
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, 'https://api.dost9.ph/sms/messages');
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // $post = array(
        //     'recipient' => '639557650803',
        //     'message' => 'HAHA',
        //     'title' => 'Trace Notification System'
        // );
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        // return $result = curl_exec($ch);
        // if (curl_errno($ch)) {
        //     echo 'Error:' . curl_error($ch);
        // }
        // curl_close($ch);
        // $files = DocumentAttachment::where('d_id',$id)->first();
        // return public_path().'\\'.str_replace('/', '\\', $files['da_file']);
        $email = "kradjumli@gmail.com";
        $name = "Ra-ouf Jumli";
        $mess = "testing message";
        $title = "DOST TESTING MAIL";
        Mail::to($email)->send(new EmailUser($name,$mess,$title,$id));

        // return public_path('upload\outgoing\2021-OUT08-ORD-5\wew.docx') ;
        // $files = [
        //     public_path('upload/outgoing/2021-OUT08-ORD-5/wew.docx'),
        //     public_path('upload/outgoing/2021-OUT08-ORD-5/wew.docx'),
        // ];

        // $files = DocumentAttachment::get();
        // $data = array("name" => 'Krad', "body" => 'test');

		// Mail::send("email.demo", $data, function($message){
        //     $message->to("kradjumli@gmail.com", "Ra-ouf Jumli")->subject("Trace Notification : Haha");
        //     $message->from("dost9ict@gmail.com","DOST IX - Trace");     
        //     $message->attach(public_path("upload").'\outgoing\2021-OUT08-ORD-5\wew.docx');
        // });
		
    //     'mailer' => [
    //         'class' => 'yii\swiftmailer\Mailer',
    //         'viewPath' => '@common/mail',
    //          'useFileTransport' => false,//set this property to false to send mails to real email addresses
    //          //comment the following array to send mail using php's mail function
    //          'transport' => [
    //              'class' => 'Swift_SmtpTransport',
    //              'host' => 'smtp.gmail.com',
    //              'username' => 'dost9ict@gmail.com',
    //              'password' => 'efsepgobqhceacai',
    //              'port' => '587',
    //              'encryption' => 'tls',
    //              'streamOptions'=>[
    //                 'ssl'=>[
    //                      'verify_peer'=>false,
    //                      'verify_peer_name'=>false,
    //                      'allow_self_signed'=>true
    //                ]
    //              ]
    //          ],
    //  ],
        
	}
	
}
