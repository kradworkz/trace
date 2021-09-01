<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentRouting extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_document_routings';
	protected $primaryKey 	= 'dr_id';	
	protected $fillable 	= ['d_id', 'u_id', 'dr_seen', 'dr_completed', 'dr_date', 'dr_status'];

	public function docs() {
		return $this->belongsTo('App\Models\Document', 'd_id', 'd_id');
	}

	public function seen() {
		return $this->belongsTo('App\Models\User', 'u_id', 'u_id');
	}
}
