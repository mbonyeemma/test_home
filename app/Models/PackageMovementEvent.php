<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PackageMovementEvent extends Model {
	protected $table = "packagemovement_events";
	protected $fillable = [
        'package_id','source','destination','status','	longitude','latitude','place_name','created_by','created_at','category_id'];	


    public function package()
    {
    	return $this->belongsTo('App\Models\Package', 'package_id', 'id');
    }

    public function facility()
	{
		return $this->belongsTo('App\Models\Facility', 'location', 'id');
	}

}
