<?php
// app/Http/Controllers/KickStartController.php
namespace Khill\Lavacharts\Examples\Controllers;
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \Lava;


//namespace App\Http\Controllers;

class KickStartController extends Controller {
    /**
     * Generate the starting point of the application     */

    public function index(Request $request) {
    	/*
			SELECT p.id, lv.lookupvaluedescription as sampletype, p.barcode, s.numberofsamples, sf.name as sourcefacility,  p.created_at as thedate, pme.`status`, pme.created_at as last_seen, ff.name as last_seen_facility, pme.source, pme.destination, pme.longitude, pme.latitude
		FROM
		  (SELECT
		     package_id, MAX(created_at) AS created_at
		   FROM
		     packagemovement_events
		   GROUP BY
		     package_id) AS latest_pme
		INNER JOIN
		  packagemovement_events pme
		ON(pme.package_id = latest_pme.package_id AND pme.created_at = latest_pme.created_at)
		left JOIN package p ON(p.id = pme.package_id)

		LEFT JOIN facility sf ON(p.facilityid = sf.id) 
		LEFT JOIN facility df ON(p.final_destination = df.id) 
		LEFT JOIN facility ff ON(pme.source = ff.id) 
		LEFT JOIN samples s ON(s.barcodeid = p.id) 
		
		LEFT JOIN packagedetail pd ON(pd.big_barcodeid = p.id)
		LEFT JOIN lookuptypevalue lv ON(lv.lookuptypevalue = s.samplecategory AND lv.lookuptypeid = 18)
	 WHERE p.id = 16602 or p.id = 16603 or p.id = 16604
    	*/
        $hubs = array_merge_maintain_keys(array('' => 'Hub'), getAllHubs());
		$facilities = array_merge_maintain_keys(array(''=>'Facility/POE'),getAllFacilities());
		$districts = array_merge_maintain_keys(array('' => 'Districts'), getAllDistricts());
		$this_week = getWeekEndDates();	
		$ref_labs = array_merge_maintain_keys(array('' => 'Destinations'), getReferenceLabs()); 	
		$site_types = array_merge_maintain_keys(array('' => 'Site types'), getSiteOfllectionTypes()); 
		$sample_types = array_merge_maintain_keys(array('' => 'Sample types'), getSampleTypes()); 
		$where_clause = '';
		$graph_where_clause = '';
		$where_clause_date = '';
		$where_clause_date_samples_query = '';
		$filters = $request->all();
		$ref_lab = '';
		$site_type= '';
		$sample_type = '';
		$status_dropdown = array_merge_maintain_keys(array('' => 'Fileter by status'), getHubPackageStatus());
		$status_dropdown_for_hubs = getHubPackageStatusLabel();

		
		$filters = $request->all();
		$from = $this_week['start'];
		$to = $this_week['end'];

		$where = " AND YEARWEEK(fp.created_at,1) = YEARWEEK(CURDATE(),1)";
		$where_package = " AND YEARWEEK(p.created_at,1) = YEARWEEK(CURDATE(),1)";
		$graph_where_clause = " AND YEARWEEK(`thedate`,1) = YEARWEEK(CURDATE(),1)";
		$sample_type_cond = '';
		//$graph_where_clause = ' WHERE thedate > DATE_SUB(NOW(), INTERVAL 1 WEEK';
		if(!empty($filters)){
			$from = $filters['from'];
			$to = $filters['to'];

			if($from != '' && $to != ''){
				$where = "WHERE fp.created_at BETWEEN '".getMysqlDateFormat($from)."'  AND '".getMysqlDateFormat($to)."'";
				$graph_where_clause = " AND thedate BETWEEN '".getMysqlDateFormat($from)."'  AND '".getMysqlDateFormat($to)."'";
				$where_package = "WHERE p.created_at BETWEEN '".getMysqlDateFormat($from)."'  AND '".getMysqlDateFormat($to)."'";
			}
		
			if(array_key_exists('facilityid',$filters) && $filters['facilityid']){
				$where_package .= ' AND p.facilityid = '.$filters['facilityid'];
			}
			if(array_key_exists('ref_lab',$filters) && $filters['ref_lab']){
				$where_package .= ' AND p.final_destination = '.$filters['ref_lab'];
			}
			if(array_key_exists('hubid',$filters) && $filters['hubid']){
				$where_clause .= ' AND pm.destination = '.$filters['hubid'];
			}
			if(array_key_exists('sample_type',$filters) && $filters['sample_type']){
				$sample_type_cond .= ' AND s.samplecategory = '.$filters['sample_type'];
			}
			
		}
		

		// get covid samples for this week
			$query = "SELECT lv.lookupvaluedescription as sampletype, SUM(s.numberofsamples) AS samples
		FROM samples s 
		INNER JOIN lookuptypevalue lv ON(lv.lookuptypevalue = s.samplecategory)
		INNER JOIN lookuptype l ON(l.id = lv.lookuptypeid)
		WHERE lv.lookuptypeid = 18 ".$graph_where_clause."
		GROUP BY lv.lookupvaluedescription ASC";
	
		$samples_graph = $samples = \DB::select($query);
		 
		$Realtime_samplestable = lava::DataTable();
		$Realtime_samplestable->addStringColumn('Sample Type')
				->addNumberColumn('Number of samples');
			foreach($samples as $line){
				$Realtime_samplestable->addRow([
				  $line->sampletype, $line->samples
				]);
			}
		//$chart = lava::LineChart('samples', $stocksTable);			
		lava::ColumnChart('samples_tracked', $Realtime_samplestable, [
			'title' => '',
			'titleTextStyle' => [
				'color'    => '#eb6b2c',
				'fontSize' => 14
			]
		]);	

		$query = "SELECT fp.id, fp.barcode as sample_id, fp.created_at as thedate, bp.barcode as container, fp.small_barcodeid, le.created_at,fp.big_barcodeid, pme.created_at as last_seen, ff.name as last_seen_facility, sf.name as sourcefacility, s.numberofsamples, lv.lookupvaluedescription as sampletype,pme.source, pme.destination, pme.longitude, pme.latitude, pme.status FROM
(SELECT p.id, p.barcode, p.created_at, p.facilityid, p.final_destination, pd.small_barcodeid, pd.big_barcodeid FROM package p 
LEFT JOIN packagedetail pd ON(p.id = pd.small_barcodeid) $where_package) as fp
LEFT JOIN
(SELECT package_id, MAX(created_at) AS created_at FROM packagemovement_events GROUP BY package_id) AS le
ON(le.package_id = fp.big_barcodeid)
LEFT JOIN packagemovement_events pme ON(pme.package_id = le.package_id AND pme.created_at = le.created_at)
INNER JOIN facility ff ON(ff.id = pme.source)
INNER JOIN facility sf ON(sf.id = fp.facilityid)
LEFT JOIN samples s ON(s.barcodeid = fp.id $sample_type_cond)
LEFT JOIN package bp ON(fp.big_barcodeid = bp.id)
LEFT JOIN lookuptypevalue lv ON(lv.lookuptypevalue = s.samplecategory AND lv.lookuptypeid = 18) 
";
		
		//dd($query);
		$package_samples = \DB::select($query);

		return view('landing.index', compact('samples_graph','all_samples', 'hubs', 'facilities','districts','this_week','request','package_samples','status_dropdown','status_dropdown_for_hubs','from','to','ref_labs','site_types','sample_types','ref_lab', 'site_type', 'sample_type'));
    }

