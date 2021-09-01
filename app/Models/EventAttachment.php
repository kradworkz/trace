<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventAttachment extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_event_attachments';
	protected $primaryKey 	= 'ea_id';	
	protected $fillable 	= ['e_id', 'ea_file'];
}
