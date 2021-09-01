<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempName extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_tempname';
	protected $primaryKey 	= 'tn_id';	
	protected $fillable 	= ['tn_name'];
}
