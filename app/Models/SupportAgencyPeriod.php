<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportAgencyPeriod extends Model {

	//
	protected $table = 'supportagencyperiod';

	
	protected $fillable = [
        'organizationid',
        'supportagencyid',
        'createdby'];

	//public $timestamps = false;
	 public function organization(){
        return $this->belongsTo('App\Models\Organization', 'organizationid', 'id');
    }
	public function supportagency(){
        return $this->belongsTo('App\Models\SupportAgency', 'supportagencyid', 'id');
    }

 }



