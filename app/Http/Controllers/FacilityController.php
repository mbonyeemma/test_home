<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Post;
use Auth;
use \Entrust;
use Session;
use \App\Models\Facility as Facility;
class FacilityController extends Controller {

    public function __construct() {
       // $this->middleware(['auth', 'clearance']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {
		//$facilities = \DB::table('facility')->get();
		//$facilities = Facility::where('id', '!=', '')->get();
		$where_clause = '';
		
		if(Auth::user()->hasRole('hub_coordinator')){
			//$staff = Staff::where('hubid',Auth::user()->hubid)->Orderby('id', 'desc')->where('type', $pagetype)->paginate(10);
			$where_clause = " AND f.parentid = '".Auth::user()->hubid."'";
		}else{
        	//$staff = Staff::Orderby('id', 'desc')->where('type', $pagetype)->paginate(10);
		}
		$can_delete_facility = Entrust::can('delete-facility');
		$can_update_facility = Entrust::can('Update_facility');

		$query = "SELECT f.id, f.name,  h.hubname as hub, fl.level as `facilitylevel`, d.name as district 
		FROM facility as f 
		INNER JOIN facility as h ON (f.parentid = h.id) 
		INNER JOIN facilitylevel AS fl ON (f.facilitylevelid = fl.id) 
		INNER JOIN district as d ON(f.districtid = d.id)
		WHERE f.id<> ''".$where_clause."
		ORDER BY f.name ASC";
		$facilities = \DB::select($query);

		//exit;
        return view('facility.list', compact('facilities','can_delete_facility','can_update_facility'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		$hubsdropdown = getAllHubs();
		$facilityleveldropdown = array_merge_maintain_keys(array('' => 'Select One'),getAllFacilityLevels());
		$districtdropdown = array_merge_maintain_keys(array('' => 'Select One'),getAllDistricts());
		
       return View('facility.create', compact('hubsdropdown', 'facilityleveldropdown', 'districtdropdown'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) { 
		
		//Validate all fields
         $facility = new Facility;
		try {
			$facility->facilitylevelid = $request->facilitylevelid;
			$facility->parentid = $request->hubid;
			$facility->name = $request->name;
			$facility->hubid = $request->hubid;
			$facility->districtid = $request->districtid;
			$facility->inchargephonenumber = $request->inchargephonenumber;
			$facility->incharge = $request->incharge;
			$facility->labmanagerphonenumber = $request->labmanagerphonenumber;
			$facility->labmanager = $request->labmanager;
			$facility->address = $request->address;
			$facility->email = $request->email;
			$facility->save();
			return redirect()->route('facility.show', array('id' => $facility->id));

		}catch (\Exception $e) {
			print_r('faild to save'.$e);
			exit;
			return redirect()->route('facility.create')
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
		$facility = Facility::findOrFail($id); //Find post of id = $id
        return view ('facility.view', compact('facility'));
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $hubsdropdown = getAllHubs();
		$facilityleveldropdown = array_merge_maintain_keys(array('' => 'Select One'),getAllFacilityLevels());
		$districtdropdown = array_merge_maintain_keys(array('' => 'Select One'),getAllDistricts());
        $facility = Facility::findOrFail($id);
       return View('facility.edit', compact('facility','hubsdropdown','facilityleveldropdown','districtdropdown'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
		$facility = Facility::findOrFail($id);;
		try {
			$facility->facilitylevelid = $request->facilitylevelid;
			$facility->parentid = $request->parentid;
			$facility->ipid = $request->ipid;
			$facility->name = $request->name;
			$facility->districtid = $request->districtid;
			$facility->inchargephonenumber = $request->inchargephonenumber;
			$facility->incharge = $request->incharge;
			$facility->labmanagerphonenumber = $request->labmanagerphonenumber;
			$facility->labmanager = $request->labmanager;
			$facility->save();
			return redirect()->route('facility.show', array('id' => $facility->id));

		}catch (\Exception $e) {
			print_r('faild to save'.$e);
			exit;
			return redirect()->route('facility.create')
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
		$facility = Facility::findOrFail($id);
        $facility->delete();
        return redirect()->route('facility.list')
            ->with('flash_message',
             'Orgainzation successfully deleted');       
    }
	
	public function printQr($id){
		$facility = Facility::findOrFail($id);
		return view ('facility.print', compact('facility'));
	}
}