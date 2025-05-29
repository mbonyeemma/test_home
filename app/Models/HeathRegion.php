<?php
namespace App\Models;

class HeathRegion extends \Eloquent
{
	protected $table = "healthregion";
	public function healthregion(){
		return $this->hasMany('App\Models\Hub', 'healthregionid', 'id');
	}
}