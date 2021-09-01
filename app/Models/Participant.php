<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_participants';
	protected $primaryKey 	= 'p_id';	
	protected $fillable 	= ['m_id', 'u_id', 'p_ord', 'p_seen', 'p_notif'];

	public function users() {
		return $this->belongsTo('App\Models\User', 'u_id', 'u_id');
	}
}
