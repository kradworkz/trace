<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_meetings';
	protected $primaryKey 	= 'm_id';
	protected $fillable 	= [	'm_startdate', 'm_enddate', 'm_starttime', 'm_endtime', 'm_tstartdate', 'm_tenddate', 'm_tstarttime', 'm_tendtime', 'm_purpose', 'm_destination', 'm_others', 
								'm_encodedby', 'm_status', 'm_reason', 'm_datechecked', 'm_postponedby', 'm_stat' ];

}