    public function allContacts() {
        $query = "SELECT f.name as hub, d.name as district, c.category as category, c.type as type, 'desgn', firstname, lastname, c.emailaddress, c.telephonenumber  
		FROM contact as c  
		LEFT JOIN facility as f ON (c.hubid = f.id) 
		LEFT JOIN district as d ON (c.dlfpdistrictid = d.id) 
		WHERE c.isactive = 1

		UNION SELECT f.name as hub, '', '', s.type as type, s.designation as desgn, firstname, lastname, s.emailaddress, s.telephonenumber  
		FROM staff as s
		LEFT JOIN facility as f ON (s.hubid = f.id)
		WHERE s.isactive = 1
		ORDER BY firstname ASC";
		//echo $query;
		//exit;
		$contacts = \DB::select($query);

		$query = "SELECT f.id, f.name, i.name as 'ip',f.hubname, f.address, hr.name as healthregion, d.name as `district` FROM facility f
		INNER JOIN healthregion hr ON(f.healthregionid = hr.id AND ISNULL(f.parentid))
		LEFT JOIN organization i ON(i.id = f.ipid)
		LEFT JOIN hub h ON(h.id = f.hubid)
	    INNER JOIN district d ON (f.districtid = d.id)
		WHERE f.id != ''
		ORDER BY f.name ASC";
		$hubs = \DB::select($query);
		return view('contact.comprehensive_list', compact('contacts', 'hubs'));
    }

    public function login(){
    	return View('auth.login');
    }
}