<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SampleTracking extends Model {
	//specify the table to be used to store the equipment
	protected $table = "sampletracking";
	/**
	* Relationship with facility
	*/
	public function facility()
	{
		return $this->belongsTo('App\Models\Facility', 'facilityid', 'id');
	}
	
	public function transporter()
	{
		return $this->belongsTo('App\Models\Staff', 'sampletransportedby', 'id' );
	}
}
