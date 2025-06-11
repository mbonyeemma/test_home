<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model {

	//

	protected $table = 'facility';

	public static $rules = [
		'facility' => 'required',
		'districtid' => 'required',
		'hubid' => 'required',
		'facilitylevelid'=>'required',
        'email'=>'email'
	];

	protected $fillable = [
	  'facilitycode',
	  'facility',
	  'facilitylevelid',
	  'districtid',
	  'hubid',
	  'phone',
	  'email',
	  'parentid',
	  'contactperson',
	  'physicaladdress',
	  'returnaddress',
	  'created',
	  'distancefromhub',
	  'createdby',
	  'type',
	  'facility_type'
	];

	//public $timestamps = false;

	public function district(){
        return $this->belongsTo('App\Models\District','districtid', 'id' );
    }

    public function hub(){
        return $this->belongsTo('App\Models\Hub', 'hubid', 'id');
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
