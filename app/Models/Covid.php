<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Covid extends Model {
	protected $table = "covid";
	//specify the fields that must be filled
    protected $fillable = [
        'facilityid', 'numberofsamples', 'transactiondate'
    ];
	/**
	* Relationship with hub
	*/
	public function facility()
	{
		return $this->belongsTo('App\Models\Facility', 'facilityid', 'id');
	}
}
