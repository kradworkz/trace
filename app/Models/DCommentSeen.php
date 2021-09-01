<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DCommentSeen extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_dcomment_seen';
	protected $primaryKey 	= 'dcs_id';	
	protected $fillable 	= ['d_id', 'comm_id', 'u_id', 'dcs_seen'];

	public function subject() {
		return $this->belongsTo('App\Models\Document', 'd_id', 'd_id');
	}

	public function comment() {
		return $this->belongsTo('App\Models\Comment', 'comm_id', 'comm_id');
	}
}
