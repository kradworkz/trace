<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionDone extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_action_done';
	protected $primaryKey 	= 'ad_id';	
	protected $fillable 	= ['comm_id', 'd_id', 'u_id', 'ad_seen', 'ad_rd'];

	public function user() {
		return $this->belongsTo('App\Models\User', 'u_id', 'u_id');
	}

	public function comment() {
		return $this->belongsTo('App\Models\Comment', 'comm_id', 'comm_id');
	}

	public function document() {
		return $this->belongsTo('App\Models\Document', 'd_id', 'd_id');
	}
}
