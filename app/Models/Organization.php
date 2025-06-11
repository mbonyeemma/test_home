<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model {

	//
	protected $table = 'organization';

	public static $rules = [
		'type' => 'required'
	];
	
	protected $fillable = [
		'type',
        'name',
		'healthregionid',
        'address',
        'telephonenumber',
        'emailaddress',
        'created',
        'createdby'];

	//public $timestamps = false;
	public function healthregion(){
		return $this->belongsTo('App\Models\HeathRegion','healthregionid', 'id');
    }
	public function supportagency(){
		return $this->belongsTo('App\Models\SupportAgency','supportagencyid', 'id');
    }
	
	public function facilities(){
        return $this->hasMany('App\Models\Facility', 'id', 'ipid');
    }
	
    public function hubs(){
        return $this->hasMany('App\Models\Hub','ipid');
    }

 }



