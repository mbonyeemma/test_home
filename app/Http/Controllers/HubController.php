<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use \Entrust;
use \App\Models\Hub as Hub;
use \App\Models\Facility as Facility;
use \App\Models\Contact as Contact;
class HubController extends Controller {

    public function __construct() {
        //$this->middleware(['auth', 'clearance'])->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {
		$can_delete_facility = Entrust::can('delete-facility');
		$can_update_facility = Entrust::can('Update_facility');
		$query = "SELECT f.id, f.name, i.name as 'ip', f.hubname, f.address, hr.name as healthregion, d.name as `district` FROM facility f
	INNER JOIN healthregion hr ON(f.healthregionid = hr.id)
	LEFT JOIN organization i ON(i.id = f.ipid)
    INNER JOIN district d ON (f.districtid = d.id)
		WHERE f.id = f.parentid
		ORDER BY f.name ASC";
		$hubs = \DB::select($query);
        return view('hub.list', compact('hubs', 'can_delete_facility','can_update_facility'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
      	//$healthregion = getAllHealthRgions();
		$ips = array_merge_maintain_keys(array('' => 'Select One'), getAllIps());
		$facilities = array_merge_maintain_keys(array('' => 'Select One'), getAllHubCandidateFacilities());
		$healthregions = array_merge_maintain_keys(array('' => 'Select One'),getAllHealthRgions());
      	return View('hub.create', compact('ips','facilities', 'healthregions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) { 

    	//Validate all fields
		try {
			$facility = Facility::findOrFail($request->parentid);
			$facility->hubname = $request->name;
			$facility->address = $request->address;
			$facility->type = 2;
			$facility->ipid = $request->ipid;
			$facility->parentid = NULL;
			$facility->healthregionid = $request->healthregionid;
			$facility->save();
			return redirect()->route('hub.show', array('id' => $facility->id));

		}catch (\Exception $e) {
			print_r($e->getMessage());
			exit;
			return redirect()->route('hub.create')
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
		$hub = Facility::findOrFail($id); //Find post of id = $id
		$can_delete_facility = Entrust::can('delete-facility');
		$can_update_facility = Entrust::can('Update_facility');
		//get hub contacts
		$incharge = getContact($id, 2,1, 'hubid');
		$hubcordinator = getContact($id, 2,2, 'hubid');
		$labmanager = getContact($id, 2,3, 'hubid');
		$vlfocalperson = getContact($id, 2,4, 'hubid');
		$eidfocalperson = getContact($id, 2,5, 'hubid');
		// $dlfp = getContact($id, 2,6, 'hubid');
		$dlfp = Contact::where('hubid', $id)
							->where('category', 2)
							->where('isactive',1)
							->where('type', 6)->first();
		
		//$facilities = array_merge_maintain_keys(array('' => 'Select One'), getAllHubCandidateFacilities());
		$districts_for_hub = getDistrictsForHub($id);
				//get the facilities served by the hub
		$query = "SELECT f.id, f.name, f.incharge, f.inchargephonenumber, f.labmanager, f.labmanagerphonenumber, f.hubname as hub, fl.level as `facilitylevel`, d.name as district 
		FROM facility as f 
		INNER JOIN facilitylevel AS fl ON (f.facilitylevelid = fl.id) 
		INNER JOIN district as d ON(f.districtid = d.id)
		WHERE f.parentid = '".$hub->id."'
		ORDER BY f.name ASC";
		$facilities = \DB::select($query);

		$mondayschedule = getHubScheduleforaDay(1, $id);
		$tuesdayschedule = getHubScheduleforaDay(2, $id);
		$wednesdayschedule = getHubScheduleforaDay(3, $id);
		$thursdayschedule = getHubScheduleforaDay(4, $id);
		$fridayschedule = getHubScheduleforaDay(5, $id);
		$saturdayschedule = getHubScheduleforaDay(6, $id);
		$sundayschedule = getHubScheduleforaDay(7, $id);
		
		return view ('hub.show', compact('hub','incharge','hubcordinator','labmanager','vlfocalperson','eidfocalperson','facilities','mondayschedule','tuesdayschedule','wednesdayschedule','thursdayschedule','fridayschedule','saturdayschedule','sundayschedule','can_delete_facility','can_update_facility','dlfp','districts_for_hub'));
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
         $hub = Facility::findOrFail($id);
		 $ips = getAllIps();
		$healthregion = getAllHealthRgions();
		$supportagencies = array_merge_maintain_keys(array('' => 'Select One'), getAllSupportAgencies());
        return view('hub.edit', compact('hub','healthregion','ips'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
         $this->validate($request, [
            'name'=>'required|max:100',
        ]);

        $hub = Facility::findOrFail($id);
        $hub->parentid = $request->parentid;
		$hub->address = $request->address;
		$hub->hubname = $request->name;
		$hub->address = $request->address;
		$hub->ipid = $request->ipid;
		$hub->parentid = $request->parentid;
        $hub->save();

        return redirect()->route('hub.show', 
            $hub->id)->with('flash_message', 
            'Article, '. $hub->name.' updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
       $hub = Hub::findOrFail($id);
        $hub->delete();

        return redirect()->route('hub.list')
            ->with('flash_message',
             'Hub successfully deleted');
    }
	
	public function assignfacility(){
		$facilitydropdown = getAllFacilities();
		$hubdropdown = \App\Models\Facility::where('parentid', '=', NULL)->pluck('name', 'id');
		return view('hub.assignfacility', compact('facilitydropdown', 'hubdropdown'));
	}
	
	
	public function massassignfacilities(Request $request) { 
		try {
		   \DB::transaction(function() use($request){
			   $facilities_to_assign = $request->facilities;
			   if(count($facilities_to_assign)){
					for($i = 0; $i < count($facilities_to_assign); $i++){
						$facility = \App\Models\Facility::findOrFail($facilities_to_assign[$i]);
						$facility->parentid = $request->hubid;
						$facility->save();
					}
				}
				
			});
			//echo 'bifu';
			//exit;
			//return redirect()->route('hub.assignfacility');
			return redirect()->route('hub.show', array('id' => $request->hubid.'#tab_4'));				
			
		}catch (\Exception $e) {
			print_r($e->getMessage());
			exit;
			//return redirect()->route('routingschedule.create')
            //->with('flash_message', 'failed');
		}
    }
}