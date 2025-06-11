<?php
// app/Http/Controllers/PostController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;

use \App\Models\LookupType as LookupType;
use \App\Models\Contact as Contact;

class ContactController extends Controller {

    public function __construct() {
        //$this->middleware(['auth', 'clearance'])->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($category,$type) {
        $where_clause = '';
        $query = "SELECT f.name as hub, d.name as district, c.firstname, c.lastname, c.othernames, c.emailaddress, c.telephonenumber, c.phone2, c.phone3  
		FROM contact as c 
		LEFT JOIN facility as f ON (c.hubid = f.id) 
		LEFT JOIN district as d ON (f.districtid = d.id) 
		WHERE c.isactive = 1 AND c.category = ".$category." AND c.type = ".$type."
		ORDER BY f.name ASC";
		//echo $query;
		//exit;
		$contacts = \DB::select($query);
		return view('contact.list', compact('contacts', 'category','type'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function dlfps() {
        $where_clause = '';
        $query = "SELECT f.name as hub, d.name as district, c.firstname, c.lastname, c.othernames, c.emailaddress, c.telephonenumber, c.phone2, c.phone3  
		FROM facility as f contact as c 
		LEFT JOIN ON (c.hubid = f.id) 
		LEFT JOIN district as d ON (c.dlfpdistrictid = d.id) 
		WHERE c.isactive = 1 AND c.category = 2 AND c.type = 6
		ORDER BY f.name ASC";
		//echo $query;
		//exit;
		$contacts = \DB::select($query);
		return view('contact.dlfp_list', compact('contacts'));
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
	public function form($category, $type, $obj){
		$title = 'Create Contact';
		//$districts = array_merge_maintain_keys(array('' => 'Select one'),getAllDistricts());
		$organizations = array_merge_maintain_keys(array('' => 'Select one'),getAllHubs());
		$districts_for_hub = array_merge_maintain_keys(array('' => 'Select one'),getDistrictsForHub($obj));
		return view('contact.create', compact('category','type', 'obj', 'title','districts_for_hub'));
	}
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) { 
		$contact = new Contact;
		//depending on whethe the contact is for ip, hub, district, save the relevant table attribute
		if($request->category == 1){
			$contact->organizationid = $request->obj;
			$return_url = 'organization.show';
			//check that the organization/hub does not have an existing conact of the type in question
			$existing_contact = Contact::where('type', $request->type)->where('category', $request->category)->where('organizationid',$request->obj)->first();
		}elseif($request->category == 2){
			$contact->hubid = $request->obj;
			$return_url = 'hub.show';
			//check that the organization/hub does not have an existing conact of the type in question
			$existing_contact = Contact::where('type', $request->type)->where('category', $request->category)->where('hubid',$request->obj)->first();
		}else{
		}
		//deactivate the existing contact before creating the new one
		if(!empty($existing_contact)){
			$existing_contact->isactive = 0;
			$existing_contact->save();
		}
		try {
			
			$contact->type = $request->type;
			$contact->firstname = $request->firstname;
			$contact->lastname = $request->lastname;
			$contact->othernames = $request->othernames;
			$contact->emailaddress = $request->emailaddress;
			$contact->telephonenumber = $request->telephonenumber;
			$contact->dlfpdistrictid = $request->dlfpdistrictid;
			$contact->phone2 = $request->phone2;
			$contact->phone3 = $request->phone3;
			$contact->phone4 = $request->phone4;
			$contact->category = $request->category;
			$contact->isactive = 1;
			$contact->createdby = Auth::getUser()->id;
			
			$contact->save();
			//in the return url, use the obj id for which you are saving the contact
			return redirect()->route($return_url, array('id' => $request->obj.'#tab_3'));

		}catch (\Exception $e) {
			
			print_r('faild to save'.$e);
			return redirect()->url('contact/new/category/'.$request->category.'/type/'.$request->type)
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
		$staff = Staff::findOrFail($id); //Find post of id = $id
        return view ('staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
		$contact = contact::findOrFail($id); 
		$title = 'Update Contact';
		$organizations = array_merge_maintain_keys(array('' => 'Select one'),getAllHubs());
		$districts_for_hub = array_merge_maintain_keys(array('' => 'Select one'),getDistrictsForHub($contact->hubid));
		return view('contact.edit', compact('title','contact','districts_for_hub'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
		$contact = Contact::findOrFail($id);
		//check if there
		try {
			$contact->firstname = $request->firstname;
			$contact->lastname = $request->lastname;
			$contact->othernames = $request->othernames;
			$contact->emailaddress = $request->emailaddress;
			$contact->telephonenumber = $request->telephonenumber;
			$contact->dlfpdistrictid = $request->dlfpdistrictid;
			$contact->phone2 = $request->phone2;
			$contact->phone3 = $request->phone3;
			$contact->phone4 = $request->phone4;
			$contact->lastupdatedby = Auth::getUser()->id;
			//depending on whethe the contact is for ip, hub, district, save the relevant table attribute
			if($contact->category == 1){
				$objid = $contact->organizationid;
				$return_url = 'organization.show';
			}elseif($contact->category == 2){
				$return_url = 'hub.show';
				$objid = $contact->hubid;
			}else{
				//do nothing
			}
			$contact->save();
			//in the return url, use the obj id for which you are saving the contact
			return redirect()->route($return_url, array('id' => $objid.'#tab_3'));

		}catch (\Exception $e) {
			//print_r('faild to save'.$e);
			//exit;
			return redirect()->route('contact.edit', array('id' => $contact->id))
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