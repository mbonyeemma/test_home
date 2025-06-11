<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MessageRecipient extends Model {
	//specify the table to be used to store the equipment
	protected $table = "messagerecipient";
	//specify the fields that must be filled
    protected $fillable = [
        'messageid', 'recipientid', 'isread','iscc', 'isbcc'
    ];
	
	public static $rules = [
		'messageid' => 'required',
		'dayoftheweek' => 'required'
	];
	/**
	* Relationship with message
	*/
	public function message()
	{
		return $this->belongsTo('App\Models\Message', 'messageid', 'id');
	}
	/**
	* Relationship with user
	*/
	public function receiver(){
		return $this->belongsTo('App\Models\User', 'recipientid', 'id');
	}
	
}
