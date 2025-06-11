<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DailyRoutingReason extends Model {
	protected $table = "dailyroutingreason";
	//specify the fields that must be filled
    protected $fillable = [
        'hubid', 'facilityid', 'reason', 'date'
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
