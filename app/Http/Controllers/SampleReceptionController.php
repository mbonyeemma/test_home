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
use Illuminate\Support\Facades\Input; 
use Illuminate\Pagination\LengthAwarePaginator;
use DB;

class SampleReceptionController extends Controller {

    public function __construct() {
        //$this->middleware(['auth', 'clearance'])->except('index', 'show');
    }
	public function create(){
		$hubs = array_merge_maintain_keys(array('' => 'Select hub'), getAllHubs());
		$facilities = array('' => 'Select facility');

		$all_facilities  = array_merge_maintain_keys(array('Select facility' => 'Select a facility'), getAllFacilities());
		$test_types = array_merge_maintain_keys(array('Select sample type' => 'Sample types'), getTestTypes()); 

		//return view('reception.create', compact('samples', 'hubs', 'facilities','status_dropdown','all_facilities','test_types'));
		return view('reception.create', compact('hubs', 'facilities','all_facilities','test_types'));
		
	}
	public function list(Request $request){
	// get the samples for this month
		$hubs = array_merge_maintain_keys(array('' => 'Filter by hub'), getAllHubs());
		$facilities = array('' => 'Filter by facility');
		$districts = array_merge_maintain_keys(array('' => 'Filter by district'), getAllDistricts());
		$status_dropdown = array_merge_maintain_keys(array('' => 'Fileter by status'), getCphlPackageStatus());
		$all_facilities  = array_merge_maintain_keys(array('' => 'Select a facility'), getAllFacilities());
		$test_types = array_merge_maintain_keys(array('' => 'Sample types'), getTestTypes()); 
		$ref_labs = array_merge_maintain_keys(array('' => 'Filter by hub'), getReferenceLabs());
		$incharge_clause = '';

		//search conditions
		$searchString = $request->search;
	    $dateFrom = $request->from;
	    $dateTo = $request->to;
	    $status = $request->status;
	    $hub = $request->hubid;
	    $searchstring = $request->search;

	    if($searchString != ''){
			
		    $packages = Package::with('facility', 'facility.hub' )
		    	->where(function($q) use ($searchString){	    		
			    	$q->orWhereHas('facility', function($q) use ($searchString){
		            	$q->where('name', 'like', '%' . $searchString . '%');
			    	});
				})->orderBy('created_at', 'DESC');
			$packages = $packages->orWhere(function($q) use ($searchString){
	        	$q->where('barcode', 'like', '%' . $searchString . '%');
	      	})->paginate(15);
		
			if (count($packages) == 0) 
		        {

		            return view('reception.untracked', compact('all_facilities', 'test_types','hubs', 'ref_labs','searchString'));
		        }
		}elseif ($status) {      
	    	$packages = Package::where(function($q) use ($status)
	      {
	       $q->where('status', 'like', '%' . $status . '%');
	      })->orderBy('created_at', 'DESC')->paginate(15);
	        if (count($packages) == 0) 
	          {
	            Session::flash('message', trans('No Match Found'));
	          }
		}elseif ($dateFrom||$dateTo) {
      
	    $packages = Package::where(function($q) use ($dateFrom, $dateTo)
	      {
	        if($dateFrom){
	          $q->where('created_at', '>=', $dateFrom);
	        }
	        if($dateTo){
	          $dateTo = $dateTo . ' 23:59:59';
	          $q->where('created_at', '<=', $dateTo);
	        }
	      })->orderBy('created_at', 'DESC')->paginate(15);
	        if (count($packages) == 0) 
	          {
	            Session::flash('message', trans('No Match Found'));
	          }
	    }else
	    $packages = Package::with('facility')->orderBy('created_at', 'DESC')->paginate(15);
	      // dd($packages);
					
	return view('reception.list', compact('packages', 'hubs', 'facilities','districts','status_dropdown','all_facilities','test_types','ref_labs'));
}

	// paginator function
			
	public function arrayPaginator($array, $request)
	{
	    $page = Input::get('page', 1);
	    $perPage = 10;
	    $offset = ($page * $perPage) - $perPage;

	    return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
	        ['path' => $request->url(), 'query' => $request->query()]);
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

