<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model {
	//specify the table to be used to store the contact
	protected $table = "contact";
	//specify the fields that must be filled
    protected $fillable = [
        'organizationid', 'ipid', 'districtid','firstname', 'lastname', 'othernames', 'emailaddress', 'telephonenumber', 'type', 'category', 'isactive'
    ];
	
	public static $rules = [
		'facilityid' => 'required',
		'firstname' => 'required',
		'lastname' => 'required',
		'telephonenumber'=>'required',
		'email' => 'required|email|unique'
	];
		/**
	* Relationship with ips
	*/
	public function organization()
	{
		return $this->belongsTo('App\Models\Organization', 'organizationid', 'id');
	}
	/**
	* Get full name
	**/
	function getFullName(){
		return $this->firstname.' '.$this->lastname.' '.$this->othernames;
	}
	/**
	* Relationship with DLPF districts
	*/
	public function dlfpDistrict()
	{
		return $this->belongsTo('App\Models\District', 'dlfpdistrictid', 'id');
	}

	/**
	* Relationship with hub
	*/
	public function hub()
	{
		return $this->belongsTo('App\Models\Hub', 'hubid', 'id');
	}


	/**
	* Get full name
	**/
}
