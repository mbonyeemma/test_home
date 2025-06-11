<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EquipmentBreakDown extends Model {
	//specify the table to be used to store the equipment
	protected $table = "equipmentbreakdown";
	//specify the fields that must be filled
    protected $fillable = [
        'hubid','bikeid','mechanicid','datebrokendown','reportingdate','brokendownenddate','status','reportedby','createdby'
    ];
	
	public static $rules = [
		'facilityid' => 'required',
		'hubid' => 'required'
	];
		/**
	* Relationship with districts
	*/
	public function hub()
	{
		return $this->belongsTo('App\Models\Facility', 'hubid','id');
	}
		/**
	* Relationship with districts
	*/
	public function mechanic()
	{
		return $this->belongsTo('App\Models\Contact', 'mechanicid','id');
	}
}
