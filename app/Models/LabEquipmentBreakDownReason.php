<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FacilityLabEquipmentBreakDownReason extends Model {
	//specify the table to be used to store the equipment
	protected $table = "facilitylabequipmentBreakdownreason";
	//specify the fields that must be filled
    protected $fillable = [
        'reason','facilitylabequipmentbreakdownid','createdby'
    ];
	
	public static $rules = [
		'reason' => 'required',
		'facilitylabequipmentbreakdownid' => 'required'
	];
		/**
	* Relationship with districts
	*/
	public function equipmentbreakdown()
	{
		return $this->belongsTo('App\Models\LabEquipmentBreakDown', 'facilitylabequipmentbreakdownid','id');
	}
}
