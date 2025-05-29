<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Package extends Model {
	protected $table = "package";
	/**
	* Relationship with hub
	*/
	public function hub()
	{
		return $this->belongsTo('App\Models\Facility', 'hubid', 'id');
	}
	protected $fillable = ['barcode','facilityid','hubid','test_type','sample_type','date_picked','created_by','final_destination','type','numberofsamples','is_tracked_from_facility','is_batch'];
	/**
	* Relationship with hub
	*/
	public function facility()
	{
		return $this->belongsTo('App\Models\Facility', 'facilityid', 'id');
	}

	public function children()
	{
	    return $this->hasMany(Package::class, 'parent_id');
	}

	public function parent()
	{
	    return $this->belongsTo(Package::class, 'parent_id');
	}

	public function packageMovementEvent(){
		return $this->belongsTo('App\Models\PackageMovementEvent', 'latest_event_id', 'id');
	}

	// To access
	//$category->children; // sub-categories collection

	//$category->parent; // parent instance


}