<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zoom extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_zoom_settings';
	protected $primaryKey 	= 'zs_id';	
	protected $fillable 	= ['zs_email', 'zs_password', 'zs_remarks'];
}
