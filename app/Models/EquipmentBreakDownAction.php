<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EquipmentBreakDownAction extends Model {
	protected $table = "equipmentbreakdownaction";
	//specify the fields that must be filled
    protected $fillable = [
        'action','equipmentbreakdownid','createdby'
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
		return $this->belongsTo('App\Models\EquipmentBreakDown', 'equipmentbreakdownid','id');
	}
}
