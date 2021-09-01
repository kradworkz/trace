<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_document_types';
	protected $primaryKey 	= 'dt_id';	
	protected $fillable 	= ['dt_type'];

	public function documents() {
		return $this->hasMany('App\Models\Document', 'dt_id', 'dt_id');
	}
}