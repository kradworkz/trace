<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRight extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_user_rights';
	protected $primaryKey 	= 'ur_id';	
	protected $fillable 	= ['ur_name'];	
}
