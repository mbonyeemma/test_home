<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RoutingSchedule extends Model {
	//specify the table to be used to store the equipment
	protected $table = "routingschedule";
	//specify the fields that must be filled
    protected $fillable = [
        'facilityid', 'dayoftheweek', 'hubid','createdby', 'status', 'isactive','thedate', 'bikeid'
    ];
	
	public static $rules = [
		'hubid' => 'required',
		'dayoftheweek' => 'required'
	];
	/**
	* Relationship with hub
	*/
	public function hub()
	{
		return $this->belongsTo('App\Models\Facility', 'hubid', 'id');
	}
	/**
	* Relationship with bike
	*/
	public function bike(){
		return $this->belongsTo('App\Models\Eqipment', 'bikeid', 'id');
	}
	
	/**
	* Relationship with hub
	*/
	public function facility()
	{
		return $this->belongsTo('App\Models\Facility', 'facilityid', 'id');
	}
}
