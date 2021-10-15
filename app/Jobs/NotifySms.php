<?php

namespace App\Jobs;

use App\Models\Action;
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
            $actions = Document::where('d_id', $this->doc_id)->value('d_actions');

            if($actions != null){
                $acts = Action::select('a_action')->whereIn('a_id',json_decode($actions))->get();
                $val = "";
                $count = count($acts);
                $c = $count - 1;
                if($count >0){
                    foreach($acts as $key=>$act){
                        $val .=  $act['a_action']; 
                        ($count>1) ? ($key != $c) ? $val .= ', ' : '' : '';
                    }
                }else{
                    $val = '';
                }
            }else{
                $val = '';
            }

            if(!empty($user)){
                $no = $user->u_mobile;
                $name = $user->u_fname.' '.$user->u_mname.', '.$user->u_lname;
                $email = $user->u_email;
                $text = "Hi ".$name.", You have been tagged to the document with Routing Slip : ".$this->doc." and a subject : ".$this->title.". ".$val;
                
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
