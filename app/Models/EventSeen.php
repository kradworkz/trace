<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSeen extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_event_seen';
	protected $primaryKey 	= 'es_id';
	protected $fillable 	= ['e_id', 'u_id', 'es_seen', 'es_invited', 'e_confirm', 'es_confirmed', 'es_reason'];

	public function seen() {
		return $this->belongsTo('App\Models\User', 'u_id', 'u_id');
	}

	public function ev() {
		return $this->belongsTo('App\Models\Event', 'e_id', 'e_id');
	}
}
