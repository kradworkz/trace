<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_companies';
	protected $primaryKey 	= 'c_id';	
	protected $fillable 	= ['c_name', 'c_address', 'c_acronym', 'c_telephone', 'c_fax', 'c_email', 'c_website', 'u_id'];
}