<?php

namespace App\Models;

use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    public $timestamps 		= false;
	protected $table 		= 't_users';
	protected $primaryKey 	= 'u_id';
	protected $hidden 		= ['u_password', 'remember_token'];
	protected $fillable 	= ['u_username', 'u_email', 'u_password', 'u_fname', 'u_mname', 'u_lname', 'u_mobile', 'ug_id', 'group_id', 'u_position', 'u_picture', 'u_active', 'u_administrator', 'u_head', 'u_zoom_mgr'];

	public function getAuthPassword() {
		return $this->u_password;
	}

	public function setUpasswordAttribute($value) {
		$this->attributes['u_password'] = Hash::make($value);
	}

	public function groups() {
		return $this->belongsTo('App\Models\Group', 'group_id', 'group_id');
	}
}