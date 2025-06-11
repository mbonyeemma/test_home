<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class NftActivities extends Model {
	protected $table = "nft_activities";
	//specify the fields that must be filled
    protected $fillable = [
        'unique_id','activity_start_date','from_location_name','from_location_id','to_location_name','to_location_id','sample_description',
        'status','riders_name','delivered_on','entered_by'
    ];

    public $timestamps = false;
	
	// public static $rules = [
	// 	'hubid' => 'hubid'
	// ];
	//ALTER TABLE `nft_activities` CHANGE `to_location_name` `to_location_name` VARCHAR(100) NULL DEFAULT NULL;
	//ALTER TABLE `nft_activities` CHANGE `delivered_on` `delivered_on` TIMESTAMP NULL DEFAULT NULL;
		
}
