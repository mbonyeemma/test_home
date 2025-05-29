<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FacilityLabEquipmentBreakDownAction extends Model {
	protected $table = "facilityylabequipmentbreakdownaction";
	//specify the fields that must be filled
    protected $fillable = [
        'action','labequipmentbreakdownid','createdby'
    ];
	
	public static $rules = [
		'action' => 'required',
		'equipmentbreakdownid' => 'required'
	];
		/**
	* Relationship with breakdowns
	*/
	public function equipmentbreakdown()
	{
		return $this->belongsTo('App\Models\FacilityLabEquipmentBreakDown', 'facilitylabequipmentbreakdownid','id');
	}
}
