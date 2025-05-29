<?php
namespace Khill\Lavacharts\Examples\Controllers;
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \Lava;
use Auth;
use Session;
use \Entrust;

use \App\Models\Equipment as Equipment;

class DashboardController extends Controller {

    public function __construct() {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {
    	
		$facilities_not_visited_cond = '';
		$where_condition = '';
		$where_condition .= " AND e.status = 2";

		$query = "SELECT e.id, lv.lookupvaluedescription as name, e.model, e.serial_number, e.status, e.location, f.hubname, e.installation_date FROM facilitylabequipment e
        INNER JOIN facility f ON(e.hubid = f.id)
		INNER JOIN lookuptypevalue lv ON (lv.lookuptypevalue = e.labequipment_id AND lv.lookuptypeid = 27)
        WHERE e.id != '' ".$where_condition."
        ORDER BY lv.lookupvaluedescription";
		//echo $query; exit;
        $lab_equipment_broken_down = \DB::select($query);

		$facilities_not_visited_cond = "AND f.parentid = '".Auth::user()->hubid."'";
		//facilities not visited last week
		$query = 'select f.id, f.name, h.hubname from 
		facility f
		INNER JOIN facility h ON (f.parentid = h.id '.$facilities_not_visited_cond.')
		WHERE f.id NOT IN(select c.facilityid from checklogin c 
		WHERE c.thedate >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY
		AND c.thedate < curdate() - INTERVAL DAYOFWEEK(curdate())-1 DAY)
		order by f.name';			
		$facilities_not_visited = \DB::select($query);

		if(Auth::user()->hasRole('eoc_admin')){
			//exit('ad');
			return redirect('staff/list/5');
		}
		if(Auth::user()->hasRole('hub_coordinator')){
            $where_condition .= " AND e.hubid ='".Auth::user()->hubid."'";
        }

        if(Auth::user()->hasRole('implementing_partner')){
        	$ips_facilities = getFacilitiesForIP(Auth::user()->organisation_id);
        	if(count($ips_facilities)){
        		$where_condition .= " AND e.hubid in (".$ips_facilities.")";
        	}
        	return redirect('/IP/dashboard');		
    	}
		$where_condition .= " AND e.status = 2";		

		$query = "SELECT e.id, lv.lookupvaluedescription as name, e.model, e.serial_number, e.status, e.location, f.hubname, e.installation_date FROM facilitylabequipment e
        INNER JOIN facility f ON(e.hubid = f.id)
		INNER JOIN lookuptypevalue lv ON (lv.lookuptypevalue = e.labequipment_id AND lv.lookuptypeid = 27)
        WHERE e.id != '' ".$where_condition."
        ORDER BY lv.lookupvaluedescription ";
		//echo $query; exit;
        $lab_equipment_broken_down = \DB::select($query);
		
		$where_clause = '';
		$facilities_not_visited_cond = '';
		if(Auth::user()->hasRole('hub_coordinator')){
			$where_clause .= "AND s.hubid = '".Auth::user()->hubid."'";
			$facilities_not_visited_cond = "AND f.parentid = '".Auth::user()->hubid."'";
			$equipment_broken_down = Equipment::where('hubid',Auth::user()->hubid)->where('status',2)->orderby('id', 'desc')->paginate(10);
			$equipment_no_service = Equipment::where('hubid',Auth::user()->hubid)->where('hasservicecontract',0)->orderby('id', 'desc')->paginate(10);					
			//return redirect()->route('dashboard.coordinator');
		}else{
			$equipment_broken_down = Equipment::orderby('id', 'desc')->where('status',2)->paginate(10); 
			$equipment_no_service = Equipment::orderby('id', 'desc')->where('hasservicecontract',0)->paginate(10); 
		}
			
			//facilities not visited last week
			$query = 'select f.id, f.name, h.hubname from 
			facility f
			INNER JOIN facility h ON (f.parentid = h.id '.$facilities_not_visited_cond.')
			WHERE f.id NOT IN(select c.facilityid from checklogin c 
			WHERE c.thedate >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY
			AND c.thedate < curdate() - INTERVAL DAYOFWEEK(curdate())-1 DAY)
			order by f.name';			
			$facilities_not_visited = \DB::select($query);
			
			// get the samples for this week
			$query = "SELECT lv.lookupvaluedescription as sampletype, SUM(s.numberofsamples) AS samples
			FROM samples s 
			INNER JOIN lookuptypevalue lv ON(lv.lookuptypevalue = s.test_type)
			INNER JOIN lookuptype l ON(l.id = lv.lookuptypeid)
			WHERE lv.lookuptypeid = 18 AND YEARWEEK(`date_picked`) = YEARWEEK(CURDATE()) ".$where_clause."
			GROUP BY lv.lookupvaluedescription";
			
		   $samples = \DB::select($query);
		 	
			$samplestable = lava::DataTable();
			$samplestable->addStringColumn('Sample Type')
				->addNumberColumn('Number of samples');
			foreach($samples as $line){
				$samplestable->addRow([
				  $line->sampletype, $line->samples
				]);
			}
			//$chart = lava::LineChart('samples', $stocksTable);			
			lava::ColumnChart('samples', $samplestable, [
				'title' => ' Samples this week',
				'titleTextStyle' => [
					'color'    => '#eb6b2c',
					'fontSize' => 14
				]
			]);
			
			// Random Data For Example
			$query = "SELECT count(s.id) AS results
			FROM results s 
			WHERE delivered_at >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY ".$where_clause."
			GROUP BY s.id ";

		   	$results = \DB::select($query);		
			
			$resultstable = lava::DataTable();

			$resultstable->addNumberColumn('Number of results');
			if (count($results)){
			 	foreach($results as $line){
					$resultstable->addRow([
					  $line->results
					]);													
				}															
			
			lava::ColumnChart('theresults', $resultstable, [
				'title' => 'Results this week',
				'titleTextStyle' => [
					'color'    => '#eb6b2c',
					'fontSize' => 14
				]
			]);
		}
		
		//print_r(count($equipment_brokendown));
		//exit;
		return view('dashboard.index', compact('equipment_broken_down', 'equipment_no_service','samples','results', 'facilities_not_visited', 'lab_equipment_broken_down'));
    }

    public function monitorIpSamples(Request $request)
    {
    	$filters = $request->all();
    	$period = getWeekEndDates();
    	$from = $period['start'];
		$to = $period['end'];
		$where_clause = '';
		$test_types = array_merge_maintain_keys(array('' => 'Sample types'), getTestTypes()); 
		$facilities = array_merge_maintain_keys(array(''=>'Facility'),getAllFacilities());
		
		$testtypes = getTestTypes();
		$sum_string = $this->getSumString($testtypes);

		  if(Auth::user()->hasRole('implementing_partner')){
        	$ips_facilities = getFacilitiesForIP(Auth::user()->organisation_id);
        	if(count($ips_facilities)){
        		$where_condition .= " AND e.hubid in (".$ips_facilities.")";
        	}
			$userID = Auth::user()->organisation_id;
			$hubs = array_merge_maintain_keys(array('' => 'Select a hub'), getAllHubsForIP($userID));

			$where = 'WHERE YEARWEEK(`date_picked`) = YEARWEEK(CURDATE())';

			if(!empty($filters)){
				$graph_title = 'Samples for selected options';
				if($filters['from'] != '' && $filters['to'] != ''){
					$where = "WHERE p.created_at BETWEEN '".getMysqlDateFormat($filters['from'])."'  AND '".getMysqlDateFormat($filters['to'])."'";
				}
				
				if(array_key_exists('facilityid',$filters) && $filters['facilityid']){
					$where_clause .= ' AND p.facilityid = '.$filters['facilityid'];
				}
				if(array_key_exists('hubid',$filters) && $filters['hubid']){
					$where_clause .= ' AND h.id = '.$filters['hubid'];
				}
				if(array_key_exists('districtid',$filters) && $filters['districtid']){
					$where_clause .= ' AND d.id = '.$filters['districtid'];
				}
			}


			$query = "SELECT h.hubname as hub, d.name as district, f.name as facility, f.hubname as althubname,
			".$sum_string.", 
			SUM(p.numberofsamples) as total
			FROM package p  
			INNER JOIN testtypes tt ON(tt.id = p.test_type)
			LEFT JOIN  facility f ON (f.id = p.facilityid)
			LEFT JOIN  facility AS h ON (f.parentid = h.id)
			LEFT JOIN  district AS d ON (f.districtid = d.id)
			 ".$where.$where_clause."
			GROUP BY p.facilityid, h.hubname, d.name, f.name,f.hubname ASC";
			
			$samples = \DB::select($query);

        	return view('ip.packagetracker', compact('from','to','facilities', 'request','hubs','testtypes','test_types', 'samples'));
		
    	}

    }
	
	public function coordinator(){
      	$stocksTable = lava::DataTable();  
		/*$stocksTable->addDateColumn('Day of Month')
            ->addNumberColumn('Projected')
            ->addNumberColumn('Official');
*/		$stocksTable->addStringColumn('Sample Type')
            ->addNumberColumn('Number of Samples');
		// Random Data For Example
		$query = "SELECT lv.lookupvaluedescription as sampletype, SUM(s.numberofsamples) AS samples
		FROM samples s 
		INNER JOIN lookuptypevalue lv ON(lv.lookuptypevalue = s.test_type)
		INNER JOIN lookuptype l ON(l.id = lv.lookuptypeid)
		WHERE lv.lookuptypeid = 18 AND s.hubid = '".Auth::user()->hubid."'AND MONTH(thedate) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
		GROUP BY lv.lookupvaluedescription ASC";
	   $samples = \DB::select($query);
	   //print_r($samples);
	  // exit;
		/*for ($a = 1; $a <= 30; $a++) {
			$stocksTable->addRow([
			  '2015-10-' . $a, rand(800,1000), rand(800,1000)
			]);
		}*/
		foreach($samples as $line){
			$stocksTable->addRow([
			  $line->sampletype, $line->samples
			]);
		}
		$chart = lava::LineChart('MyStocks', $stocksTable);
		
		$resultstable = lava::DataTable();  
			$resultstable->addStringColumn('Result Type')
            ->addNumberColumn('Number of results');
		// Random Data For Example
				$query = "SELECT lv.lookupvaluedescription as resulttype, SUM(s.numberofresults) AS results
		FROM results s 
		INNER JOIN lookuptypevalue lv ON(lv.lookuptypevalue = s.test_type)
		INNER JOIN lookuptype l ON(l.id = lv.lookuptypeid)
		WHERE lv.lookuptypeid = 18 AND s.hubid = '".Auth::user()->hubid."'AND MONTH(thedate) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
		GROUP BY lv.lookupvaluedescription ASC";
		   $results = \DB::select($query);
			foreach($results as $line){
				$resultstable->addRow([
				  $line->resulttype, $line->results
				]);
			}
		//print_r($resultstable);
		//exit;
		$resultchart = lava::LineChart('theresults', $resultstable);	
		return View('dashboard.coordinator', compact('samples','results'));
	}

	// Show facilities belonging to an IP
	public function showIpFacilites()
	{

		$can_delete_facility = Entrust::can('delete-facility');
		$can_update_facility = Entrust::can('Update_facility');
		$userID = Auth::user()->organisation_id;

		$query = "SELECT f.id, f.name, i.name as 'ip', f.hubname, f.address, hr.name as healthregion, d.name as `district` FROM facility f
		INNER JOIN healthregion hr ON(f.healthregionid = hr.id)
		LEFT JOIN organization i ON(i.id = f.ipid)
	    INNER JOIN district d ON (f.districtid = d.id)
		WHERE f.id = f.parentid
		AND i.id = $userID
		ORDER BY f.name ASC";
		$hubs = \DB::select($query);
        return view('hub.Ip_hubs.list', compact('hubs', 'can_delete_facility','can_update_facility'));
	}

	public function showSamplesByHubIP(Request $request)
	{
		$period = getWeekEndDates();
		$from = $period['start'];
		$to = $period['end'];
		$userID = Auth::user()->organisation_id;
		// get the samples for this month
		// $hubs = array_merge_maintain_keys(array('' => 'Select a hub'), getAllHubs());
		$hubs = array_merge_maintain_keys(array('' => 'Select a hub'), getAllHubsForIP($userID));

		$facilities = array('' => 'Filter by facility');
		$where_clause = '';
		$incharge_clause = '';
			if(Auth::user()->hasRole('hub_coordinator')){
				$incharge_clause .= " AND h.hubid = '".Auth::user()->hubid."'";
				$facilities = array_merge_maintain_keys(array(''=>'Select a facility'),getFacilitiesforHub(Auth::user()->hubid));
			}

			$graph_title = 'Samples last month';			
			
	        if(Auth::user()->hasRole('implementing_partner')){
	        	$ips_facilities = getFacilitiesForIP(Auth::user()->organisation_id);
	        	if(count($ips_facilities)){
	        		$where_clause .= " AND p.hubid in (".$ips_facilities.")";
	        	}
	    	}
			//$where = " AND p.created_at BETWEEN '".getMysqlDateFormat($from)."'  AND '".getMysqlDateFormat($to)."'";
			$filters = $request->all();
			if(!empty($filters)){
				$graph_title = 'Samples for selected options';
				if($filters['from'] != '' && $filters['to'] != ''){
					$from = $filters['from'];
					$to = $filters['to'];
				}
				
				if(array_key_exists('facilityid',$filters) && $filters['facilityid']){
					$where_clause .= ' AND p.facilityid = '.$filters['facilityid'];
				}
				if(array_key_exists('hubid',$filters) && $filters['hubid']){
					$where_clause .= ' AND h.id = '.$filters['hubid'];
				}
			}
			$where = " WHERE p.created_at BETWEEN '".getMysqlDateFormat($from)."'  AND '".getMysqlDateFormat($to)."'";
			$query = "SELECT h.name as hub, SUM(numberofsamples) as total FROM package p 
			INNER JOIN facility h ON h.id = p.hubid
			LEFT JOIN organization i ON(i.id = h.ipid)			
	 		".$where.$where_clause.$incharge_clause." AND h.id = h.parentid AND i.id = ".$userID."
			GROUP BY p.hubid, h.name ASC";
			//dd($query);
			$samples = \DB::select($query);
	 		//dd($samples);
		return view('ip.iphubsamples', compact('samples', 'hubs', 'facilities', 'request','from','to'));
	}

	public function statForIpsampleInTransit()
	{
		$querry = "SELECT A.id AS id, B.name AS Facility,i.name as IP, A.name
					FROM facility A, facility B
					LEFT JOIN organization i ON(i.id = B.ipid)
					WHERE A.id = B.parentid 
					and A.ipid = 7"	;	


		$y =  "SELECT B.name AS Facility,i.name as IP, A.name as HUB
				FROM facility A, facility B
				left JOIN organization i ON(i.id = B.ipid)
				WHERE A.id = B.parentid 
				and A.ipid = 7";	
	}

	private function getSumString($testtypes){
    	//dd($testtypes);
    	$ret_str = '';
    	$counter = 1;
    	foreach ($testtypes as $test_type_id => $test_type_name) {
    		//
    		$ret_str .= "SUM(CASE WHEN p.test_type = ".$test_type_id." THEN p.numberofsamples END) AS `".generateSlug($test_type_name,'_')."`";
    		if($counter != count($testtypes)){
    			$ret_str .= ', ';
    		}
    		$counter++;
    		# code...
    	}
    	return $ret_str;
    }

       
}