<?php

namespace App\Mail;

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
        ($this->id != null) ? $files = DocumentAttachment::where('d_id',$this->id)->get() : '';
        
        $mess= $this->view('email.demo')->with('name',$this->name)->with('body',$this->message);
        $mess->subject($this->title);

        if($this->id != null){
            foreach($files as $file){
                $mess->attach(public_path().'/'.$file['da_file']);
            }   
        }

        return $mess;
    }
}
