<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model {
	//specify the table to be used to store the equipment
	protected $table = "staff";
	//specify the fields that must be filled
    protected $fillable = [
        'facilityid', 'firstname', 'lastname', 'othernames', 'emailaddress', 'telephonenumber', 
        'hasbbtraining', 'hasdefensiveriding', 'designation', 'type', 'nationalid','permitexpirydate'
    ];
	
	public static $rules = [
		'facilityid' => 'required',
		'firstname' => 'required',
		'lastname' => 'required',
		'telephonenumber'=>'required',
		'email' => 'required|email|unique'
	];
	/**
	* Relationship with facility/hub
	*/
	public function facility()
	{
		return $this->belongsTo('App\Models\Facility', 'hubid', 'id');
	}
	/**
	* Relationship with bike
	*/
	public function bike()
	{
		return $this->hasOne('App\Models\Equipment', 'id', 'motorbikeid');
	}
	public function user()
	{
		return $this->hasOne('App\User', 'id', 'user_id');
	}
	
	public function getFullName(){
		return $this->firstname.' '.$this->lastname;
	}
}
