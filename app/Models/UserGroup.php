<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_user_groups';
	protected $primaryKey 	= 'ug_id';	
	protected $fillable 	= ['ug_name'];
}
