<?php
namespace Khill\Lavacharts\Examples\Controllers;
namespace App\Http\Controllers;
use Illuminate\Http\Request;

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
use \App\Models\Package as Package;
use \App\Models\PackageMovement as PackageMovement;
use \App\Models\PackageMovementEvent as PackageMovementEvent;
use \App\Models\PackageDetail as PackageDetail;
use \App\Models\PackageReceipt as PackageReceipt;
use \App\Models\UntrackedPackage as UntrackedPackage;

class SamplesController extends Controller {

    public function __construct() {
        //$this->middleware(['auth', 'clearance'])->except('index', 'show');
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

	public function sampleList(Request $request){
			// get the samples for this month
			$hubs = array_merge_maintain_keys(array('' => 'Select a hub'), getAllHubs());
			$facilities = array_merge_maintain_keys(array('' => 'Select a facility'), getAllFacilities());
			$districts = array_merge_maintain_keys(array('' => 'Select a district'), getAllDistricts());
			
			$incharge_clause = '';
			if(Auth::user()->hasRole('hub_coordinator')){
				$incharge_clause .= " AND s.hubid = '".Auth::user()->hubid."'";
				$facilities = array_merge_maintain_keys(array(''=>'Select a facility'),getFacilitiesforHub(Auth::user()->hubid));
			}
			$graph_title = 'Samples last month';
			$where_clause = '';
			$where = 'WHERE MONTH(thedate) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)';
			$filters = $request->all();
			if(!empty($filters)){
				$graph_title = 'Samples for selected options';
				if($filters['from'] != '' && $filters['to'] != ''){
					$where = "WHERE s.thedate BETWEEN '".getMysqlDateFormat($filters['from'])."'  AND '".getMysqlDateFormat($filters['to'])."'";
				}
			
				if(array_key_exists('facilityid',$filters) && $filters['facilityid']){
					$where_clause .= ' AND s.facilityid = '.$filters['facilityid'];
				}
				if(array_key_exists('hubid',$filters) && $filters['hubid']){
					$where_clause .= ' AND h.id = '.$filters['hubid'];
				}
				if(array_key_exists('districtid',$filters) && $filters['districtid']){
					$where_clause .= ' AND d.id = '.$filters['districtid'];
				}
			}
			$query = "SELECT h.hubname as hub, d.name as district, f.name as facility,
	SUM(CASE WHEN s.test_type = 1 THEN s.numberofsamples END) AS `VL`,
	SUM(CASE WHEN s.test_type = 2 THEN s.numberofsamples  END) AS `HIVEID`,
	SUM(CASE WHEN s.test_type = 3 THEN s.numberofsamples  END) AS `SickleCell`,
	SUM(CASE WHEN s.test_type = 4 THEN s.numberofsamples  END) AS `CD4CD8`,
	SUM(CASE WHEN s.test_type = 5 THEN s.numberofsamples  END) AS `Genexpert`,
	SUM(CASE WHEN s.test_type = 6 THEN s.numberofsamples  END) AS `CBCFBC`,
	SUM(CASE WHEN s.test_type = 7 THEN s.numberofsamples  END) AS `LFTS`,
	SUM(CASE WHEN s.test_type = 8 THEN s.numberofsamples  END) AS `RFTS`,
	SUM(CASE WHEN s.test_type = 9 THEN s.numberofsamples  END) AS `Culturesensitivity`,
	SUM(CASE WHEN s.test_type = 10 THEN s.numberofsamples  END) AS `MTBCultureDST`,
	SUM(s.numberofsamples) as total
	FROM samples s  
	LEFT JOIN  facility f ON (f.id = s.facilityid)
	LEFT JOIN  facility AS h ON (f.parentid = h.id)
	LEFT JOIN  district AS d ON (f.districtid = d.id)
	 ".$where.$where_clause.$incharge_clause."
	GROUP BY s.facilityid ASC";
	
	$samples = \DB::select($query);
		 
		$query = "SELECT lv.lookupvaluedescription as sampletype, SUM(s.numberofsamples) AS samples
	FROM samples s 
	INNER JOIN lookuptypevalue lv ON(lv.lookuptypevalue = s.test_type)
	INNER JOIN lookuptype l ON(l.id = lv.lookuptypeid AND lv.lookuptypeid = 18 )
	".$where.$where_clause.$incharge_clause."
	GROUP BY lv.lookupvaluedescription ASC";
		   $samples_graph = \DB::select($query);
		 
		 $samplestable = lava::DataTable();
		$samplestable->addStringColumn('Sample Type')
				->addNumberColumn('Number of samples');
			foreach($samples_graph as $line){
				$samplestable->addRow([
				  $line->sampletype, $line->samples
				]);
			}
			//$chart = lava::LineChart('samples', $stocksTable);			
			lava::ColumnChart('samples', $samplestable, [
				'title' => $graph_title,
				'titleTextStyle' => [
					'color'    => '#eb6b2c',
					'fontSize' => 14
				]
			]);
			
		return view('samaples.all', compact('samples', 'hubs', 'facilities','districts', 'samples_graph'));
	}
	
	
		/*
		DB::beginTransaction(); //Start transaction!

		try{
		   //saving logic here
		   $ship = $ship->save();
		   Ship::find($ship->id)->captain()->save($captain);
		}
		catch(\Exception $e)
		{
		  //failed logic here
		   DB::rollback();
		   throw $e;
		}

		DB::commit();
		*/
		/*
			DB::beginTransaction();

			try {
			    // Validate, then create if valid
			    $newAcct = Account::create( ['accountname' => Input::get('accountname')] );
			} catch(ValidationException $e)
			{
			    // Rollback and then redirect
			    // back to form with errors
			    DB::rollback();
			    return Redirect::to('/form')
			        ->withErrors( $e->getErrors() )
			        ->withInput();
			} catch(\Exception $e)
			{
			    DB::rollback();
			    throw $e;
			}

			try {
			    // Validate, then create if valid
			    $newUser = User::create([
			        'username' => Input::get('username'),
			        'account_id' => $newAcct->id
			    ]);
			} catch(ValidationException $e)
			{
			    // Rollback and then redirect
			    // back to form with errors
			    DB::rollback();
			    return Redirect::to('/form')
			        ->withErrors( $e->getErrors() )
			        ->withInput();
			} catch(\Exception $e)
			{
			    DB::rollback();
			    throw $e;
			}

			// If we reach here, then
			// data is valid and working.
			// Commit the queries!
			DB::commit();
		*/
				return redirect()->route('samples.all');
	}
}