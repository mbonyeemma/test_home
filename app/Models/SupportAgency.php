<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportAgency extends Model {

	//

	protected $table = 'supportagency';

	
	protected $fillable = [
        'name',
        'address',
        'telephonenumber',
        'emailaddress',
        'created',
        'createdby'];

	//public $timestamps = false;
	
	 public function ips(){
        return $this->hasMany('App\Models\Organization', 'id', 'supportagencyid');
    }
	public function supportagencyperiod(){
        return $this->hasOne('App\Models\SupportAgencyPeriod', 'id', 'supportagencyid');
    }

 }



