<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use \App\Models\Organization as Organization;
use \App\Models\SupportAgencyPeriod as SupportAgencyPeriod;
class OrganizationController extends Controller {

    public function __construct() {
       // $this->middleware(['auth', 'clearance']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {
		//$organizations = Organization::orderby('id', 'desc')->paginate(10); //show only 10 items at a time in descending order
		//print_R($equipment);
		//exit;
		$query = "SELECT * FROM organization
		ORDER BY name ASC";
		$organizations = \DB::select($query);
        return view('organization.list', compact('organizations'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {		
		$healthregion = getAllHealthRgions();
		$supportagencies = array_merge_maintain_keys(array('' => 'Select One'), getAllSupportAgencies());
		$facilities = array_merge_maintain_keys(array('' => 'Select one'),getAllHubCandidateFacilities());
       return View('organization.create', compact('healthregion','supportagencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) { 
		//Use DB::transaction to avoid one model saving and the other failing unknowingly - orphan records not good
		//prepare the models to be save
		//START organization model data
		//dd(Auth::user());
		$user_id = Auth::getUser()->id;
        $organization = new Organization;
		$organization->name = $request->name;
		$organization->address = $request->address;
		$organization->emailaddress = $request->emailaddress;
		$organization->type = 1;
		$organization->telephonenumber = $request->telephonenumber;
		$organization->healthregionid = $request->healthregionid;
		$organization->supportagencyid = $request->supportagencyid;
		$organization->createdby = $user_id;
		//END organization model data
		
		//START support period model data, except the one to be picked from the organization model
		$supportagencyperiod = new SupportAgencyPeriod;
		$supportagencyperiod->supportagencyid = $request->supportagencyid;
		$supportagencyperiod->startdate = date('Y-m-d', strtotime($request->startdate));
		$supportagencyperiod->enddate = date('Y-m-d', strtotime($request->enddate));
		$supportagencyperiod->createdby = $user_id;
		//END support period model data
		try {
		   \DB::transaction(function() use ($organization, $supportagencyperiod){
			   $organization->save();
			   $supportagencyperiod->organizationid = $organization->id;
			   $supportagencyperiod->save();
			});
			return redirect()->route('organization.show', array('id' => $organization->id));
		}catch (\Exception $e) {
			//print_r('faild to save'.$e);
			return redirect()->route('organization.view')
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
		$organization = Organization::findOrFail($id); //Find post of id = $id
		//get the relevant contacts based on type and category
		$careandtreat = getContact($id, 1,1);
		$labtech = getContact($id, 1,2);
		$pmtc = getContact($id, 1,3);
		
		$supportperiod = getSupportPeriodDates($organization->id);
		$facilities = getFacilitiesForIP($organization->id);
		//$hubssupported = $organization
        return view ('organization.view', compact('facilities','organization','supportperiod', 'careandtreat', 'labtech', 'pmtc'));
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
		$organization = Organization::findOrFail($id);
		$healthregion = getAllHealthRgions();
		return view('organization.edit', compact('organization','healthregion'));
        
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
         $organization = Organization::findOrFail($id);;
		try {
			$organization->name = $request->name;
			$organization->address = $request->address;
			$organization->emailaddress = $request->emailaddress;
			$organization->telephonenumber = $request->telephonenumber;
			$organization->healthregionid = $request->healthregionid;
			$organization->save();
			return redirect()->route('organization.show', array('id' => $organization->id));

		}catch (\Exception $e) {
			print_r('faild to save'.$e);
			exit;
			return redirect()->route('organization.view')
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
       $hub = Organization::findOrFail($id);
        $hub->delete();

        return redirect()->route('organization.list')
            ->with('flash_message',
             'Orgainzation successfully deleted');
    }
}