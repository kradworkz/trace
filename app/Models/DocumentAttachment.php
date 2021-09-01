<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentAttachment extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_document_attachments';	
	protected $primaryKey 	= 'da_id';	
	protected $fillable 	= ['d_id', 'da_file'];
}
