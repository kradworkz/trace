<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_user_logs';
	protected $primaryKey 	= 'ul_id';	
	protected $fillable 	= ['u_id', 'ul_ip', 'ul_location', 'ul_session'];

	public function user() {
		return $this->belongsTo('App\Models\User', 'u_id', 'u_id');
	}
}
