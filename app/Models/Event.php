<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_events';
	protected $primaryKey 	= 'e_id';	
	protected $fillable 	= ['e_name', 'e_type', 'e_startdate', 'e_starttime', 'e_enddate', 'e_endtime', 'e_keywords', 'e_staff', 'e_venue', 'e_live', 'u_id', 
		'e_confirm', 'e_online', 'e_zoom', 'zs_id', 'e_zoom_pw', 'e_zoom_approved', 'e_zoom_date', 'e_zoom_link', 'e_zoom_mtgid', 'e_zoom_reason', 'e_zoom_reason'];

	public function user() {
		return $this->belongsTo('App\Models\User', 'u_id', 'u_id');
	}
}
