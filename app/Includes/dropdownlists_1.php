<?php

	# This class require_onces functions to access and use the different drop down lists within
	# this application

	/**
	 * function to return the results of an options query as array. This function assumes that
	 * the query returns two columns optionvalue and optiontext which correspond to the corresponding key
	 * and values respectively. 
	 * 
	 * The selection of the names is to avoid name collisions with database reserved words
	 * 
	 * @param String $query The database query
	 * 
	 * @return Array of values for the query 
	 */
	function getOptionValuesFromDatabaseQuery($query) {
		//$conn = getDatabaseConnection(); 
		//echo $query;
		$result = DB::select($query);
		//print_r($result);
		//exit;
		$valuesarray = array();
		foreach ($result as $value) {
			$valuesarray[$value->optionvalue]	= htmlentities($value->optiontext);
		}
		//print_r($valuesarray);
		//exit;
		return decodeHtmlEntitiesInArray($valuesarray);
	}
     function getLookUps($look_up_id){
     	$valuesquery = "SELECT lookuptypevalue AS optionvalue, lookupvaluedescription AS optiontext FROM lookuptypevalue WHERE lookuptypeid = ".$look_up_id." ORDER BY optiontext";
		
		return getOptionValuesFromDatabaseQuery($valuesquery);
     } 
	//get all hubs
	function getAllHubs(){
	$first = \App\Models\Facility::where('id', '=', 2489);
	$hubs = \App\Models\Facility::whereColumn('parentid', '=', 'id')->where('id', '<', 10000)->union($first)->get()->pluck('name', 'id');

	//	$hubs = \App\Models\Facility::whereColumn('parentid', '=', 'id')->where('id', '<', 10000)->pluck('name', 'id');
		return $hubs;
	}

	//get all hubs for IP
	function getAllHubsForIP($ipID){

		$iphubs = \App\Models\Facility::join('organization', 'facility.ipid', '=', 'organization.id')
							->whereColumn('facility.parentid', '=', 'facility.id')
							->where('organization.id', $ipID)
                            ->pluck('facility.name','facility.id');
		return $iphubs;

		
	}

	function getPoeSites(){
		$sites = \App\Models\Facility::where('facilitylevelid', '=', 14)->pluck('name', 'id');
		return $sites;
	}

	function getReferenceLabs(){
		$labs = \App\Models\Facility::where('code', '=', 10000)->pluck('name', 'id');
		return $labs;
	}

	function getReferenceLabs_and_hubs()
	{
		$labs = \DB::select("SELECT id, name FROM facility WHERE is_ref_lab_and_hub = 1");
		return $arrayName = ['RefLabs' => $labs];
	}

	function getFacilityIdFromDHIS2Code($dhiscode)
	{
	$facility = \DB::select("select id, parentid from facility WHERE dhis2_uid = '$dhiscode'");
	return $facility[0]->id;
	}

	function getTestTypes($require_destination = ''){
		if(!$require_destination){
			$valuesquery = "SELECT id AS optionvalue, name AS optiontext FROM testtypes WHERE is_active = 1 ORDER BY optiontext";		
			return getOptionValuesFromDatabaseQuery($valuesquery);
		}else{
			$query = "SELECT id, name, require_destination FROM testtypes WHERE is_active = 1";
		return \DB::select($query);
		}
	}
	
	function getSiteOfllectionTypes(){
		$sites = [1=>'Community', 2=>'Isolation', 3=>'POEs', 4=>'Facility'];
		return $sites;
	}
	function getAllFacilities(){
		$facilities = \App\Models\Facility::where('id', '>', 0)->pluck('name', 'id');
		return $facilities;
	}
	function getAllFacilitiesWitTheirhHups(){
		 $facilities = \DB::select("SELECT name, id, parentid FROM facility");
		return $facilities;
	}
	function getAllPoes(){
		$facilities = \App\Models\Facility::where('id', '>', 0)
		->Where('facilitylevelid', '14')->pluck('name', 'id');
		return $facilities;
	}
	function getAllHealthRgions(){
		$healthregions = \App\Models\HeathRegion::pluck('name', 'id');
		return $healthregions;
	}
	function getAllRgions(){
		$regions = \App\Models\Region::pluck('name', 'id');
		return $regions;
	}
	function getAllSupportAgencies(){
		$supportagencies = \App\Models\SupportAgency::pluck('name', 'id');
		return $supportagencies;
	}
		
	function getAllFacilityLevels(){
		$levels =  \App\Models\FacilityLevel::pluck('level', 'id');
		return $levels;
	}
	function getAllDistricts(){
		$districts =  \App\Models\District::pluck('name', 'id');
		return $districts;
	}
		
	function getAllIps(){
		return \App\Models\Organization::where('type', 1)->pluck('name','id');
	}
	function getBikesForHub($hubid){
		return \App\Models\Euipment::where('hubid', $hubid)->pluck('name','id');
	}
	
	/*
	* Get name and id of all hubs at leves H/iv, hospital or RRH which are not yet hubs
	*/
	function getAllHubCandidateFacilities(){
	$candidatefacilities =	\App\Models\Facility::where('parentid', '!=', '')
      ->where(function($q) {
          $q->where('facilitylevelid', 1)
            ->orWhere('facilitylevelid', 9)
			->orWhere('facilitylevelid', 11);
      })->pluck('name','id');
	  return $candidatefacilities;
	}
	
	//get all facilities
	function getAllUnAssignedBikes(){
		if(Auth::user()->hasRole('hub_coordinator')){
			$sampletransporters = \App\Models\Equipment::where('type', '=', 1)->where('isassigned', 0)->where('hubid', Auth::user()->hubid)->pluck('numberplate', 'id');
		}else{
			$sampletransporters = \App\Models\Equipment::where('type', '=', 1)->where('isassigned', 0)->pluck('numberplate', 'id');
		}
		return $sampletransporters;
	}
	
	function getFacilitiesforHub($hubid){
		$facilities = \App\Models\Facility::where('parentid', '=', $hubid)->orWhere('id', '=',$hubid)->pluck('name', 'id');
		return $facilities;
	}
	
	function getHubforFacility($facility_id){
		$facility = \DB::select("select id, parentid from facility WHERE id = ".$facility_id);
		return $facility[0]->parentid == ''?$facility[0]->id:$facility[0]->parentid;
	}
	
	function getDistrictsForHub($hubid){
		$valuesquery = "SELECT d.id as optionvalue, d.name as optiontext FROM facility f
		INNER JOIN district d ON(f.districtid = d.id)  
		WHERE  f.parentid = '".$hubid."' GROUP BY d.id, d.name ORDER BY optiontext";
		//dd($valuesquery);
		return getOptionValuesFromDatabaseQuery($valuesquery);
	}
	function getMechanicforHub($id){
		$mechanics = '';
		if($id){
			$mechanics = \App\Models\Contact::where('type', '=', 1)->where('category', 4)->where('isactive', 1)->where('hubid', $id)->pluck('firstname', 'id');
		}
		return $mechanics;
	}
	
	function getUnassignedBikesforHub($hubid){
		$valuesquery = "SELECT id as optionvalue, numberplate as optiontext FROM equipment  
		WHERE  hubid = '".$hubid."' ORDER BY optiontext";
		return getOptionValuesFromDatabaseQuery($valuesquery);
	}
	function getAssignedBikesforHub($hubid){
		$valuesquery = "SELECT id as optionvalue, numberplate as optiontext FROM equipment  
		WHERE  hubid = '".$hubid."' ORDER BY optiontext";
		return getOptionValuesFromDatabaseQuery($valuesquery);
	}
	function getSampleTransportersforHub($hubid){
		$valuesquery = "SELECT id as optionvalue, CONCAT(`firstname`,' ',`lastname`) as optiontext FROM staff  
		WHERE  hubid = '".$hubid."' ORDER BY optiontext";
		return getOptionValuesFromDatabaseQuery($valuesquery);
	}
	
	function getGenerateHtmlforAjaxSelect($options, $empty_string = 'Select One'){
		$select_string = '<option value="">'.$empty_string.'</option>';
		foreach($options as $key => $value){
			$select_string .= '<option value="'.$key.'">'.$value.'</option>';
		}
		return $select_string;
	}
	function getCphlPackageStatus(){
		$status_array = array(1 => 'In Transit',2 => 'Delivered', 3 => 'Received');
		return $status_array;
	}
	function getHubPackageStatus(){
		$status_array = array(1 => 'In Transit', 2 => 'Delivered', 3 => 'Received',
								4 => 'Waiting Pickup', 5 => 'In transit', 6 => 'Deliverd', 7 => 'Received');
		return $status_array;
	}
	/*
		function getHubPackageStatus(){
		$status_array = array(1 => 'In Transit to hub', 2 => 'Delivered at hub', 3 => 'Received at hub',
								4 => 'Waiting Pickup', 5 => 'In transit to CPHL', 6 => 'Deliverd at Cphl', 7 => 'Received at Cphl');
		return $status_array;
	}
	*/
	function getHubPackageStatusLabel(){
		$status_array = array(0=>'Waiting for Pickup', 1 => 'In Transit to ', 2 => 'Delivered at ', 3 => 'Received at ');
		return $status_array;
	}

	function getRefLabForTestType($test_type_id){	
		

	}


	// Get specimen total per hub per specimen type

	function getTotalSpecimen($hub_id, $specimen_id,$from, $to='')
	{

			$jacobTotal = "SELECT sum(p.numberofsamples) as total from package p 
								left join facility fc on fc.id = p.facilityid
								left join testtypes tt on tt.id = p.test_type
								WHERE p.created_at BETWEEN '".$from."' AND '". $to."'
								AND fc.parentid = ". $hub_id."
								AND  p.test_type = ". $specimen_id."";

						$totals = '';
							if($hub_id != '' && $specimen_id != ''){
								$result = DB::select($jacobTotal);
								if(count($result)){
									$totals = $result[0]->total;
								}
							}
				return $totals;
	}

	function returnFormatedDate($month_year_string)
	{
		$month = substr($month_year_string, 0, 3);  // returns month
		$year = substr($month_year_string, -2);    // returns year

		$conctntvalue = $month." "."'".$year;
		return $conctntvalue;
	}

function getTestIdFromName($test_code)
{
	$test = \DB::select("select id from testtypes WHERE snomed_code = '$test_code'");
	// return $test[0]->id == '' ? $test[0]->id : "";
	return $test[0]->id;

}

	function returnTotal($testtypeid, $month_created)
	{
		$results = '';
		$total = "SELECT total
							FROM
							(
							SELECT month_created,month_full,testtype, id,sum(numberofsamples) as total
							FROM (SELECT distinct pe.package_id, tt.name as testtype, tt.id as id , p.numberofsamples,DATE_FORMAT(p.created_at , '%Y%m') as month_created,DATE_FORMAT(p.created_at , '%Y%M') as month_full from package p
							inner join packagemovement_events pe ON pe.package_id = p.id
							inner join testtypes tt ON p.test_type = tt.id
							where pe.location = p.hubid 
							AND p.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)) as tx
							group by 1,2,3,4
							order by month(1)) as alltx
							WHERE id = '".$testtypeid."' AND month_created = '".$month_created."'
							group by 1
							order by month(1)";
						if($testtypeid != '' && $month_created != ''){
							$return = DB::select($total);
								if(count($return)){
									$results = $return[0]->total;
								}
							}
					return $results;

	}
?>
