<?php
// app/Http/Controllers/PostController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;

use \App\Models\LookupType as LookupType;
use \App\Models\Staff as Staff;
use \App\Models\User as User;
use \App\Models\Equipment as Equipment;

class StaffController extends Controller
{

	public function __construct()
	{
		//$this->middleware(['auth', 'clearance'])->except('index', 'show');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function index(Request $request, $pagetype)
	{
		$where_clause = '';

		if (Auth::user()->hasRole('hub_coordinator')) {
			//$staff = Staff::where('hubid',Auth::user()->hubid)->Orderby('id', 'desc')->where('type', $pagetype)->paginate(10);
			$where_clause = "AND s.hubid = '" . Auth::user()->hubid . "'";
		} else {
			//$staff = Staff::Orderby('id', 'desc')->where('type', $pagetype)->paginate(10);
		}
		$query = "SELECT s.id, s.firstname,s.user_id, s.lastname, s.designation, s.hasdrivingpermit, s.hasbbtraining, s.isimmunizedforhb, s.hasdefensiveriding, s.permitexpirydate, s.nationalid, f.name as facility, f.id as hubid, s.othernames,s.emailaddress,s.telephonenumber,s.telephonenumber2,s.telephonenumber3, d.name as district 
		FROM staff as s 
		LEFT JOIN facility as f ON (s.hubid = f.id) 
		LEFT JOIN district d ON(f.districtid  = d.id)
		WHERE s.designation = " . $pagetype . " " . $where_clause . "
		ORDER BY s.firstname ASC";
		//echo $query;
		//exit;
		$staff = \DB::select($query);
		return view('staff.index', compact('staff', 'pagetype', 'request'));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function index_mobile(Request $request)
	{
		$where_clause = '';
		// $query = "SELECT s.id, s.firstname,s.user_id, s.lastname, s.designation, s.hasdrivingpermit, s.hasbbtraining, s.isimmunizedforhb, s.hasdefensiveriding, s.permitexpirydate, s.nationalid, f.name as facility, f.id as hubid, s.othernames,s.emailaddress,s.telephonenumber,s.telephonenumber2,s.telephonenumber3, d.name as district 
		// FROM staff as s 
		// LEFT JOIN facility as f ON (s.hubid = f.id) 
		// LEFT JOIN district d ON(f.districtid  = d.id)
		// " . $where_clause . "
		// ORDER BY s.firstname ASC";

		$query = "SELECT rr.id, rr.name, rr.email, fa.name as 'hubname', rr.telephone_number, rr.driving_permit, 
		rr.defensive_driving, rr.bb_training, rr.hep_b_immunisation, fa.inchargephonenumber, fa.inchargephonenumber FROM restrackself_reg rr
		LEFT JOIN facility fa ON rr.hubid = fa.id";
		//echo $query;
		//exit;
		$staff = \DB::select($query);
		return view('staff.index_mobile', compact('staff', 'request'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function form($pagetype)
	{
		$lt = new LookupType();
		$lt->name = 'DESIGNATIONS';
		$hubsdropdown =  array_merge_maintain_keys(array('' => 'Select one'), getAllHubs());
		$poe_sites = array_merge_maintain_keys(array('' => 'Select one'), getPoeSites());
		$ref_labs = array_merge_maintain_keys(array('' => 'Select one'), getReferenceLabs());
		$bikes = array_merge_maintain_keys(array('' => 'Select one'), getAllUnAssignedBikes());
		$designation = array_merge_maintain_keys(array('' => 'Select one'), $lt->getOptionValuesAndDescription());
		$lt->name = 'YES_NO';
		$yes_no = $lt->getOptionValuesAndDescription();
		return view('staff.create', compact('pagetype', 'designation', 'hubsdropdown', 'bikes', 'yes_no', 'poe_sites', 'ref_labs'));
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{

		$staff = new Staff;
		$user = new User;


		try {
			//check that the rider being added is not already added
			if (Auth::user()->hasRole('hub_coordinator') && !Auth::user()->hasRole('national_hub_coordinator')) {
				$staff->hubid = Auth::user()->hubid;
				$user->hubid = Auth::user()->hubid;
			} else {
				if (\Request::has('facilityid')) {
					$staff->hubid = $request->facilityid;
					$user->hubid = $request->facilityid;
				}
			}
			if (\Request::has('poe_site')) {
				$staff->facilityid = $request->poe_site;
			}
			if (\Request::has('ref_lab')) {
				$staff->facilityid = $request->ref_lab;
				$user->ref_lab = $request->ref_lab;
			}

			$staff->type = $request->type;
			if (empty($request->motorbikeid)) {
				$request->motorbikeid = 0;
			} else {
				$staff->motorbikeid = $request->motorbikeid;
			}
			$staff->isactive = 1;
			$staff->firstname = $request->firstname;
			$staff->lastname = $request->lastname;
			$staff->othernames = $request->othernames;
			$staff->emailaddress = $request->emailaddress;
			$staff->telephonenumber = $request->telephonenumber;
			$staff->telephonenumber2 = $request->telephonenumber2;
			$staff->telephonenumber3 = $request->telephonenumber3;
			$staff->nationalid = $request->nationalid;

			$staff->motorbikeid = $request->motorbikeid;
			$staff->type = $request->type;
			$staff->designation = $request->type;
			if ($request->type == 1) {
				$staff->hasdrivingpermit = $request->hasdrivingpermit;
				$staff->hasdefensiveriding = $request->hasdefensiveriding;
				$staff->hasbbtraining = $request->hasbbtraining;
				$staff->permitexpirydate = getMysqlDateFormat($request->permitexpirydate);
				$staff->isimmunizedforhb = $request->isimmunizedforhb;
			}
			$staff->save();
			//now add the transporter to the bike, if adding sample transporter
			if ($request->type == 1) {
				if (!empty($request->motorbikeid)) {
					$bike = Equipment::findOrFail($request->motorbikeid);
					$bike->sampletransporterid = $staff->id;
					$bike->save();
				}
			}

			//create a user the user

			if (empty($request->emailaddress)) {
				$user->email = date('ymdhi') . '@dev.com';
			} else {
				$user->email = $request->emailaddress;
			}

			$roles = array();
			if ($request->type == 1) {
				$roles = array(15);
			} elseif ($request->type == 2) {
				$roles = array(5);
			} elseif ($request->type == 4) {
				$roles = array(14);
			} elseif ($request->type == 5) {
				$roles = array(13);
			} elseif ($request->type == 6) {
				$roles = array(17);
			} elseif ($request->type == 3) {
				$roles = array(12);
			} elseif ($request->type == 8) {
				$roles = array(21);
			} elseif ($request->type == 7) {
				$user->facilityid = 900000;
				$roles = array(20);
			}

			$user->name = $request->firstname . ' ' . $request->lastname;
			$user->setPasswordAttribute($request->password);

			$user->staff_id = $staff->id;
			$user->username = $request->username;
			$user->save();
			$user->roles()->attach($roles);
			//dd($user);
			$staff->user_id = $user->id;
			$staff->save();
			return redirect()->route('staff.show', array('id' => $staff->id));
		} catch (\Exception $e) {

			print_r('faild to save' . $e);
			exit;
			return redirect()->url('staff/new/' . $request->type)
				->with('flash_message', 'failed');
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$staff = Staff::findOrFail($id); //Find post of id = $id
		return view('staff.show', compact('staff'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$staff = Staff::findOrFail($id);
		$pagetype = $staff->type;
		$lt = new LookupType();
		$lt->name = 'DESIGNATIONS';
		$hubsdropdown = getAllHubs();
		$designation = array_merge_maintain_keys(array('' => 'Select one'), $lt->getOptionValuesAndDescription());
		$lt->name = 'YES_NO';
		$yes_no = $lt->getOptionValuesAndDescription();
		return view('staff.edit', compact('yes_no', 'staff', 'pagetype', 'designation', 'hubsdropdown'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{

		$staff = Staff::findOrFail($id);

		try {
			$staff->facilityid = $request->poe_site;
			$staff->hubid = $request->facilityid;
			$staff->firstname = $request->firstname;
			$staff->lastname = $request->lastname;
			$staff->othernames = $request->othernames;
			$staff->emailaddress = $request->emailaddress;
			$staff->telephonenumber = $request->telephonenumber;
			$staff->nationalid = $request->nationalid;

			$user = User::where('staff_id', '=', $staff->id)->first();
			$user->hubid = $request->facilityid;

			if ($staff->type == 1) {
				$staff->hasdrivingpermit = $request->hasdrivingpermit;
				$staff->hasdefensiveriding = $request->hasdefensiveriding;
				$staff->hasbbtraining = $request->hasbbtraining;
				$staff->permitexpirydate = getMysqlDateFormat($request->permitexpirydate);
				$staff->isimmunizedforhb = $request->isimmunizedforhb;
			} else {
				$staff->designation = $request->designation;
			}
			$user->save();
			$staff->save();
			return redirect()->route('staff.show', array('id' => $staff->id));
		} catch (\Exception $e) {

			print_r('faild to save' . $e);
			exit;
			return redirect()->url('staff/new/' . $request->type)
				->with('flash_message', 'failed');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {}

	/**
	 *
	 * @return \Illuminate\Http\Response - bikes for a hub which do not have a rider
	 */
	public function bikeWithoutRider(Request $request)
	{
		$hubid = $request->hubid;
		if ($request->ajax()) {
			$bikes = \App\Models\Equipment::where('hubid', $request->hubid)->whereDoesntHave('bikerider')->pluck("numberplate", "id");
			$html_options = getGenerateHtmlforAjaxSelect($bikes);
			return response()->json(['options' => $html_options]);
		}
	}
}
