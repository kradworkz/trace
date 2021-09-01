<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Name extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_names';
	protected $primaryKey 	= 'n_id';	
	protected $fillable 	= ['n_name'];

}