<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SampleTracking;
use Auth;
use Session;
use \Lava;
use \App\Models\LookupType as LookupType;
use \App\Models\DailyRouting as DailyRouting;
use \App\Models\Facility as Facility;
use \App\Models\Hub as Hub;
use \App\Models\DailyRoutingReason as DailyRoutingReason;
use \App\Models\Sample as Sample;
use \App\Models\Result as Result;
use \App\Models\PackageMovementEvent as PackageMovementEvent;
use Excel;

class SampleTrackingController extends Controller
{

	public function __construct()
	{
		//$this->middleware(['auth', 'clearance'])->except('index', 'show');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function index()
	{
		$hubid = Auth::getUser()->hubid;
		$query = "SELECT s.id, f.name as facility , s.patient, s.specimennumber, lv.lookupvaluedescription as specimentype, CONCAT(st.firstname,'',st.lastname) as transporter, s.sampletransported_at as time, lvs.lookupvaluedescription as status
		FROM sampletracking as s 
		INNER JOIN facility as f ON (s.facilityid = f.id) 
		INNER JOIN lookuptypevalue lv ON(lv.lookuptypevalue = s.specimentype AND lv.lookuptypeid = 17)
		INNER JOIN lookuptype l ON(l.id = lv.lookuptypeid)
		INNER JOIN lookuptypevalue lvs ON(lvs.lookuptypevalue = s.status AND lvs.lookuptypeid = 18)
		INNER JOIN lookuptype ls ON(ls.id = lvs.lookuptypeid)
		INNER JOIN staff st ON(s.sampletransportedby = st.id)
		WHERE s.hubid = '" . $hubid . "'
		ORDER BY s.status ASC";
		$results = \DB::select($query);
		return view('sampletracking.list', compact('results'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$hubid = Auth::getUser()->hubid;
		$sampletransporters = array_merge_maintain_keys(array('' => 'Select One'), getSampleTransportersforHub($hubid));
		$facilities = array_merge_maintain_keys(array('' => 'Select One'), getFacilitiesforHub($hubid));
		$lt = new LookupType();
		$lt->name = 'SPECIMEN_TYPES';
		$specimentypes  = array_merge_maintain_keys(array('' => 'Select One'), $lt->getOptionValuesAndDescription());

		return View('sampletracking.create', compact('facilities', 'hubid', 'sampletransporters', 'specimentypes'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$sampletracking = new SampleTracking();
		$sampletracking->facilityid = $request->facilityid;
		$sampletracking->hubid = $request->hubid;
		$sampletracking->status = 1;
		$sampletracking->sampletransportedby = $request->sampletransportedby;
		$sampletracking->patient = $request->patient;
		$sampletracking->specimennumber = $request->specimennumber;
		$sampletracking->specimentype = $request->specimentype;
		$sampletracking->sampletransported_at = $request->sampletransported_at;
		$sampletracking->createdby = Auth::getUser()->id;
		$sampletracking->save();
		return redirect()->route('sampletracking.show', array('id' => $sampletracking->id));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$sampletracking = SampleTracking::findOrFail($id);
		return view('sampletracking.view', compact('sampletracking'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {}

	public function all(Request $request)
	{
		$hubs = array_merge_maintain_keys(array('' => 'Hub'), getAllHubs());
		$facilities = array_merge_maintain_keys(array('' => 'Facility'), getAllFacilities());
		$poes = array_merge_maintain_keys(array('' => 'POE'), getAllPoes());
		$districts = array_merge_maintain_keys(array('' => 'Districts'), getAllDistricts());
		$period = getWeekEndDates();
		$ref_labs = array_merge_maintain_keys(array('' => 'Destinations'), getReferenceLabs());
		$site_types = array_merge_maintain_keys(array('' => 'Site types'), getSiteOfllectionTypes());
		$test_types = array_merge_maintain_keys(array('' => 'Sample types'), getTestTypes());
		$where_clause = '';
		$graph_where_clause = '';
		$where_clause_date = '';
		$where_clause_date_samples_query = '';
		$filters = $request->all();
		$ref_lab = '';
		$site_type = '';
		$test_type = '';
		$sitetype_type_cond = '';
		$sitetype_facility_cond = '';
		$status_dropdown = array_merge_maintain_keys(array('' => 'Fileter by status'), getHubPackageStatus());
		$status_dropdown_for_hubs = getHubPackageStatusLabel();


		$filters = $request->all();
		$from = $period['start'];
		$to = $period['end'];
		$post_type = $request->method();
		$page_type = $request->page_type;
		if ($page_type == 2) {
			// bached samples
			$where_clause .= ' AND p.is_batch = 1';
			$tab = '2';
			$tab_1_class = '';
			$tab_2_class = 'active';
		} else {
			//singel packages
			$where_clause .= ' AND p.is_batch = 0';
			$tab = '1';
			$tab_1_class = 'active';
			$tab_2_class = '';
		}

		//$where = " WHERE p.created_at between (CURDATE() - INTERVAL 1 MONTH ) and CURDATE()";;
		$cut_off_number_of_days = env('NUMBER_OF_DAYS_CUT_OFF_FOR_PACKAGES');
		$where_package = $where  = "  AND p.created_at BETWEEN CURDATE() - INTERVAL " . env('NUMBER_OF_DAYS_CUT_OFF_FOR_PACKAGES') . " DAY AND CURDATE()";
		$graph_where_clause = " AND date_picked BETWEEN CURDATE() - INTERVAL " . env('NUMBER_OF_DAYS_CUT_OFF_FOR_PACKAGES') . " DAY AND CURDATE()";
		$test_type_cond = '';
		//$graph_where_clause = ' WHERE date_picked > DATE_SUB(NOW(), INTERVAL 1 WEEK';

		if ($post_type == 'POST') {
			$from = $filters['from'];
			$to = $filters['to'];

			if ($from != '' && $to != '') {
				$where = " AND p.created_at BETWEEN '" . getMysqlDateFormat($from) . "'  AND '" . getMysqlDateFormat($to) . "'";
				$graph_where_clause = " AND date_picked BETWEEN '" . getMysqlDateFormat($from) . "'  AND '" . getMysqlDateFormat($to) . "'";
				$where_package = "WHERE p.created_at BETWEEN '" . getMysqlDateFormat($from) . "'  AND '" . getMysqlDateFormat($to) . "'";
			}

			if (array_key_exists('facilityid', $filters) && $filters['facilityid']) {
				$where_package .= ' AND p.facilityid = ' . $filters['facilityid'];
			}
			if (array_key_exists('poe', $filters) && $filters['poe']) {
				$where_package .= ' AND p.facilityid = ' . $filters['poe'];
			}
			if (array_key_exists('ref_lab', $filters) && $filters['ref_lab']) {
				$where_package .= ' AND p.final_destination = ' . $filters['ref_lab'];
			}
			if (array_key_exists('hubid', $filters) && $filters['hubid']) {
				$where_clause .= ' AND p.hubid = ' . $filters['hubid'];
			}
			if (array_key_exists('test_type', $filters) && $filters['test_type']) {
				$where_clause .= ' AND p.test_type = ' . $filters['test_type'];
			}

			if (array_key_exists('site_type', $filters) && $filters['site_type']) {
				$sample_t = $filters['site_type'];
				//1=>Community,2=>Isolation,3=>POEs, 4=>Facility
				if ($sample_t == 1) {
					$sitetype_type_cond .= ' AND p.facilityid = 900000';
				}
				if ($sample_t == 4) {
					$sitetype_facility_cond .= ' AND sf.parentid IS NULL AND sf.facilitylevelid <> 14';
				}
			}
		}

		if (Auth::user()->hasRole('hub_coordinator')) {
			$where_clause .= " AND sf.parentid = '" . Auth::user()->hubid . "'";
			$facilities = array_merge_maintain_keys(array('' => 'Select a facility'), getFacilitiesforHub(Auth::user()->hubid));
		}

		$query = "SELECT p.id, p.barcode as sample_id, p.numberofsamples, count(p.barcode) as total, p.created_at as date_picked, p.barcode as container, le.created_at,
le.created_at as last_seen, le.created_at as last_seen, pmef.name as last_seen_facility,
sf.name as sourcefacility, p.numberofsamples, tt.name as testtype,p.facilityid, p.final_destination, le.source, le.destination, le.longitude, 
le.latitude, le.status, fd.name as final_destn, p.longitude, p.latitude, p.place_name,p.delivered_on,p.received_at_destination_on,
IF(sf.facilitylevelid = 14,'Poe',IF(sf.id = 899991,'Quarantine',IF(sf.id = 899992,'Isolation',IF(sf.id = 900000,'Community surveillance','Health facility')))) as collection_point,
IF(sf.id = 899991 OR sf.id = 899992 OR sf.id = 900000, p.place_name, sf.name) as collection_point_name,
IF(sf.id = 899991 OR sf.id = 899992 OR sf.id = 900000, '', d.name) as `district`,
IF(sf.id = 899991 OR sf.id = 899992 OR sf.id = 900000, '', h.name) as `hub` FROM package p
LEFT JOIN packagemovement_events le on(le.id = p.latest_event_id)
INNER JOIN facility sf ON(sf.id = p.facilityid)
LEFT JOIN facility h ON(h.id = sf.parentid)
INNER JOIN district d ON(d.id = sf.districtid)
INNER JOIN facility fd ON(fd.id = p.final_destination)
LEFT JOIN facility pmef ON(pmef.id = le.location)
INNER JOIN testtypes tt ON(tt.id = p.test_type)
" . $where_package . $where_clause . " 
GROUP BY p.id, le.created_at,
le.created_at,p.parent_id, le.created_at, pmef.name,
sf.name, p.numberofsamples , le.source, le.destination, le.longitude,
le.latitude, le.`status`, fd.name, h.name, d.name, p.place_name, p.facilityid, p.final_destination";

		//dd($query);
		$package_samples = \DB::select($query);

		return view('sampletracking.all', compact('hubs', 'facilities', 'districts', 'poes', 'request', 'package_samples', 'status_dropdown', 'status_dropdown_for_hubs', 'from', 'to', 'ref_labs', 'site_types', 'test_types', 'ref_lab', 'site_type', 'test_type', 'tab', 'tab_1_class', 'tab_2_class'));
	}

	public function receiveRample($id)
	{
		$packages = \DB::select('SELECT p.barcode, p.id, p.status FROM packagedetail pd  
				INNER JOIN package p ON (pd.small_barcodeid = p.id)
				WHERE pd.big_barcodeid =' . $id . ' GROUP BY p.id');
		if (count($packages)) {
			return view('sampletracking.receive', compact('packages', 'id'));
		} else {
			//save the untracked barcode and redirect to receive individual packages
			return redirect()->route('samples.all');
		}
	}

	/*	public function cphl(Request $request){
		// get the samples for this month
			$hubs = array_merge_maintain_keys(array('' => 'Filter by hub'), getAllHubs());
			$facilities = array('' => 'Filter by facility');
			$districts = array_merge_maintain_keys(array('' => 'Filter by district'), getAllDistricts());
			$status_dropdown = array_merge_maintain_keys(array('' => 'Fileter by status'), getCphlPackageStatus());

			$all_facilities  = array_merge_maintain_keys(array('' => 'Select a facility'), getAllFacilities());
			$test_types = array_merge_maintain_keys(array('' => 'Sample types'), getTestTypes()); 
			
			$incharge_clause = '';
			if(Auth::user()->hasRole('hub_coordinator')){
				$incharge_clause .= " AND pm.destination = '".Auth::user()->hubid."'";
				$facilities = array_merge_maintain_keys(array(''=>'Select a facility'),getFacilitiesforHub(Auth::user()->hubid));
			}
			$where_clause = '';
			$where = 'WHERE p.created_at > DATE_SUB(NOW(), INTERVAL 1 WEEK)';
			$filters = $request->all();
			if(!empty($filters)){
				if($filters['from'] != '' && $filters['to'] != ''){
					$where = "WHERE p.created_at BETWEEN '".getMysqlDateFormat($filters['from'])."'  AND '".getMysqlDateFormat($filters['to'])."'";
				}
			
				if(array_key_exists('hubid',$filters) && $filters['hubid']){
					$where_clause .= ' AND p.hubid = '.$filters['hubid'];
				}
				if(array_key_exists('status',$filters) && $filters['status']){
					$where_clause .= ' AND p.status = '.$filters['status'];
				}
			}
			$query = "SELECT p.id, p.barcode, h.name as hubname, p.status as packagestatus, df.hubname as sourcefacility, sp.numberofenvelopes, p.created_at as thedate, pm.`status`, pm.recieved_at, pm.delivered_at from package p
INNER JOIN (SELECT COUNT(pd.id) AS numberofenvelopes, pd.big_barcodeid  FROM packagedetail pd
GROUP BY pd.big_barcodeid) AS sp ON (p.id = sp.big_barcodeid)
INNER JOIN facility h ON(p.hubid = h.id)
LEFT JOIN packagemovement pm ON (pm.packageid = p.id)
LEFT JOIN facility df ON(pm.source = df.id)
".$where.$incharge_clause.$where_clause." AND p.type = 2
group by p.id, p.barcode,df.hubname,pm.status,sp.numberofenvelopes,p.created_at,pm.recieved_at, pm.delivered_at,h.name, p.status";
//echo $query;
//exit;
		
			$samples = \DB::select($query);	
						
		return view('sampletracking.cphl', compact('samples', 'hubs', 'facilities','districts','status_dropdown','all_facilities','test_types'));
		
		
	}*/

	public function packageStatistics()
	{
		$destinedforcphl = packageStats(5, 2); //the argumments are (status number, package type)
		$receivedatcphl = packageStats(7, 2);
		$hubpackages = packageStats(1, 1);
		//exit;
		return response()->json(['destinedforcphl' => $destinedforcphl, 'receivedatcphl' => $receivedatcphl, 'hubpackages' => $hubpackages]);
	}
	public function lateDelivery()
	{
		$query  = 'SELECT sum(numberofsamples) as no FROM package wHERE status = 0 OR status = 1 AND created_at <= CURDATE() - INTERVAL 3 DAY AND parent_id > 0';
		$package_samples = \DB::select($query);
		echo $package_samples[0]->no;
	}
	public function covid_stats($status)
	{

		if ($status == 0 || $status == 1) {
			$query  = 'SELECT sum(numberofsamples) as no FROM package wHERE status = 0 OR status = 1 AND created_at between (CURDATE() - INTERVAL 1 MONTH ) and CURDATE() AND parent_id > 0';
		} elseif ($status == 2) {
			$query = 'SELECT sum(numberofsamples) as no FROM package wHERE delivered_by <> 0 AND  created_at between (CURDATE() - INTERVAL 1 MONTH ) and CURDATE() AND parent_id > 0';
		} elseif ($status == 3) {
			$query = 'SELECT sum(numberofsamples) as no FROM package p
			INNER JOIN packagemovement_events pme ON(p.id = pme.package_id) wHERE p.created_at between (CURDATE() - INTERVAL 1 MONTH ) and CURDATE() AND parent_id > 0 AND p.final_destination = pme.location and pme.status = 3';
		} else {
			//do nothing
		}
		$package_samples = \DB::select($query);
		echo $package_samples[0]->no;
	}
	public function receive(Request $request)
	{
		// get the samples for this month
		$hubs = array_merge_maintain_keys(array('' => 'Filter by hub'), getAllHubs());
		$facilities = array('' => 'Filter by facility');
		$districts = array_merge_maintain_keys(array('' => 'Filter by district'), getAllDistricts());
		$status_dropdown = array_merge_maintain_keys(array('' => 'Fileter by status'), getCphlPackageStatus());

		$incharge_clause = '';
		if (Auth::user()->hasRole('hub_coordinator')) {
			$incharge_clause .= " AND pm.destination = '" . Auth::user()->hubid . "'";
			$facilities = array_merge_maintain_keys(array('' => 'Select a facility'), getFacilitiesforHub(Auth::user()->hubid));
		}
		$where_clause = '';
		$where = '';
		$filters = $request->all();
		if (!empty($filters)) {
			if ($filters['from'] != '' && $filters['to'] != '') {
				$where = "WHERE p.created_at BETWEEN '" . getMysqlDateFormat($filters['from']) . "'  AND '" . getMysqlDateFormat($filters['to']) . "'";
			}

			if (array_key_exists('hubid', $filters) && $filters['hubid']) {
				$where_clause .= ' AND p.hubid = ' . $filters['hubid'];
			}
			if (array_key_exists('status', $filters) && $filters['status']) {
				$where_clause .= ' AND p.status = ' . $filters['status'];
			}
		}
		$query = "SELECT p.barcode, p.id as packageid, pm.id as packagemovementid, h.name as hubname, p.status as packagestatus, df.hubname as sourcefacility, sp.numberofenvelopes, p.created_at as thedate, pm.`status`, pm.recieved_at, pm.delivered_at from package p
INNER JOIN (SELECT COUNT(pd.id) AS numberofenvelopes, pd.big_barcodeid  FROM packagedetail pd
GROUP BY pd.big_barcodeid) AS sp ON (p.id = sp.big_barcodeid)
INNER JOIN facility h ON(p.hubid = h.id)
INNER JOIN packagemovement pm ON (pm.packageid = p.id AND pm.`status` = 2)
LEFT JOIN facility df ON(pm.source = df.id)
" . $where . $incharge_clause . $where_clause . " AND p.type = 2  
group by p.barcode,df.hubname,pm.status,sp.numberofenvelopes,p.created_at,pm.recieved_at, pm.delivered_at,h.name, p.status, p.id, pm.id";

		$samples = \DB::select($query);

		return view('sampletracking.receive', compact('samples', 'hubs', 'facilities', 'districts', 'status_dropdown'));
	}

	public function results()
	{
		$where = '';
		if (Auth::user()->hasRole('hub_coordinator')) {
			$where = "WHERE r.hubid = " . Auth::user()->hubid . "";
		}
		$query = "SELECT h.hubname, f.name as facility, r.locator_id, r.delivered_at, CONCAT (s.firstname, ' ', s.lastname) as delivered_by FROM results r
		INNER JOIN facility h ON(r.hubid = h.id)
		INNER JOIN facility f ON(r.facilityid = f.id)
		INNER JOIN staff s ON(r.created_by = s.id)
		" . $where;
		$results = \DB::select($query);
		return View('sampletracking.results', compact('results'));
	}

	public function trace_sample($package_id)
	{
		$query = "select fp.id, fp.facilityid, fp.case_id, fp.barcode, of.name as 'facility_of_origin',df.name as destination,le.longitude, le.latitude, le.created_at as `date`, f.name AS location, 
					IF((le.status =3 AND le.source = le.destination AND fp.final_destination = le.destination),'Received',IF((le.status =2 AND le.source = le.destination AND fp.final_destination = le.destination),'Delivered',IF(le.status =0,'Waiting Pickup','In Transit'))) as `status`, le.status as state FROM 
		(SELECT p.id, p.barcode, p.case_id, p.created_at, p.facilityid, p.final_destination, pd.small_barcodeid, pd.big_barcodeid FROM package p 
		inner JOIN packagedetail pd ON(p.id = pd.small_barcodeid)) as fp
		inner JOIN
		(SELECT package_id, created_at, status,longitude, latitude, source, destination,created_by FROM packagemovement_events GROUP BY package_id,created_at, status,longitude, latitude,source, destination, created_by) AS le
		ON(le.package_id = fp.big_barcodeid)
                INNER JOIN facility of ON (of.id = fp.facilityid)
		INNER JOIN facility df ON (df.id = fp.final_destination)
                INNER JOIN facility f ON (f.id = le.source) 
                INNER JOIN staff st ON(le.created_by = st.id) 
                WHERE fp.id = $package_id ORDER BY date asc";


		$vents = \DB::select($query);
		$table_str = '<div class="box-body table-responsive">
          <table id="events_t" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Source Faciliity</th>
                <th>Final Destination</th>                
                <th>Date</th>
                <th>Location</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>';
		foreach ($vents as $event) {
			$locus = $event->location . ' (' . $event->latitude . ', l' . $event->longitude . ')';
			$table_str .= '<tr>
           		<td>' . $event->facility_of_origin . '</td>
           		<td>' . $event->destination . '</td>
           		<td>' . $event->date . '</td>
           		<td>' . $locus . '</td>
           		<td>' . $event->status . '</td>
           	</tr>';
		}
		$table_str .= '</tbody></table></div>';
		echo $table_str;
	}

	public function get_package_details($package_id)
	{
		$query = "select s.barcode, p.status FROM samples s
INNER JOIN package p ON p.id = s.package_id wHERE s.package_id = $package_id";

		$packages = \DB::select($query);
		//\Log::info($packages);	
		$table_str = '<div class="box-body table-responsive">
          <table id="epackages_t" class="table table-striped table-bordered">
            <thead>
               <tr>
                <th>Sample ID</th>  
            </tr>
            </thead>
            <tbody>';
		foreach ($packages as $sample) {
			$table_str .= '<tr>
           		<td>' . $sample->barcode . '</td>
           	</tr>';
		}
		$table_str .= '</tbody></table></div>';
		echo $table_str;
	}
}
