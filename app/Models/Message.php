<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {

	protected $table = 'message';

	public static $rules = [
		'content' => 'required',
		'senderid' => 'required'
	];
	
	protected $fillable = [
		'senderid',
	 	'parentid',
        'subject',
        'content',
        'html',
        'type',
        'subtype',
		'refidsss',
        'created',
        'createdby'];

	
	public function receipients(){
        return $this->hasMany('App\Models\MessageRecipient','id');
    }
	public function sender(){
		return $this->hasOne('App\Models\User', 'id', 'senderid' );
	}
}



