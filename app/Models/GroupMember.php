<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_group_members';
	protected $primaryKey 	= 'gm_id';
	protected $fillable 	= ['group_id', 'u_id'];

	public function users() {
		return $this->hasMany('App\Models\User', 'u_id', 'u_id');
	}
}