<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_settings';
	protected $primaryKey 	= 's_id';	
	protected $fillable 	= ['s_sysname', 's_abbr', 's_footer', 's_pending_days', 's_background'];
}
