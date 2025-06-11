<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityLabEquipment extends Model {

	protected $table = 'facilitylabequipment';

	public static $rules = [
		'facility_id' => 'required' 
	];

	protected $fillable = [
	  'facility_id', 
	  'labequipment_id',
	  'model',
	  'serial_number',
	  'location',
	  'procurement_type',
	  'purchase_date',
	  'delivery_date',
	  'verification_date',
	  'installation_date',
	  'spare_parts',
	  'warranty',
	  'life_span',
	  'service_frequency',
	  'service_contract']; 

	//public $timestamps = false;

	public function district(){
        return $this->belongsTo('App\Models\District','districtid', 'id' );
    }

    public function hub(){
        return $this->belongsTo('App\Models\Facility', 'parentid', 'id');
    }
	public function ip(){
        return $this->belongsTo('App\Models\Organization', 'ipid', 'id');
    }
	public function healthregion(){
		return $this->belongsTo('App\Models\HeathRegion','healthregionid', 'id');
    }

    public function facilitylevel(){
        return $this->belongsTo('App\Models\FacilityLevel', 'facilitylevelid', 'id');
    }
}
