<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DailyRouting extends Model {
	//specify the table to be used to store the equipment
	protected $table = "dailyrouting";
	//specify the fields that must be filled
    protected $fillable = [
        'facilityid', 'dayoftheweek', 'hubid', 'transporterid','createdby', 'thedate','isactive','status'
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
	* Relationship with hub
	*/
	public function facility()
	{
		return $this->belongsTo('App\Models\Facility', 'facilityid', 'id');
	}
}
