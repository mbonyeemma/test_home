<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;

use \App\Models\LookupType as LookupType;
use \App\Models\RoutingSchedule as RoutingSchedule;
use \App\Models\Facility as Facility;

class RoutingScheduleController extends Controller {

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
	public function create(){
		$hubid = Auth::getUser()->hubid; 
		$facilitydropdown = getFacilitiesforHub($hubid);
		//$pagetype = $type;
		return view('routingschedule.create', compact('facilitydropdown','hubid'));
	}
	public function createform($hubid){
		$facilitydropdown = getFacilitiesforHub($hubid);
		return view('routingschedule.create', compact('facilitydropdown', 'hubid'));
	}
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) { 
	//print_r($request->toArray());
	//exit;
		//	
		try {
		   \DB::transaction(function() use($request){
			   //save monday schedule
			   $monday_data = $request->monday;
			   if(count($monday_data)){
					for($i = 0; $i < count($request->monday); $i++){
						$routingschedule = new RoutingSchedule;
						$routingschedule->hubid = $request->hubid;
						$routingschedule->facilityid = $monday_data[$i];
						$routingschedule->dayoftheweek = 1;
						$routingschedule->createdby = Auth::getUser()->id;
						$routingschedule->save();
					}
				}
				$tuesday_data = $request->tuesday;
			   if(count($tuesday_data)){
					for($i = 0; $i < count($tuesday_data); $i++){
						$routingschedule = new RoutingSchedule;
						$routingschedule->hubid = $request->hubid;
						$routingschedule->facilityid = $tuesday_data[$i];
						$routingschedule->dayoftheweek = 2;
						$routingschedule->createdby = Auth::getUser()->id;
						$routingschedule->save();
					}
				}
				$wednesday_data = $request->wednesday;
			   if(count($wednesday_data)){
					for($i = 0; $i < count($wednesday_data); $i++){
						$routingschedule = new RoutingSchedule;
						$routingschedule->hubid = $request->hubid;
						$routingschedule->facilityid = $wednesday_data[$i];
						$routingschedule->dayoftheweek = 3;
						$routingschedule->createdby = Auth::getUser()->id;
						$routingschedule->save();
					}
				}
				$thursday_data = $request->thursday;
			   if(count($thursday_data)){
					for($i = 0; $i < count($request->thursday); $i++){
						$routingschedule = new RoutingSchedule;
						$routingschedule->hubid = $request->hubid;
						$routingschedule->facilityid = $thursday_data[$i];
						$routingschedule->dayoftheweek = 4;
						$routingschedule->createdby = Auth::getUser()->id;
						$routingschedule->save();
					}
				}
				$friday_data = $request->friday;
			   if(count($friday_data)){
					for($i = 0; $i < count($friday_data); $i++){
						$routingschedule = new RoutingSchedule;
						$routingschedule->hubid = $request->hubid;
						$routingschedule->facilityid = $friday_data[$i];
						$routingschedule->dayoftheweek = 5;
						$routingschedule->createdby = Auth::getUser()->id;
						$routingschedule->save();
					}
				}
				$saturday_data = $request->saturday;
			   if(count($saturday_data)){
					for($i = 0; $i < count($saturday_data); $i++){
						$routingschedule = new RoutingSchedule;
						$routingschedule->hubid = $request->hubid;
						$routingschedule->facilityid = $saturday_data[$i];
						$routingschedule->dayoftheweek = 6;
						$routingschedule->createdby = Auth::getUser()->id;
						$routingschedule->save();
					}
				}
				$sunday_data = $request->sunday;
			   if(count($sunday_data)){
					for($i = 0; $i < count($sunday_data); $i++){
						$routingschedule = new RoutingSchedule;
						$routingschedule->hubid = $request->hubid;
						$routingschedule->facilityid = $sunday_data[$i];
						$routingschedule->dayoftheweek = 7;
						$routingschedule->createdby = Auth::getUser()->id;
						$routingschedule->save();
					}
				}
			});
			//echo 'bifu';
			//exit;
			//send the hubid instead so that you can pick schedule for the hub
			if(Auth::getUser()->hasRole(['In_charge'])){
				return redirect()->route('routingschedule.show', array('id' => Auth::getUser()->hubid.'#tab_4'));				
			}else{
				return redirect()->route('hub.show', array('id' => $request->hubid.'#tab_4'));				
			}
		}catch (\Exception $e) {
			print_r('faild to save'.$e);
			exit;
			return redirect()->route('routingschedule.create')
            ->with('flash_message', 'failed');
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
		$mondayschedule = getHubScheduleforaDay(1, $id);
		$tuesdayschedule = getHubScheduleforaDay(2, $id);
		$wednesdayschedule = getHubScheduleforaDay(3, $id);
		$thursdayschedule = getHubScheduleforaDay(4, $id);
		$fridayschedule = getHubScheduleforaDay(5, $id);
		$saturdayschedule = getHubScheduleforaDay(6, $id);
		$sundayschedule = getHubScheduleforaDay(7, $id);
		//exit;
        return view ('routingschedule.view', compact('mondayschedule','tuesdayschedule','wednesdayschedule','thursdayschedule','fridayschedule','saturdayschedule','sundayschedule', 'id'));
    }

    /**tuesdayschedule
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
		$hub = Facility::findOrFail($id);
		$facilitydropdown = getFacilitiesforHub($id);
		$facilities = Facility::where('parentid', $id)->get();
		$mondayfacilityids = getHubScheduleFacilitiesforaDay(1, $id);
		//print_r($mondayfacilityids); exit;
		/*foreach($facilities as $facility){
			echo $facility->id.'<br>';
			if(in_array($facility->id, $mondayfacilityids)){
				echo 'true<br>';
			}
		}*/
		//exit;
		$tuesdayfacilityids = getHubScheduleFacilitiesforaDay(2, $id);
		$wednesdayfacilityids = getHubScheduleFacilitiesforaDay(3, $id);
		$thursdayfacilityids = getHubScheduleFacilitiesforaDay(4, $id);
		$fridayfacilityids = getHubScheduleFacilitiesforaDay(5, $id);
		$saturdayfacilityids = getHubScheduleFacilitiesforaDay(6, $id);
		$sundayfacilityids = getHubScheduleFacilitiesforaDay(7, $id);
		//exit;
		return view('routingschedule.edit', compact('facilitydropdown','mondayfacilityids','tuesdayfacilityids','wednesdayfacilityids','thursdayfacilityids','fridayfacilityids','saturdayfacilityids','sundayfacilityids', 'facilities', 'hub'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {		
		try {
		   \DB::transaction(function() use($request, $id){
			  //delete the previously existing data
			\DB::table('routingschedule')->where('hubid', $id)->delete();
			//now save the updated/new schedule 
			   //save monday schedule
			   $monday_data = $request->monday;
			   if(count($monday_data)){
					for($i = 0; $i < count($request->monday); $i++){
						$routingschedule = new RoutingSchedule;
						$routingschedule->hubid = Auth::getUser()->hubid;
						$routingschedule->facilityid = $monday_data[$i];
						$routingschedule->dayoftheweek = 1;
						$routingschedule->createdby = Auth::getUser()->id;
						$routingschedule->save();
					}
				}
				$tuesday_data = $request->tuesday;
			   if(count($tuesday_data)){
					for($i = 0; $i < count($tuesday_data); $i++){
						$routingschedule = new RoutingSchedule;
						$routingschedule->hubid = Auth::getUser()->hubid;
						$routingschedule->facilityid = $tuesday_data[$i];
						$routingschedule->dayoftheweek = 2;
						$routingschedule->createdby = Auth::getUser()->id;
						$routingschedule->save();
					}
				}
				$wednesday_data = $request->wednesday;
			   if(count($wednesday_data)){
					for($i = 0; $i < count($wednesday_data); $i++){
						$routingschedule = new RoutingSchedule;
						$routingschedule->hubid = Auth::getUser()->hubid;
						$routingschedule->facilityid = $wednesday_data[$i];
						$routingschedule->dayoftheweek = 3;
						$routingschedule->createdby = Auth::getUser()->id;
						$routingschedule->save();
					}
				}
				$thursday_data = $request->thursday;
			   if(count($thursday_data)){
					for($i = 0; $i < count($request->thursday); $i++){
						$routingschedule = new RoutingSchedule;
						$routingschedule->hubid = Auth::getUser()->hubid;
						$routingschedule->facilityid = $thursday_data[$i];
						$routingschedule->dayoftheweek = 4;
						$routingschedule->createdby = Auth::getUser()->id;
						$routingschedule->save();
					}
				}
				$friday_data = $request->friday;
			   if(count($friday_data)){
					for($i = 0; $i < count($friday_data); $i++){
						$routingschedule = new RoutingSchedule;
						$routingschedule->hubid = Auth::getUser()->hubid;
						$routingschedule->facilityid = $friday_data[$i];
						$routingschedule->dayoftheweek = 5;
						$routingschedule->createdby = Auth::getUser()->id;
						$routingschedule->save();
					}
				}
				$saturday_data = $request->saturday;
			   if(count($saturday_data)){
					for($i = 0; $i < count($saturday_data); $i++){
						$routingschedule = new RoutingSchedule;
						$routingschedule->hubid = Auth::getUser()->hubid;
						$routingschedule->facilityid = $saturday_data[$i];
						$routingschedule->dayoftheweek = 6;
						$routingschedule->createdby = Auth::getUser()->id;
						$routingschedule->save();
					}
				}
				$sunday_data = $request->sunday;
			   if(count($sunday_data)){
					for($i = 0; $i < count($sunday_data); $i++){
						$routingschedule = new RoutingSchedule;
						$routingschedule->hubid = Auth::getUser()->hubid;
						$routingschedule->facilityid = $sunday_data[$i];
						$routingschedule->dayoftheweek = 7;
						$routingschedule->createdby = Auth::getUser()->id;
						$routingschedule->save();
					}
				}
			});
			//echo 'bifu';
			//exit;
			//send the hubid instead so that you can pick schedule for the hub
			if(Auth::getUser()->hasRole(['In_charge'])){
				return redirect()->route('routingschedule.show', array('id' => Auth::getUser()->hubid.'#tab_4'));				
			}else{
				return redirect()->route('hub.show', array('id' => $request->hubid.'#tab_4'));				
			}
		}catch (\Exception $e) {
			print_r('faild to save'.$e);
			exit;
			return redirect()->route('routingschedule.create')
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
       
    }
}