	public function receiveSmallPackage_old(Request $request)
	{
		$form_data = $request->all();
		
		$form_data['user_id'] = Auth::user()->id;
		\DB::transaction(function($request_obj) use ($form_data) {
			$small_package = Package::findOrFail($form_data['id']);
			$package_receipt = new PackageReceipt;
			$package_receipt->packageid = $form_data['id'];
			$package_receipt->packagetype = $form_data['user_id'];
			$package_receipt->received_by = Auth::user()->id;
			$package_receipt->previous_status = $small_package->status;
			$package_receipt->numberofsamples = $form_data['numberofsamples'] == ''? 0:$form_data['numberofsamples'];
			$package_receipt->created_by = $form_data['user_id'];
			$package_receipt->save();

			//mark package as received at CPHL
			$small_package->status = 3;
			$small_package->is_tracked_from_facility = $form_data['is_tracked_from_facility'];
			if($form_data['receipt_date'] != ''){
				$small_package->received_at_destination_on = $form_data['receipt_date'];
			}else {
				$small_package->received_at_destination_on = date('Y-m-d H:m:s');
			}
			
			$small_package->numberofsamples = $form_data['numberofsamples'] == ''? 0:$form_data['numberofsamples'];
			$small_package->save();
			if(!$small_package->first_received_at){
				$small_package->first_received_at = 2490;
			}
			$event = new PackageMovementEvent;
			$event->package_id = $small_package->id;
			$event->source = 2490;
			$event->destination = 2490;
			$event->status = 3;
			$event->location = 2490;
			$event->category_id = $small_package->test_type;
			//$event->longitude 
			//$event->latitude
			$event->place_name = 'CPHL';
			$event->created_by = $form_data['user_id'];
			if($form_data['receipt_date'] != ''){
				$event->created_at = $form_data['receipt_date'];
			}
			$event->save();
			//update package with latest event_id
			$small_package->latest_event_id = $event->id;

			if($form_data['is_to_be_transfered']){
				//create transfer event
				$ref_event = new PackageMovementEvent;
				$ref_event->package_id = $small_package->id;
				$ref_event->source = 2490;
				$ref_event->destination = $form_data['transfer_to'];
				$ref_event->status = 4;
				$ref_event->location = 2490;
				$ref_event->category_id = $small_package->test_type;
				//$ref_event->longitude 
				//$ref_event->latitude
				$ref_event->place_name = 'CPHL';
				$ref_event->created_by = $form_data['user_id'];

				$ref_event->save();
				//update package with latest event_id
				$small_package->latest_event_id = $ref_event->id;

			}
			//update package 
			$small_package->save();
		});
		return redirect()->route('reception.list')->with('success','Package successfully received.');
	}

	public function receiveSmallPackage(Request $request)
	{
		$form_data = $request->all();
		$form_data['user_id'] = Auth::user()->id;
		\DB::transaction(function($request_obj) use ($form_data) 
		{
			$hubbid = Auth::user()->hubid;
			$small_package = Package::findOrFail($form_data['id']);
			$package_receipt = new PackageReceipt;
			$package_receipt->packageid = $form_data['id'];
			$package_receipt->packagetype = $form_data['user_id'];
			$package_receipt->received_by = Auth::user()->id;
			$package_receipt->previous_status = $small_package->status;
			$package_receipt->numberofsamples = $form_data['numberofsamples'] == ''? 0:$form_data['numberofsamples'];
			$package_receipt->created_by = $form_data['user_id'];
			$package_receipt->save();

			//mark package as received at CPHL
			$small_package->status = 3;
			$small_package->is_tracked_from_facility = $form_data['is_tracked_from_facility'];
			if($form_data['receipt_date'] != ''){
				$small_package->received_at_destination_on = $form_data['receipt_date'];
			}else {
				$small_package->received_at_destination_on = date('Y-m-d H:m:s');
			}
			
			$small_package->numberofsamples = $form_data['numberofsamples'] == ''? 0:$form_data['numberofsamples'];
			$small_package->save();
			if(!$small_package->first_received_at){
				$small_package->first_received_at = 2490;
			}
			$event = new PackageMovementEvent;
			$event->package_id = $small_package->id;
			$event->source = $hubbid;
			$event->destination = $hubbid;
			$event->status = 3;
			$event->location = $hubbid;
			$event->category_id = $small_package->test_type;
			//$event->longitude 
			//$event->latitude
			$event->place_name = 'CPHL';
			$event->created_by = $form_data['user_id'];
			if($form_data['receipt_date'] != ''){
				$event->created_at = $form_data['receipt_date'];
			}
			$event->save();
			//update package with latest event_id
			$small_package->latest_event_id = $event->id;

			if($form_data['is_to_be_transfered']){
				//create transfer event
				$ref_event = new PackageMovementEvent;
				$ref_event->package_id = $small_package->id;
				$ref_event->source = $hubbid;
				$ref_event->destination = $form_data['transfer_to'];
				$ref_event->status = 4;
				$ref_event->location = $hubbid;
				$ref_event->category_id = $small_package->test_type;
				//$ref_event->longitude 
				//$ref_event->latitude
				$ref_event->place_name = 'CPHL';
				$ref_event->created_by = $form_data['user_id'];

				$ref_event->save();
				//update package with latest event_id
				$small_package->latest_event_id = $ref_event->id;

			}
			//update package 
			$small_package->save();
		});
		return redirect()->route('reception.list')->with('success','Package successfully received.');
	}

