<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IP extends Model {

	//

	protected $table = 'ip';

	public static $rules = [
		'ip' => 'required'
	];
	
	protected $fillable = [
		'ip',
	 	'full_name',
        'address',
        'focal_person',
        'focal_person_contact',
        'description',
        'funding_source',
        'created',
        'createdby'];

	public $timestamps = false;

	public function facilities(){
        return $this->hasMany('AppD\Models\Facility');
    }

    public function hubs(){
        return $this->hasMany('App\Models\Hub','ipID');
    }

    public static function ipsArr(){
		$arr=array();
		foreach(IP::all() AS $ip){
			$arr[$ip->id]=$ip->ip;
		}
		return $arr;
	}



}



