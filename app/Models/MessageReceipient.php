<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MessageRecipient extends Model {
	//specify the table to be used to store the equipment
	protected $table = "messagerecipient";
	//specify the fields that must be filled
    protected $fillable = [
        'createdby', 'status', 'isactive','thedate'
    ];
	
	/**
	* Relationship with message
	*/
	public function message()
	{
		return $this->hasOne('App\Models\Message', 'messageid','id' );
	}
	/**
	* Relationship with user
	*/
	public function receipient(){
		return $this->hasOne('App\Models\User', 'recipientid' 'id');
	}
}
