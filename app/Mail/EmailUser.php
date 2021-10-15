<?php

namespace App\Mail;

use App\Models\Action;
use App\Models\Document;
use App\Models\DocumentAttachment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailUser extends Mailable
{
    use Queueable, SerializesModels;

    protected $name, $message, $title ,$id;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name = null, $message = null, $title = null, $id = null)
    {
        $this->name = $name;
        $this->message = $message;
        $this->title = $title;
        $this->id = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        $link = Document::where('d_id', $this->id)->value('d_status');
        
        $actions = Document::where('d_id', $this->id)->value('d_actions');
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


        $link = ($link == "Outgoing") ? 'outgoing/view/' : 'incoming/view/' ;

        ($this->id != null) ? $files = DocumentAttachment::where('d_id',$this->id)->get() : '';

        $mess= $this->view('email.demo')->with('name',$this->name)->with('body',$this->message)->with('link',$link)->with('val',$val)->with('id',$this->id);
        $mess->subject($this->title);

        if($this->id != null){
            foreach($files as $file){
                $mess->attach(public_path().'/'.$file['da_file']);
            }   
        }

        return $mess;
    }
}
