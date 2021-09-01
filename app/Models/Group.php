<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_groups';
	protected $primaryKey 	= 'group_id';	
	protected $fillable 	= ['group_name', 'u_id'];

	public function groupmembers() {
		return $this->hasMany('App\Models\GroupMember', 'group_id', 'group_id');
	}

	public function gmembers() {
		return $this->belongsToMany('App\Models\User', 't_groupmembers', 'group_id', 'u_id')->orderBy('gm_id')->withTimestamps();
	}
}