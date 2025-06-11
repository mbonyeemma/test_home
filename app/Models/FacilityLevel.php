<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityLevel extends Model {

	//

	protected $table = 'facilitylevel';

	public static $rules = [
		'level' => 'required'
	];
	
	protected $fillable = ['level','created','createdby'];

	public $timestamps = false;

	public function facilities(){
        return $this->hasMany('App\Models\Facility');
    }
	public function facility(){
        return $this->hasMany('App\Models\Facility');
    }
    public static function facilityLevelsArr(){
		$arr=array();
		foreach(FacilityLevel::all() AS $fl){
			$arr[$fl->id]=$fl->level;
		}
		return $arr;
	}


}



