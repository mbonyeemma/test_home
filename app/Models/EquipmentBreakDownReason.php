<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EquipmentBreakDownReason extends Model {
	//specify the table to be used to store the equipment
	protected $table = "equipmentBreakdownreason";
	//specify the fields that must be filled
    protected $fillable = [
        'reason','equipmentbreakdownid','createdby'
    ];
	
	public static $rules = [
		'reason' => 'required',
		'equipmentbreakdownid' => 'required'
	];
		/**
	* Relationship with districts
	*/
	public function equipmentbreakdown()
	{
		return $this->belongsTo('App\Models\EquipmentBreakDown', 'equipmentbreakdownid','id');
	}
}
