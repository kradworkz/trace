<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public $timestamps 		= false;
	protected $table 		= 't_documents';
	protected $primaryKey 	= 'd_id';
	protected $fillable 	= [ 'd_status', 'd_subject', 'dt_id', 'd_documentdate', 'd_datereceived', 'd_datesent', 'd_sender', 'd_addressee', 'c_id', 'd_keywords', 'd_remarks', 'd_routingslip', 
								'd_incomingreference', 'd_routingthru', 'd_routingfrom', 'd_actions', 'd_datetimerouted', 'd_istrack', 'd_iscompleted', 'd_datecompleted',
								'd_encoded_by', 'd_group_encoded' ];

	public function dtypes() {
		return $this->belongsTo('App\Models\DocumentType', 'dt_id', 'dt_id');
	}

	public function companies() {
		return $this->belongsTo('App\Models\Company', 'c_id', 'c_id');
	}

	public function user() {
		return $this->belongsTo('App\Models\User', 'd_routingfrom', 'u_id');
	}
}