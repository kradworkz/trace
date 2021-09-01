<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_actions';
	protected $primaryKey 	= 'a_id';	
	protected $fillable 	= ['a_action', 'a_number'];
}
