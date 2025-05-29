<?php
namespace App\Models;

class District extends \Eloquent
{
	protected $table = "district";

		/**
	* Relationship with districts
	*/
	public function Region()
	{
		return $this->belongsTo('App\Models\Region');
	}
}

