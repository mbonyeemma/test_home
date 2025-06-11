<?php
// app/Http/Controllers/KickStartController.php
namespace Khill\Lavacharts\Examples\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Lava;


//namespace App\Http\Controllers;

class KickStartController extends Controller
{
	/**
	 * Generate the starting point of the application     */

	public function index(Request $request)
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
		//$where_package = $where  = "  AND p.created_at BETWEEN CURDATE() - INTERVAL ".env('NUMBER_OF_DAYS_CUT_OFF_FOR_PACKAGES')." DAY AND CURDATE() + 1";
		$where_package = $where  = "  AND p.created_at BETWEEN DATE_SUB(NOW(), INTERVAL " . env('NUMBER_OF_DAYS_CUT_OFF_FOR_PACKAGES') . " DAY) AND NOW()";
		$graph_where_clause = " AND date_picked BETWEEN CURDATE() - INTERVAL " . env('NUMBER_OF_DAYS_CUT_OFF_FOR_PACKAGES') . " DAY AND CURDATE() + 1";

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


		$query = "SELECT p.id, p.barcode as sample_id, p.numberofsamples, count(p.barcode) as total, p.created_at as date_picked, p.barcode as container, le.created_at,
le.created_at as last_seen, le.created_at as last_seen, pmef.name as last_seen_facility,
sf.name as sourcefacility, p.numberofsamples, tt.name as testtype,p.facilityid, p.final_destination, le.source, le.destination, le.longitude, le.location,
le.latitude, le.status, fd.name as final_destn, p.longitude, p.latitude, p.place_name,p.delivered_on,p.received_at_destination_on,
IF(sf.facilitylevelid = 14,'Poe',IF(sf.id = 899991,'Quarantine',IF(sf.id = 899992,'Isolation',IF(sf.id = 900000,'Community surveillance','Health facility')))) as collection_point,
IF(sf.id = 899991 OR sf.id = 899992 OR sf.id = 900000, p.place_name, sf.name) as collection_point_name,
IF(sf.id = 899991 OR sf.id = 899992 OR sf.id = 900000, '', d.name) as `district`,
IF(sf.id = 899991 OR sf.id = 899992 OR sf.id = 900000, '', h.name) as `hub` FROM package p
LEFT JOIN packagemovement_events le on(le.id = p.latest_event_id)
INNER JOIN facility sf ON(sf.id = p.facilityid)
LEFT JOIN facility h ON(h.id = sf.parentid)
INNER JOIN district d ON(d.id = sf.districtid)
LEFT JOIN facility fd ON(fd.id = p.final_destination)
LEFT JOIN facility pmef ON(pmef.id = le.location)
INNER JOIN testtypes tt ON(tt.id = p.test_type)
" . $where_package . $where_clause . " 
GROUP BY p.id, le.created_at,
le.created_at,p.parent_id, le.created_at, pmef.name,
sf.name, p.numberofsamples , le.source, le.destination, le.longitude,
le.latitude, le.`status`, fd.name, h.name, d.name, p.place_name, p.facilityid, p.final_destination,le.location";
		$package_samples = \DB::select($query);
		return view('landing.index', compact('hubs', 'facilities', 'districts', 'poes', 'request', 'package_samples', 'status_dropdown', 'status_dropdown_for_hubs', 'from', 'to', 'ref_labs', 'site_types', 'test_types', 'ref_lab', 'site_type', 'test_type', 'tab', 'tab_1_class', 'tab_2_class', 'page_type'));
	}


	/***/
	public function allContacts()
	{
		$query = "SELECT f.name as hub, d.name as district, c.category as category, c.type as type, 'desgn', firstname, lastname, c.emailaddress, c.telephonenumber  
		FROM contact as c  
		LEFT JOIN facility as f ON (c.hubid = f.id) 
		LEFT JOIN district as d ON (c.dlfpdistrictid = d.id) 
		WHERE c.isactive = 1
		UNION SELECT f.name as hub, '', '', s.type as type, s.designation as desgn, firstname, lastname, s.emailaddress, s.telephonenumber  
		FROM stapmef as s
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

	public function login()
	{
		return View('auth.login');
	}

	public function setLatestEvent()
	{
		//get all big packages
		$query = "select id, created_at from package where type = 2 AND ID = 18581";
		$packages = \DB::select($query);

		$query = "select p.id, pd.id, pd.parent_id, s.test_type from packagedetail pd 
INNER JOIN package p on (p.id = pd.id)
LEFT JOIN samples s on (p.id = s.package_id) WHERE pd.parentid = 18585";
		$packages = \DB::select($query);
		//dd($packages);
		foreach ($packages as $package) {
			\DB::statement('UPDATE package set test_type = ' . $package->test_type . ' WHERE id = ' . $package->small_barcodeid . '
                OR id = ' . $package->big_barcodeid);
		}

		\DB::statement('UPDATE package p INNER JOIN samples s ON (p.id =  s.barcodeid) SET p.test_type = s.test_type');
		//get all packages without any packages
		//update package set test_type = 19 where final_destination > 9999 and created_at > '2019-12-31' and (test_type is null or test_type =0) or facilityid = 900000
		//dd('set');

	}
}