	public function receiveSmallPackagel(Request $request)
	{
		$form_data = $request->all();
			
		$form_data['user_id'] = 1498;
		\DB::transaction(function($request_obj) use ($form_data) 
		{
			$hubbid = 2490;
			$small_package = Package::where('barcode', '=', $form_data['barcode'])->orderby('id', 'desc')->first();
			// dd($small_package->id);
			$package_receipt = new PackageReceipt;
			$package_receipt->packageid = $small_package->id;
			$package_receipt->packagetype = $small_package->type;
			$package_receipt->received_by = 1498;
			$package_receipt->previous_status = $small_package->status;
			$package_receipt->numberofsamples = $small_package->numberofsamples;
			$package_receipt->created_by = 1498;
			$package_receipt->save();

			//mark package as received at CPHL
			$small_package->status = 3;
			$small_package->is_tracked_from_facility = 1;
			if($form_data['receipt_date'] != '')
			{
				$small_package->received_at_destination_on = $form_data['receipt_date'];
			}else {
				$small_package->received_at_destination_on = date('Y-m-d H:m:s');
			}

			// dd($small_package);
			
			$small_package->numberofsamples = $small_package->numberofsamples;
			$small_package->save();
			if(!$small_package->first_received_at){
				$small_package->first_received_at = 2490;
			}
			$event = new PackageMovementEvent;
			$event->package_id = $small_package->id;
			$event->source = $hubbid;
			$event->destination = $hubbid;
			$event->status = 3;
			$event->location = $hubbid;
			$event->category_id = $small_package->test_type;
			$event->place_name = 'CPHL';
			$event->created_by = 1498;
			if($form_data['receipt_date'] != ''){
				$event->created_at = $form_data['receipt_date'];
			}
			$event->save();
			//update package with latest event_id
			$small_package->latest_event_id = $event->id;

			// if($form_data['is_to_be_transfered'])
			// {
			// 	//create transfer event
			// 	$ref_event = new PackageMovementEvent;
			// 	$ref_event->package_id = $small_package->id;
			// 	$ref_event->source = $hubbid;
			// 	$ref_event->destination = $form_data['transfer_to'];
			// 	$ref_event->status = 4;
			// 	$ref_event->location = $hubbid;
			// 	$ref_event->category_id = $small_package->test_type;
			// 	//$ref_event->longitude 
			// 	//$ref_event->latitude
			// 	$ref_event->place_name = 'CPHL';
			// 	$ref_event->created_by = 1498;

			// 	$ref_event->save();
			// 	//update package with latest event_id
			// 	$small_package->latest_event_id = $ref_event->id;

			// }
			//update package 
			$small_package->save();
		});
		return redirect()->route('reception.list')->with('success','Package successfully received.');
	}

