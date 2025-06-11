<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Covid;
use Auth;
use Session;
use \Lava;
use \App\Models\Facility as Facility;
use Excel;

class CovidController extends Controller {
	/*
	 * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request) {

    	// get the samples for this month
		$hubs = array_merge_maintain_keys(array('' => 'Select a hub'), getAllHubs());
		$facilities = array_merge_maintain_keys(array(''=>'Select a facility'),getAllFacilities());
		$districts = array_merge_maintain_keys(array('' => 'Select a district'), getAllDistricts());
		$test_types = array_merge_maintain_keys(array('' => 'Sample types'), getTestTypes());
		$this_week = getWeekEndDates();
		$from = $this_week['start'];
		$to =  $this_week['end'];
		$incharge_clause = '';
		
		$where_clause = '';
		$where = 'WHERE  c.transactiondate > DATE_SUB(NOW(), INTERVAL 1 WEEK)';
		$filters = $request->all();
		
		if(!empty($filters)){
			$from = $filters['from'];
			$to = $filters['to'];
			if($from != '' && $to != ''){
				$where = "WHERE c.transactiondate BETWEEN '".getMysqlDateFormat($from)."'  AND '".getMysqlDateFormat($to)."'";
			}
		
			if(array_key_exists('facilityid',$filters) && $filters['facilityid']){
				$where_clause .= ' AND c.facilityid = '.$filters['facilityid'];
			}
			if(array_key_exists('districtid',$filters) && $filters['districtid']){
				$where_clause .= ' AND d.id = '.$filters['districtid'];
			}
			if(array_key_exists('test_type',$filters) && $filters['test_type']){
				$where_clause .= ' AND c.test_type = '.$filters['test_type'];
			}
			/*if(array_key_exists('status',$filters) && $filters['status']){
				$where_clause .= ' AND p.status = '.$filters['status'];
			}*/
			
		}
		$query = "SELECT c.id, c.numberofsamples, c.transactiondate,f.name as facility, lv.lookupvaluedescription as test_type, d.name as district FROM covid c
LEFT JOIN facility f ON (c.facilityid = f.id) 
LEFT JOIN district d ON (f.districtid = d.id) 
INNER JOIN lookuptypevalue lv ON(lv.lookuptypevalue = c.test_type)
INNER JOIN lookuptype l ON(l.id = lv.lookuptypeid AND lv.lookuptypeid = 18)
".$where.$where_clause." 
group by c.id, c.numberofsamples,c.transactiondate,f.name,d.name, lv.lookupvaluedescription";		//echo $query; exit;
		
		$samples = \DB::select($query);	

		$query = "SELECT lv.lookupvaluedescription as sampletype, SUM(c.numberofsamples) AS samples
		FROM covid c 
		LEFT JOIN facility f ON (c.facilityid = f.id) 
		LEFT JOIN district d ON (f.districtid = d.id) 
		INNER JOIN lookuptypevalue lv ON(lv.lookuptypevalue = c.test_type)
		INNER JOIN lookuptype l ON(l.id = lv.lookuptypeid AND lv.lookuptypeid = 18)
		".$where.$where_clause."
		GROUP BY lv.lookupvaluedescription ASC";
		$sample_summary_totals = \DB::select($query);

		return view('covid.list', compact('samples', 'hubs', 'facilities','districts','this_week','request','test_types','from','to','sample_summary_totals'));        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$facilities = array_merge_maintain_keys(array('' => 'Select One'),getAllFacilities());
		$test_types = array_merge_maintain_keys(array('' => 'Sample types'), getSampleTypes());
		
      	return View('covid.create', compact('facilities','test_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) { 
		$data = $request->all();
	    $samples = $data['samples'];
	    //dd($samples);
	    foreach($samples as $key=>$value){ 
	    	//dd($value);
	    	$covid = new Covid();                   
	        //$the_streams[] = $value;
	        $covid->transactiondate = date('Y-m-d', strtotime($data['transactiondate']));
	        $covid->numberofsamples = $value['numberofsamples'];
			$covid->facilityid = $value['facilityid'];
			$covid->test_type = $value['test_type'];
			$covid->status = 2;
			$covid->save();
	    }

		/*$covid = new Covid();
		$covid->transactiondate = date('Y-m-d', strtotime($request->transactiondate));
		$covid->numberofsamples = $request->numberofsamples;
		$covid->facilityid = $request->facilityid;
		$covid->status = 2;
		$covid->save();*/
		return redirect()->route('covid.create');
	}

}