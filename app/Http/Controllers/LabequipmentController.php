<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use \App\Models\LookupType as LookupType;
use \App\Models\FacilityLabEquipment as FacilityLabEquipment;
use \App\Models\FacilityLabEquipmentBreakDown as EquipmentBreakDown;
use \App\Models\FacilityLabEquipmentBreakDownAction as EquipmentBreakDownAction;
use \App\Models\FacilityLabEquipmentBreakDownReason as EquipmentBreakDownReason;
class LabequipmentController extends Controller {

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
        $title = 'View Equipment List';
        $status = '';
        if(Auth::user()->hasRole('In_charge')){
            $where_condition = " AND e.hubid ='".Auth::user()->hubid."'";
        }
        $query = "SELECT e.id, lv.lookupvaluedescription as name, e.model, e.serial_number, e.status, e.location, f.hubname,e.hubid, e.installation_date FROM facilitylabequipment e
        INNER JOIN facility f ON(e.hubid = f.id)
		INNER JOIN lookuptypevalue lv ON (lv.lookuptypevalue = e.labequipment_id AND lv.lookuptypeid = 27)
        WHERE e.id != '' ".$where_condition."
        ORDER BY lv.lookupvaluedescription  ASC";
		//echo $query; exit;
        $equipment = \DB::select($query);
        return view('facilitylabequipment.list', compact('equipment', 'status','title'));
    }
    public function elist($status){
		$where_condition = '';
		if(Auth::user()->hasRole('In_charge')){
            $where_condition .= " AND e.hubid ='".Auth::user()->hubid."'";
        }
		$where_condition .= " AND e.status = '".$status."'";
		if($status == 2){
			$title = 'Equipment Broken Down';
		}elseif($status == 0){
			$title = 'Equipment withous Service Contract';
		}		
		$query = "SELECT e.id, lv.lookupvaluedescription as name, e.model, e.serial_number, e.status, e.location, f.hubname, e.installation_date FROM facilitylabequipment e
        INNER JOIN facility f ON(e.hubid = f.id)
		INNER JOIN lookuptypevalue lv ON (lv.lookuptypevalue = e.labequipment_id AND lv.lookuptypeid = 27)
        WHERE e.id != '' ".$where_condition."
        ORDER BY lv.lookupvaluedescription  ASC";
		//echo $query; exit;
        $equipment = \DB::select($query);
        return view('facilitylabequipment.list', compact('equipment', 'status','title'));
		
	}
    public function servicecont($service){
        if(Auth::user()->hasRole('In_charge')){
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
        $hubsdropdown = getAllHubs();
        
        $lt = new LookupType();
        $lt->name = 'YES_NO';
        $yesnooptions = $lt->getOptionValuesAndDescription(); 
        
        $lt->name = 'LAB_SECTIONS';
        $labsectiondropdown = array_merge_maintain_keys(array('' => 'Select'),$lt->getOptionValuesAndDescription());

        $lt->name = 'PROCUREMENT_TYPE';
        $procurementtypedropdown = array_merge_maintain_keys(array('' => 'Select'),$lt->getOptionValuesAndDescription());

        $lt->name = 'WARRANTY_PERIOD';
        $warrantyperioddropdown = array_merge_maintain_keys(array('' => 'Select'),$lt->getOptionValuesAndDescription());
		
		$lt->name = 'LAB_EQUIPMENT';
        $labequipmentdropdown = array_merge_maintain_keys(array('' => 'Select'),$lt->getOptionValuesAndDescription());
	
		$lt->name = 'SUPPLIERS';
        $supplierdropdown = array_merge_maintain_keys(array('' => 'Select'),$lt->getOptionValuesAndDescription());
		
        $lt->name = 'SERVICE_FREQUENCY';
        $servicefrequencydropdown = array_merge_maintain_keys(array('' => 'Select'),$lt->getOptionValuesAndDescription());
       return View('facilitylabequipment.create', compact('hubsdropdown', 'yesnooptions', 'labsectiondropdown','procurementtypedropdown','warrantyperioddropdown','servicefrequencydropdown','labequipmentdropdown', 'supplierdropdown'));
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
         $labequipment = new \App\Models\FacilityLabEquipment;
        try {
            $labequipment->hubid = \Auth()->user()->hubid;
            $labequipment->createdby = \Auth()->user()->id;
			$labequipment->status = 1;
            $labequipment->labequipment_id = $request->labequipment_id;
            $labequipment->model = $request->model;
            $labequipment->serial_number = $request->serialnumber;
            $labequipment->location = $request->labsection;
            $labequipment->procurement_type = $request->procurementtype;
            $labequipment->purchase_date = date('Y-m-d H:s:i', strtotime($request->purchasedon));
            $labequipment->delivery_date = date('Y-m-d H:s:i', strtotime($request->delivereddate));  
            $labequipment->verification_date = date('Y-m-d H:s:i', strtotime($request->Verificationdate));
            $labequipment->installation_date = date('Y-m-d H:s:i', strtotime($request->Installationdate));
            $labequipment->spare_parts = $request->hasspearparts;
            $labequipment->warranty = $request->warrantperiod;
            $labequipment->life_span = $request->Lifetime;
            $labequipment->service_frequency = $request->servicefrequency;
            $labequipment->service_contract = $request->hasservicecontract ;            
            $labequipment->save();            
            return redirect()->route('labequipment.show', array('id' => $labequipment->id));

        }catch (\Exception $e) {
            print_r('faild to save'.$e);
            exit;
            return redirect()->route('labequipment.create')
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
        $labequipment = FacilityLabEquipment::findOrFail($id); 
        
        return view ('facilitylabequipment.view', compact('labequipment'));
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $labequipment = Labequipment::findOrFail($id);
        $hubsdropdown = getAllHubs();
        
        $lt = new LookupType();
        $lt->name = 'YES_NO';
        $yesnooptions = $lt->getOptionValuesAndDescription(); 
        
        $lt->name = 'LAB_SECTIONS';
        $labsectiondropdown = $lt->getOptionValuesAndDescription();

        $lt->name = 'YES_NO';
        $yesnooptions = $lt->getOptionValuesAndDescription();
        
        $lt->name = 'PROCUREMENT_TYPE';
        $procurementtypedropdown = $lt->getOptionValuesAndDescription();


        $lt->name = 'SERVICE_FREQUENCY';
        $servicefreqdropdown = $lt->getOptionValuesAndDescription();
        
        $lt->name = 'WARRANTY_PERIOD';
        $warrantyperioddropdown = $lt->getOptionValuesAndDescription();

        
       return View('facilitylabequipment.edit', compact('labequipment','hubsdropdown', 'labsectiondropdown','yesnooptions', 'servicefreqdropdown', 'procurementtypedropdown','warrantyperioddropdown'));        
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
         $labequipment = FacilityLabEquipment::findOrFail($id);
        try {

            $labequipment->facility_id = \Auth()->user()->hubid;
            $labequipment->createdby = \Auth()->user()->id;
            
            $labequipment->name = $request->name;
            $labequipment->model = $request->model;
            $labequipment->serial_number = $request->serialnumber;
            $labequipment->location = $request->labsection;
            $labequipment->procurement_type = $request->procurementtype;
            $labequipment->purchase_date = date('Y-m-d H:s:i', strtotime($request->purchasedon));
            $labequipment->delivery_date = date('Y-m-d H:s:i', strtotime($request->delivereddate));  
            $labequipment->verification_date = date('Y-m-d H:s:i', strtotime($request->Verificationdate));
            $labequipment->installation_date = date('Y-m-d H:s:i', strtotime($request->Installationdate));
            $labequipment->spare_parts = $request->hasspearparts;
            $labequipment->warranty = $request->warrantperiod;
            $labequipment->life_span = $request->Lifetime;
            $labequipment->service_frequency = $request->servicefrequency;
            $labequipment->service_contract = $request->hasservicecontract ;
            $labequipment->save();
            
            return redirect()->route('facilitylabequipment.show', array('id' => $labequipment->id));

        }catch (\Exception $e) {
            print_r('faild to save'.$e);
            exit;
            return redirect()->route('labequipments.create')
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
       $equipment = FacilityLabEquipment::findOrFail($id);
       $equipment->delete();

        return redirect()->route('equipment.list')
            ->with('flash_message',
             'Equipment successfully deleted');
    }
    
    public function breakdownform($hubid, $id = NULL){
        $equipment = new FacilityLabEquipment;
        $mechanics = array_merge_maintain_keys(array('' => 'Select One'),getMechanicforHub($hubid));
        if($id){
            $equipment = FacilityLabEquipment::findOrFail($id);
            
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
               $equipment_breakdown->labequipmentid = $request->id;
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
           return redirect()->route('facilitylabequipment.show', array('id' => $request->id));
        }catch (\Exception $e) {
            return redirect()->url('facilitylabequipment/down/hubid/'.$request->hubid.'/id/'.$request->id)
            ->with('flash_message', 'failed');
        }
    }
    
    public function updatebreakdownstatus(Request $request){
        
        try {
            \DB::transaction(function() use($request){
                $equipment = FacilityLabEquipment::findOrFail($request->equipmentid);
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
            return redirect()->route('facilitylabequipment.show', array('id' => $request->equipmentid));
         }catch (\Exception $e) {
            // print_r($e);
             //exit;
            return redirect()->url('facilitylabequipment/down/hubid/'.$request->hubid.'/id/'.$request->id)
            ->with('flash_message', 'failed');
        }
    }    
}