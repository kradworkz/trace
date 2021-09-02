<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotifySms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id, $title, $doc, $doc_id;
  
    public function __construct($id = null, $title = null, $doc = null,$doc_id = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->doc = $doc;
        $this->doc_id = $doc_id;
    }

    public function handle()
    {

        if($this->id != null){
            $user = User::where('u_id',$this->id)->first();
            if(!empty($user)){
                $no = $user->u_mobile;
                $name = $user->u_fname.' '.$user->u_mname.', '.$user->u_lname;
                $email = $user->u_email;
                $text = "Hi ".$name.", You have been tagged to the document with Routing Slip : ".$this->doc." and a subject : ".$this->title.".";
                
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
                
            }
        }
        return true;
    }
}
