<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_comments';
	protected $primaryKey 	= 'comm_id';	
	protected $fillable 	= ['comm_document', 'comm_event', 'comm_reference', 'u_id', 'comm_text', 'comm_rd', 'comm_for_rd'];

	public function users() {
		return $this->belongsTo('App\Models\User', 'u_id', 'u_id');
	}

	public function document() {
		return $this->belongsTo('App\Models\Document', 'comm_reference', 'd_id');
	}

	public function event() {
		return $this->belongsTo('App\Models\Event', 'comm_reference', 'e_id');
	}

	public function subject() {
		return $this->belongsTo('App\Models\Event', 'comm_reference', 'e_id');
	}
}
