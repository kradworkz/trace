<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ECommentSeen extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_ecomment_seen';
	protected $primaryKey 	= 'ecs_id';	
	protected $fillable 	= ['e_id', 'comm_id', 'u_id', 'ecs_seen'];

	public function subject() {
		return $this->belongsTo('App\Models\Event', 'e_id', 'e_id');
	}

	public function comment() {
		return $this->belongsTo('App\Models\Comment', 'comm_id', 'comm_id');
	}
}
