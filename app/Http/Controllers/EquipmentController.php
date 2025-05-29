<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use \App\Models\LookupType as LookupType;
use \App\Models\Equipment as Equipment;
use \App\Models\EquipmentBreakDown as EquipmentBreakDown;
use \App\Models\EquipmentBreakDownAction as EquipmentBreakDownAction;
use \App\Models\EquipmentBreakDownReason as EquipmentBreakDownReason;
class EquipmentController extends Controller {

    public function __construct() {
       // $this->middleware(['auth', 'clearance']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	
    public function index() {
		$where_condition = '';
		$title = 'View Bike List';
		$status = '';
		if(Auth::user()->hasRole('hub_coordinator')){
			$where_condition = " AND e.hubid ='".Auth::user()->hubid."'";
		}
		$query = "SELECT e.enginenumber, e.breakdownid, e.yearofmanufacture, e.status, e.id, e.hubid, e.numberplate, f.hubname FROM equipment e
		INNER JOIN facility f ON(e.hubid = f.id)
		WHERE e.id != '' ".$where_condition."
		ORDER BY e.numberplate ASC";
		$equipment = \DB::select($query);
        return view('equipment.list', compact('equipment', 'status','title'));
        
    }
	public function elist($status){
		if($status == 2){
			$title = 'Bikes Broken Down';
		}elseif($status == 0){
			$title = 'Bikes withous Service Contract';
		}
		$status_query = '';
		
		if(Auth::user()->hasRole('hub_coordinator')){
			$equipment = Equipment::where('hubid',Auth::user()->hubid)->where('status',$status)->orderby('id', 'desc')->paginate(10);
		}else{
			$equipment = Equipment::orderby('id', 'desc')->where('status',$status)->paginate(10); 
		}
		//print_R($equipment);
		//exit;
        return view('equipment.elist', compact('equipment','title'));
		
	}
	public function servicecont($service){
		if(Auth::user()->hasRole('hub_coordinator')){
			$equipment = Equipment::where('hubid',Auth::user()->hubid)->where('hasservicecontract',$service)->orderby('id', 'desc')->paginate(10);
		}else{
			$equipment = Equipment::orderby('id', 'desc')->where('hasservicecontract',$service)->paginate(10); 
		}
		
        return view('equipment.elist', compact('equipment','status'));		
	}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$hubsdropdown = array_merge_maintain_keys(array('' =>'Select hub'),getAllHubs());
		$lt = new LookupType();
		$lt->name = 'YES_NO';
		$yesnooptions = $lt->getOptionValuesAndDescription();		
		$lt->name = 'SERVICE_FREQ_UNITS';
		$servicefreqdropdown = $lt->getOptionValuesAndDescription();		
		$lt->name = 'WARRANTY_UNITS';
		$warrantyunitsdropdown = $lt->getOptionValuesAndDescription();		
       return View('equipment.create', compact('hubsdropdown', 'yesnooptions', 'servicefreqdropdown', 'warrantyunitsdropdown'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) { 
		$this->validate($request, [
			'numberplate' => 'unique:equipment',
			'enginenumber' => 'nullable|unique:equipment'
		]);
		//Validate all fields
         $equipment = new \App\Models\Equipment;
		try {
			if(Auth::user()->hasRole('hub_coordinator')){
				$equipment->facilityid = Auth::user()->hubid;
				$equipment->hubid = Auth::user()->hubid;
        	}else{
				$equipment->facilityid = $request->facilityid;
				$equipment->hubid = $request->facilityid;
			}
			$equipment->type = 1;
			$equipment->createdby = \Auth()->user()->id;
			$equipment->enginenumber = $request->enginenumber;
			$equipment->chasisnumber = $request->chasisnumber;
			$equipment->modelnumber = $request->modelnumber;
			$equipment->yearofmanufacture = $request->yearofmanufacture;
			$equipment->brand = $request->brand;
			$equipment->enginecapacity = $request->enginecapacity;
			$equipment->insurance = $request->insurance;
			$equipment->numberplate = $request->numberplate;
			$equipment->color = $request->color;
			//save dates
			$equipment->purchasedon = date('Y-m-d H:s:i', strtotime($request->purchasedon));
			$equipment->deliveredtohubon = date('Y-m-d H:s:i', strtotime($request->deliveredtohubon)); 
			$equipment->warrantyperiod = $request->warrantyperiod;
			$equipment->warrantyperiodunits = $request->warrantyperiodunits;
			$equipment->recommendedservicefrequency = $request->recommendedservicefrequency;
			$equipment->servicefrequencyunits = $request->servicefrequencyunits;
			$equipment->hasservicecontract = $request->hasservicecontract;
			
			$equipment->save();
			
			return redirect()->route('equipment.show', array('id' => $equipment->id));

		}catch (\Exception $e) {
			print_r('faild to save'.$e);
			exit;
			return redirect()->route('equipment.create')
            ->with('flash_message', 'failed');
		}
    //Display a successful message upon save
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
		$equipment = Equipment::findOrFail($id); 
		$breakdown_action_taken = 0;
		$reasons_for_breakdown = 0;
		if($equipment->breakdownid){
			$breakdown_action_taken = getActionTakenOnBike($equipment->breakdownid);
			$reasons_for_breakdown = getBikeBreakDownReason($equipment->breakdownid);
		}
        return view ('equipment.show', compact('equipment','reasons_for_breakdown','breakdown_action_taken'));
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
		$equipment = Equipment::findOrFail($id);
		$hubsdropdown = getAllHubs();
		
		$lt = new LookupType();
		$lt->name = 'YES_NO';
		$yesnooptions = $lt->getOptionValuesAndDescription();
		
		$lt->name = 'SERVICE_FREQ_UNITS';
		$servicefreqdropdown = $lt->getOptionValuesAndDescription();
		
		$lt->name = 'WARRANTY_UNITS';
		$warrantyunitsdropdown = $lt->getOptionValuesAndDescription();
		
       return View('equipment.edit', compact('equipment','hubsdropdown', 'yesnooptions', 'servicefreqdropdown', 'warrantyunitsdropdown'));        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
		//Validate all fields
         $equipment = Equipment::findOrFail($id);
		try {
			$equipment->facilityid = $request->facilityid;
			$equipment->hubid = $request->facilityid;
			$equipment->type = 1;
			$equipment->enginenumber = $request->enginenumber;
			$equipment->chasisnumber = $request->chasisnumber;
			$equipment->modelnumber = $request->modelnumber;
			$equipment->yearofmanufacture = $request->yearofmanufacture;
			$equipment->brand = $request->brand;
			$equipment->enginecapacity = $request->enginecapacity;
			$equipment->insurance = $request->insurance;
			$equipment->numberplate = $request->numberplate;
			$equipment->color = $request->color;
			//save dates
			$equipment->purchasedon = date('Y-m-d H:s:i', strtotime($request->purchasedon));
			$equipment->deliveredtohubon = date('Y-m-d H:s:i', strtotime($request->deliveredtohubon)); 
			$equipment->warrantyperiod = $request->warrantyperiod;
			$equipment->warrantyperiodunits = $request->warrantyperiodunits;
			$equipment->recommendedservicefrequency = $request->recommendedservicefrequency;
			$equipment->servicefrequencyunits = $request->servicefrequencyunits;
			$equipment->hasservicecontract = $request->hasservicecontract;
			
			$equipment->save();
			
			return redirect()->route('equipment.show', array('id' => $equipment->id));

		}catch (\Exception $e) {
			print_r('faild to save'.$e);
			exit;
			return redirect()->route('equipment.create')
            ->with('flash_message', 'failed');
		}
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
       $equipment = Equipment::findOrFail($id);
       $equipment->delete();

        return redirect()->route('equipment.list')
            ->with('flash_message',
             'Equipment successfully deleted');
    }
	
	public function breakdownform($hubid, $id = NULL){
		$equipment = new Equipment;
		$mechanics = array_merge_maintain_keys(array('' => 'Select One'),getMechanicforHub($hubid));
		if($id){
			$equipment = Equipment::findOrFail($id);
			
		}
		$lt = new LookupType();
		$lt->name = 'BIKE_DOWN_REASONS';
		$reasons_for_breakdown = $lt->getOptionValuesAndDescription();
		//actions taken
		$lt->name = 'BIKE_BREAK_DOWN_ACTIONS';
		$actions_taken_for_breakdown = $lt->getOptionValuesAndDescription();
		
		//print_r($reasons_for_breakdown);
		//exit;
		return view('equipment.maintenance.breakdownform', compact('reasons_for_breakdown','actions_taken_for_breakdown','mechanics','hubid','id'));
	}
	
	public function savebreakdown(Request $request){		
		try {
			
		   \DB::transaction(function() use($request){
			   $userid = \Auth()->user()->id;
			   $equipment_breakdown = new EquipmentBreakDown;
			   $equipment_breakdown->hubid = $request->hubid;
			   $equipment_breakdown->bikeid = $request->id;
			   $equipment_breakdown->mechanicid = $request->mechanicid;
			   $equipment_breakdown->status = 1;
			   $equipment_breakdown->reportedby = $userid;
			   $equipment_breakdown->createdby = $userid;
			   $equipment_breakdown->reportingdate = date('Y-m-d');
			   $equipment_breakdown->datebrokendown = date('Y-m-d', strtotime($request->reportingdate));
			   $equipment_breakdown->save();
			   	//update the bike status to 'broken down' and set the matching breakage id
				$equipment = Equipment::findOrFail($request->id);	
				$equipment->breakdownid = $equipment_breakdown->id;	
				$equipment->status = 2;	 
				$equipment->save();  
			   //now save the details of the break down
			  foreach($request->actionstaken as $action){
				   	$equipment_breakdown_action = new EquipmentBreakDownAction;
			   		$equipment_breakdown_action->action = $action;
			  		$equipment_breakdown_action->equipmentbreakdownid = $equipment_breakdown->id;
					$equipment_breakdown_action->createdby = $userid;
					$equipment_breakdown_action->save();
				}
			   
			   // now save reasons for breakdwon
			   foreach($request->reasonforbreakdown as $reason){
				   	$equipment_breakdown_reason = new EquipmentBreakDownReason;
			   		$equipment_breakdown_reason->reason = $reason;
			  		$equipment_breakdown_reason->equipmentbreakdownid = $equipment_breakdown->id;
					$equipment_breakdown_reason->createdby = $userid;
					$equipment_breakdown_reason->save();
				}
			  
		   });
		   return redirect()->route('equipment.show', array('id' => $request->id));
		}catch (\Exception $e) {
			return redirect()->url('equipment/down/hubid/'.$request->hubid.'/id/'.$request->id)
            ->with('flash_message', 'failed');
		}
	}
	
	public function updatebreakdownstatus(Request $request){		
		try {
			\DB::transaction(function() use($request){
				$equipment = Equipment::findOrFail($request->equipmentid);
				$equipment_breakdown = EquipmentBreakDown::findOrFail($request->breakdownid);
				$userid = \Auth()->user()->id;
				//update the breakdown status
				$equipment_breakdown->closingnotes = $request->closingnotes;
				$equipment_breakdown->status = 2;
				$equipment_breakdown->closedby = $userid;
				$equipment_breakdown->brokendownenddate = date('Y-m-d H:s:i');
				$equipment_breakdown->save();
				//update the equipment/bike status to 1, also remove the breakdownid
				$equipment->status = 1;
				$equipment->breakdownid = NULL;
				$equipment->save();
			});
			//exit;
		 	return redirect()->route('equipment.show', array('id' => $request->equipmentid));
		 }catch (\Exception $e) {
			// print_r($e);
			 //exit;
			return redirect()->url('equipment/down/hubid/'.$request->hubid.'/id/'.$request->id)
            ->with('flash_message', 'failed');
		}
	}	 
}