	public function receiveSmallPackagex(Request $request)
	{
		$form_data = $request->all();
		$form_data['user_id'] = 1498;
		\DB::transaction(function($request_obj) use ($form_data) 
		{
			
			$small_package = Package::where('barcode', '=', $form_data['barcode'])->orderby('id', 'desc')->first();
			$package_receipt = new PackageReceipt;
			$package_receipt->packageid = $small_package->id;
			$hubbid = $small_package->final_destination;
			$package_receipt->packagetype = $form_data['user_id'];
			$package_receipt->received_by = 1498;
			$package_receipt->previous_status = $small_package->status;
			$package_receipt->numberofsamples = $small_package->numberofsamples;
			$package_receipt->created_by = $form_data['user_id'];
			$package_receipt->save();

			//mark package as received at CPHL
			$small_package->status = 3;
			$small_package->is_tracked_from_facility = 1;
			if($form_data['receipt_date'] != ''){
				$small_package->received_at_destination_on = $form_data['receipt_date'];
			}else {
				$small_package->received_at_destination_on = date('Y-m-d H:m:s');
			}
			
			// $small_package->numberofsamples = $form_data['numberofsamples'] == ''? 0:$form_data['numberofsamples'];
			$small_package->save();
			if(!$small_package->first_received_at){
				$small_package->first_received_at = 2490;
			}
			$event = new PackageMovementEvent;
			$event->package_id = $small_package->id;
			$event->source = $hubbid;
			$event->destination = $hubbid;
			$event->status = 3;
			$event->location = $hubbid;
			$event->category_id = $small_package->test_type;
			//$event->longitude 
			//$event->latitude
			$event->place_name = 'CPHL';
			$event->created_by = $form_data['user_id'];
			if($form_data['receipt_date'] != ''){
				$event->created_at = $form_data['receipt_date'];
			}
			$event->save();
			//update package with latest event_id
			$small_package->latest_event_id = $event->id;

			// if($form_data['is_to_be_transfered']){
			// 	//create transfer event
			// 	$ref_event = new PackageMovementEvent;
			// 	$ref_event->package_id = $small_package->id;
			// 	$ref_event->source = $hubbid;
			// 	$ref_event->destination = $form_data['transfer_to'];
			// 	$ref_event->status = 4;
			// 	$ref_event->location = $hubbid;
			// 	$ref_event->category_id = $small_package->test_type;
			// 	//$ref_event->longitude 
			// 	//$ref_event->latitude
			// 	$ref_event->place_name = 'CPHL';
			// 	$ref_event->created_by = $form_data['user_id'];

			// 	$ref_event->save();
			// 	//update package with latest event_id
			// 	$small_package->latest_event_id = $ref_event->id;

			// }
			//update package 
			// dd($small_package);
			$small_package->save();
		});

		// $ret_arr['status'] = 501;
		// $ret_arr['status_desc'] = 'The package was not found';
		// $ret_arr['delivered_on'] = '';
		// $ret_arr['date_picked'] = '';
		// return response()->json($ret_arr);
	}
	//close if else package is retrieved
	//return redirect()->route('reception.list')->with('success','Package successfully received.');


