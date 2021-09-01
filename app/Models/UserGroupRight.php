<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroupRight extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_ug_rights';
	protected $primaryKey 	= 'ugr_id';	
	protected $fillable 	= ['ug_id', 'ur_id', 'ugr_view', 'ugr_add', 'ugr_edit', 'ugr_delete'];

	public function urights() {
    	return $this->belongsTo('App\Models\UserRight', 'ur_id', 'ur_id');
    }
}
