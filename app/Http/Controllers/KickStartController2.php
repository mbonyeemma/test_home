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
    	/*\DB::statement("update package p
inner join
(select big_barcodeid, count(id) as no_samples from packagedetail GROUP BY big_barcodeid) s
set p.numberofsamples = s.no_samples
where p.id = s.big_barcodeid AND p.created_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01') - INTERVAL 1 MONTH");*/
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
		$facilities = array_merge_maintain_keys(array(''=>'Facility'),getAllFacilities());
		$poes = array_merge_maintain_keys(array(''=>'POE'),getAllPoes());
		$districts = array_merge_maintain_keys(array('' => 'Districts'), getAllDistricts());
		$period = getWeekEndDates();	
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
		$sitetype_type_cond = '';
		$sitetype_facility_cond = '';
		$status_dropdown = array_merge_maintain_keys(array('' => 'Fileter by status'), getHubPackageStatus());
		$status_dropdown_for_hubs = getHubPackageStatusLabel();

		
		$filters = $request->all();
		$from = $period['start'];
		$to = $period['end'];

		//$where = " WHERE p.created_at between (CURDATE() - INTERVAL 1 MONTH ) and CURDATE()";
		$where_package = $where  = " AND p.created_at between (CURDATE() - INTERVAL 1 MONTH ) and (CURDATE() + 1 )";
		$graph_where_clause = " AND thedate between (CURDATE() - INTERVAL 1 MONTH ) and CURDATE()";
		$sample_type_cond = '';
		//$graph_where_clause = ' WHERE thedate > DATE_SUB(NOW(), INTERVAL 1 WEEK';
		if(!empty($filters)){
			$from = $filters['from'];
			$to = $filters['to'];

			if($from != '' && $to != ''){
				$where = " AND p.created_at BETWEEN '".getMysqlDateFormat($from)."'  AND '".getMysqlDateFormat($to)."'";
				$graph_where_clause = " AND thedate BETWEEN '".getMysqlDateFormat($from)."'  AND '".getMysqlDateFormat($to)."'";
				$where_package = "WHERE p.created_at BETWEEN '".getMysqlDateFormat($from)."'  AND '".getMysqlDateFormat($to)."'";
			}
		
			if(array_key_exists('facilityid',$filters) && $filters['facilityid']){
				$where_package .= ' AND p.facilityid = '.$filters['facilityid'];
			}
			if(array_key_exists('poe',$filters) && $filters['poe']){
				$where_package .= ' AND p.facilityid = '.$filters['poe'];
			}
			if(array_key_exists('ref_lab',$filters) && $filters['ref_lab']){
				$where_package .= ' AND p.final_destination = '.$filters['ref_lab'];
			}
			if(array_key_exists('hubid',$filters) && $filters['hubid']){
				$where_clause .= ' AND p.hubid = '.$filters['hubid'];
			}
			if(array_key_exists('sample_type',$filters) && $filters['sample_type']){
				$where_clause .= ' AND p.test_type = '.$filters['sample_type'];
			}
			
			if(array_key_exists('site_type',$filters) && $filters['site_type']){
				$sample_t = $filters['site_type'];
				//1=>Community,2=>Isolation,3=>POEs, 4=>Facility
				if($sample_t == 1){
					$sitetype_type_cond .= ' AND p.facilityid = 900000';
				}
				if($sample_t == 4){
					$sitetype_facility_cond .= ' AND sf.parentid IS NULL AND sf.facilitylevelid <> 14';
				}
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

		$query = "SELECT p.id, p.barcode as sample_id, count(p.barcode) as total, p.created_at as thedate, bg.barcode as container, le.created_at,
le.created_at as last_seen,pd.big_barcodeid, le.created_at as last_seen, ff.name as last_seen_facility,
sf.name as sourcefacility, p.numberofsamples, lv.lookupvaluedescription as sampletype,le.source, le.destination, le.longitude, 
le.latitude, le.status, fd.name as final_destn, p.longitude, p.latitude, p.place_name,p.delivered_on,p.received_at_destination_on,
IF(sf.facilitylevelid = 14,'Poe',IF(sf.id = 899991,'Quarantine',IF(sf.id = 899992,'Isolation',IF(sf.id = 900000,'Community surveillance','Health facility')))) as collection_point,
IF(sf.id = 899991 OR sf.id = 899992 OR sf.id = 900000, p.place_name, sf.name) as collection_point_name,
IF(sf.id = 899991 OR sf.id = 899992 OR sf.id = 900000, '', d.name) as `district`,
IF(sf.id = 899991 OR sf.id = 899992 OR sf.id = 900000, '', h.name) as `hub` FROM package p
LEFT JOIN packagemovement_events le on(le.id = p.latest_event_id)
LEFT JOIN packagedetail pd ON (le.package_id = pd.big_barcodeid)
LEFT JOIN package bg ON(bg.id = pd.big_barcodeid $where)
INNER JOIN facility sf ON(sf.id = p.facilityid)
INNER JOIN facility h ON(h.id = sf.parentid)
INNER JOIN district d ON(d.id = h.districtid)
INNER JOIN facility fd ON(fd.id = p.final_destination)
INNER JOIN facility ff ON(ff.id = le.source)
INNER JOIN lookuptypevalue lv ON(lv.lookuptypevalue = p.test_type AND lv.lookuptypeid = 18  AND lv.lookuptypevalue = 19)  
".$where_package." AND p.type = 2
GROUP BY p.id, le.created_at,
le.created_at,pd.big_barcodeid, le.created_at, ff.name,
sf.name, p.numberofsamples , lv.lookupvaluedescription, le.source, le.destination, le.longitude,
le.latitude, le.`status`, fd.name, h.name, d.name, p.place_name";
		
		dd($query);
		$package_samples = \DB::select($query);

		return view('landing.index', compact('samples_graph','all_samples', 'hubs', 'facilities','districts','poes','request','package_samples','status_dropdown','status_dropdown_for_hubs','from','to','ref_labs','site_types','sample_types','ref_lab', 'site_type', 'sample_type'));
    }


    /***/
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

    public function setLatestEvent(){
    	//get all big packages
    	$query = "select id, created_at from package where type = 2 AND ID = 18581";
        $packages = \DB::select($query);

        $query = "select p.id, pd.small_barcodeid, pd.big_barcodeid, s.samplecategory from packagedetail pd 
INNER JOIN package p on (p.id = pd.small_barcodeid)
LEFT JOIN samples s on (p.id = s.barcodeid) WHERE pd.big_barcodeid = 18585";
        $packages = \DB::select($query);
       dd($packages);
        foreach ($packages as $package) {
            \DB::statement('UPDATE package set test_type = '.$package->samplecategory.' WHERE id = '.$package->small_barcodeid.'
                OR id = '.$package->big_barcodeid);
           
        }

        \DB::statement('UPDATE package p INNER JOIN samples s ON (p.id =  s.barcodeid) SET p.test_type = s.samplecategory');
    	//get all packages without any packages
        //update package set test_type = 19 where final_destination > 9999 and created_at > '2019-12-31' and (test_type is null or test_type =0) or facilityid = 900000
    	//dd('set');

    }
}