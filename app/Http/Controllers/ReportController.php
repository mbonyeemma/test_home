<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \Lava;
use Auth;
use Session;
use Illuminate\Support\Facades\Redirect;

use \App\Models\LookupType as LookupType;
use \App\Models\DailyRouting as DailyRouting;
use \App\Models\Facility as Facility;
use \App\Models\Hub as Hub;
use \App\Models\DailyRoutingReason as DailyRoutingReason;
use \App\Models\Sample as Sample;
use \App\Models\Result as Result;
use \App\Models\TestType as TestType;
use \App\Models\Package as Package;
use DB;
use Excel;

class ReportController extends Controller {

    public function __construct() {
        //$this->middleware(['auth', 'clearance'])->except('index', 'show');
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {
        $staff = RoutingSchedule::Orderby('id', 'desc')->paginate(10);
		return view('routingschedule.list');
    }
	public function hubSamples(Request $request){
			$period = getWeekEndDates();
			$from = $period['start'];
			$to = $period['end'];
			// get the samples for this month
			$hubs = array_merge_maintain_keys(array('' => 'Select a hub'), getAllHubs());
			
			$facilities = array('' => 'Filter by facility');
			$districts = array_merge_maintain_keys(array('' => 'Select a district'), getAllDistricts());
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
				/*if(array_key_exists('districtid',$filters) && $filters['districtid']){
					$where_clause .= ' AND d.id = '.$filters['districtid'];
				}*/
			}
			$where = " WHERE p.created_at BETWEEN '".getMysqlDateFormat($from)."'  AND '".getMysqlDateFormat($to)."'";
			$query = "SELECT h.name as hub, SUM(numberofsamples) as total FROM package p 
			INNER JOIN facility h ON h.id = p.hubid
			
	 ".$where.$where_clause.$incharge_clause." AND h.id = h.parentid 
	GROUP BY p.hubid, h.name ASC";
	//dd($query);
	$samples = \DB::select($query);
	 	//dd($query);
		return view('reports.hubsamples', compact('samples', 'hubs', 'facilities','districts', 'request','from','to'));
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

    public function showTotalSamples(Request $request)
    {
	    $from = $request->from;
	    $to = $request->to;
	    $hubs = array_merge_maintain_keys(array('' => 'Select a hub'), getAllHubs());
    	return view('reports.totalsamples',compact('from','to','hubs'));

    }

    public function downloadFacilityData(Request $request)
    {
    	$from = $request->from;
	    $to = $request->to;
	    $hubs = $request->hubid;

	    set_time_limit(0);

	    $specimenTypes = TestType::OrderBy('name', 'desc')->get();

	    $hubsquerry = "SELECT p.hubid,fc.name as hub,ds.name as district from package p
						left join facility fc ON fc.id = p.hubid
						left join district ds on ds.id = fc.districtid
						left join region r on r.id = ds.regionid
						left join facility f ON f.id = p.hubid
						where p.created_at BETWEEN '".$from."' AND '".$to."'
						and fc.id = fc.parentid
						and fc.parentid < 10000
						group by fc.name,ds.name,p.hubid
						order by fc.name asc";
		$hubs = DB::select($hubsquerry);

		$content = [];

		$i = 1;
		foreach ($hubs as $hub) {
			$content[$i]['HUB'] = $hub->hub;
			$content[$i]['DISTRICT'] = $hub->district;

			foreach ($specimenTypes as $specimen) {
				$content[$i][$specimen->name] = getTotalSpecimen($hub->hubid,$specimen->id, $from, $to);
			}
			$i++;

		}

		if (!empty($content)) {
	      	Excel::create($from.'to'. $to, function($excel) use  ($content) {

		        $excel->sheet('Total Tracked Samples', function($sheet) use  ($content) {
		          $sheet->fromArray($content);
		        });

		    })->download('xls');
	    }else{
	      Session::flash('message', 'No data found!');
	      return Redirect::to('/home');
	    }
    }
    
       public function showHubVisits(Request $request)
    {
	    $from = $request->from;
	    $to = $request->to;
	    $hubs = array_merge_maintain_keys(array('' => 'Select a hub'), getAllHubs());

	    $query = "SELECT cl.thedate,f.name, f.parentid as hubid, fc.name as hub, ds.name as District, r.name as Region from checklogin cl 
					INNER JOIN (select usr.id as userid,usr.name as username,stf.designation from users usr 
					                INNER JOIN staff stf ON usr.id = stf.user_id
					                where stf.designation = 1) as us ON us.userid = cl.staffid
					INNER JOIN facility f ON f.id = cl.facilityid
					INNER JOIN facility fc ON fc.id = f.parentid
					INNER JOIN district ds on ds.id = fc.districtid
					INNER JOIN region r on r.id = ds.regionid
					where cl.thedate = CURRENT_DATE() - 1
					order by hubid";
		$data = DB::select($query);

    	return view('reports.hubvisits',compact('from','to','hubs','data'));

    }

    public function downloadHubVisitData(Request $request)
    {
    	$from = $request->from;
	    $to = $request->to;
	    $hubs = array_merge_maintain_keys(array('' => 'Select a hub'), getAllHubs());
	    $selected_hub = $request->hubid;

	    $hubid = '';
	    
	    if($selected_hub != '')
        {
            $hubid = " AND f.parentid = $selected_hub ";
        }

	    $query = "SELECT cl.thedate,f.name, f.parentid as hubid, fc.name as hub, ds.name as District, r.name as Region from checklogin cl 
					INNER JOIN (select usr.id as userid,usr.name as username,stf.designation from users usr 
					                INNER JOIN staff stf ON usr.id = stf.user_id
					                where stf.designation = 1) as us ON us.userid = cl.staffid
					INNER JOIN facility f ON f.id = cl.facilityid
					INNER JOIN facility fc ON fc.id = f.parentid
					INNER JOIN district ds on ds.id = fc.districtid
					INNER JOIN region r on r.id = ds.regionid
					where cl.thedate  BETWEEN '".$from."' AND '".$to."'
					".$hubid."
					order by hubid";
		$data = DB::select($query);

		$content = [];

		$i = 1;
		foreach ($data as $da) {
			$content[$i]['VISIT DATE'] = $da->thedate;
			$content[$i]['HUB'] = $da->hub;
			$content[$i]['FACILITY NAME'] = $da->name;
			$content[$i]['DISTRICT'] = $da->District;
			$content[$i]['REGION'] = $da->Region;
			$i++;
		}
		if (!empty($content)) {
      	Excel::create('Hub Visits'. $to, function($excel) use  ($content)
      	{
	        $excel->sheet('Hub Visits', function($sheet) use  ($content) {
	          $sheet->fromArray($content);
	        });
	    })->export('xls');

	    }else{
	      Session::flash('message', 'No data found!');
	      return Redirect::to('/home');
	    }

    }

}