	public function processReceipt(Request $request)
	 {

		$form_data = $request->all();
		$form_data['user_id'] = Auth::user()->id;
		\DB::transaction(function($request_obj) use ($form_data) {
			$packages = $form_data['packages'];
			try {
				foreach($packages as $package){
					
					$small_package = Package::findOrFail($package['small_package_id']);
					//create record for package receipt
					$package_receipt = new PackageReceipt;
					$package_receipt->packageid = $package['small_package_id'];
					$package_receipt->packagetype = $small_package->type;
					$package_receipt->received_by = $form_data['user_id'];
					$package_receipt->previous_status = $small_package->status;
					$package_receipt->numberofsamples = $package['number_of_samples'];
					$package_receipt->created_by = $form_data['user_id'];
					$package_receipt->save();

					//mark package as received at CPHL
					$small_package->status = 3;
					$small_package->is_tracked_from_facility = $package['is_tracked_from_facility'];
					$small_package->save();
				}
				//mark the big package as received
				$big_package = Package::findOrFail($form_data['big_package_id']);
				$big_package->status = 3;
				$big_package->save();

				//now save the
				$event = new PackageMovementEvent;
				$event->package_id = $big_package->id;
				$event->source = 2490;
				$event->destination = 2490;
				$event->status = 3;
				$event->location = 2490;
				$event->category_id = $big_package->test_type;
				//$event->longitude 
				//$event->latitude
				
				$event->place_name = 'CPHL';
				$event->created_by = $form_data['user_id'];

				$event->save();

				//update package with latest event_id
				$big_package->latest_event_id = $event->id;

				if($form_data['is_to_be_transfered']){
					//create transfer event
					$ref_event = new PackageMovementEvent;
					$ref_event->package_id = $package->id;
					$ref_event->source = 2490;
					$ref_event->destination = 2490;
					$ref_event->status = 4;
					$ref_event->location = 2490;
					$ref_event->category_id = $big_package->test_type;
					//$ref_event->longitude 
					//$ref_event->latitude
					$ref_event->place_name = 'CPHL';
					$ref_event->created_by = $form_data['user_id'];

					$ref_event->save();
					//update package with latest event_id
					$big_package->latest_event_id = $ref_event->id;

				}
				//update package 
				$big_package->save();			
			}catch (\Exception $e) {
				
				print_r('faild to save'.$e);
				exit;
				
	            //->with('flash_message', 'failed');
			}
		});
		return redirect()->route('reception.list')->with('success','Package successfully received.');
		//exit;
	}
	public function receiveSample($id){
		$ref_labs = array_merge_maintain_keys(array('' => 'Filter by hub'), getReferenceLabs());
		$package = Package::findOrFail($id);
		//if small package, return to different row 
		if($package->type == 1){
			return view('reception.receive_small_package',compact('package','id','ref_labs'));
		}else{
			$packages = \DB::select('SELECT p.barcode, p.id, p.status FROM packagedetail pd  
					INNER JOIN package p ON (pd.small_barcodeid = p.id)
					WHERE pd.big_barcodeid ='.$id.' GROUP BY p.id,p.barcode,p.status');
			
			if(count($packages)){
				return view('reception.receive',compact('package','id','ref_labs'));
			}else{
				//save the untracked barcode and redirect to receive individual packages
				return redirect()->route('reception.list');
			}
		}
		
	}
	public function saveunscannedbarcode(Request $request){
		$form_data = $request->all();
		$form_data['user_id'] = Auth::user()->id;
		\DB::transaction(function($request_obj) use ($form_data) {
			$destination = $form_data['transfer_to'] != ''? $form_data['transfer_to']:2490;
			$untrackedp = new UntrackedPackage;
			$untrackedp->barcode = $form_data['barcode'];			
			if(in_array('facilityid',$form_data)){
				$untrackedp->facilityid = $form_data['facilityid'];
			}
			$untrackedp->hubid = getHubforFacility($form_data['facilityid']);
			$untrackedp->type = $form_data['type'];
			$untrackedp->created_by = $form_data['user_id'];
			$untrackedp->save();
			//save creating the process for tracking
			//$cphl_gps = getCPHLGPS();
			$package = new Package;
			$package->barcode = $form_data['barcode'];
			$package->facilityid = $form_data['facilityid'];
			$package->hubid = getHubforFacility($form_data['facilityid']);
			$package->test_type = $form_data['test_type'];
			$package->delivered_on = date('Y-m-d H:i:s',strtotime($form_data['delivered_on']));;
			$package->created_at = $package->date_picked = date('Y-m-d H:i:s',strtotime($form_data['picked_on']));
			$package->type = 2;
			$package->status = 3;
			$package->final_destination = $destination;
			$package->numberofsamples = $form_data['numberofsamples'] == ''? 0:$form_data['numberofsamples'];
			$package->received_by = $form_data['user_id'];
			$package->is_tracked_from_facility = 0;
			$package->save();

			//now save the
			$event = new PackageMovementEvent;
			$event->package_id = $package->id;
			$event->source = 2490;
			$event->destination = 2490;
			$event->status = 3;
			$event->place_name = 'CPHL';
			$event->location = 2490;
			$event->category_id = $form_data['test_type'];
			//$event->longitude 
			//$event->latitude
			$event->place_name = 'CPHL';
			$event->created_by = $form_data['user_id'];

			$event->save();
			//update package with latest event_id
			$package->latest_event_id = $event->id;

			if($form_data['is_to_be_transfered']){
				//create transfer event
				$ref_event = new PackageMovementEvent;
				$ref_event->package_id = $package->id;
				$ref_event->source = 2490;
				$ref_event->destination = 2490;
				$ref_event->status = 4;
				$ref_event->location = 2490;
				$ref_event->category_id = $form_data['test_type'];
				//$ref_event->longitude 
				//$ref_event->latitude
				$ref_event->place_name = 'CPHL';
				$ref_event->created_by = $form_data['user_id'];

				$ref_event->save();
				//update package with latest event_id
				$package->latest_event_id = $ref_event->id;

			}
			//update package 
			$package->save();			
			/**/
		});
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
				return redirect()->route('reception.list')->with('success','Package successfully received.');
	}

	public function getDistrictHub($facilityid){
		$query = 'select d.name as `district`,h.name as `hub` from facility f
				inner join district d on d.id = f.districtid
				inner join facility h on f.parentid = h.id
				where f.id = '.$facilityid;
		$result = \DB::select($query);
		echo json_encode(['district' =>$result[0]->district ,'hub' =>$result[0]->hub]);
	}


	//eloquent functions

	public function cphl(Request $request)
	{
		
	}

}