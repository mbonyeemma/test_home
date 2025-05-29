<?php

namespace App\Repositories;

use App\Models\Message as Message;
use \DB as DB;
use Auth;

class AllViewsRepository
{

    static function facilities()
    {
		$hubid = Auth::getUser()->hubid; 
		$facilitydropdown = array_merge_maintain_keys(array(''=>"Facility"),getFacilitiesforHub($hubid));
		return $facilitydropdown;
    }
	
	 static function bikes()
    {
		$hubid = Auth::getUser()->hubid; 
		$bikes = array_merge_maintain_keys(array(''=>"Motorcycle"), getAssignedBikesforHub($hubid));
    }
	
	static function transporters()
    {
		$hubid = Auth::getUser()->hubid; 
		$transporters = array_merge_maintain_keys(array('' => 'Transporter' ),getSampleTransportersforHub($hubid));
    }
}