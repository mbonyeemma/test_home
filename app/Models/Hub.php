<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hub extends Model {

	protected $table = 'hub';

	protected $fillable = ['name','email','address','healthregionid','implementingpartnerid', 'coordinatorid'];

	//public $timestamps = false;

	public function facility(){
        return $this->hasMany('App\Models\Facility','hubid');
    }

    public function healthregion(){
		return $this->belongsTo('App\Models\HeathRegion','healthregionid', 'id');
    }

    public static function hubsList(){
    	return Hub::leftjoin('ips AS i','i.id', '=','h.ipID')->select('h.*','i.ip')->from("hubs AS h")->get();
    }

    public function district(){
      return $this->belongsTo('App\Models\District','districtid', 'id' );
    }
}
