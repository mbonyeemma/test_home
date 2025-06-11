<?php
// app/Http/Controllers/PostController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
class eidrController extends Controller {

    public function __construct() {
        //$this->middleware(['auth', 'clearance'])->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($name) {
		switch ($name){
		   case 'events':
			$this -> events();
			break;
		   case 'bar':
			$this ->login();
			break; 
		  defautlt:
			abort(404,'bad request');
			break;
		 }
		/*$query = "SELECT s.id, s.firstname, s.lastname, s.designation, s.hasdrivingpermit, s.hasbbtraining, s.isimmunizedforhb, s.hasdefensiveriding, s.permitexpirydate, s.nationalid, f.name as facility 
		FROM staff as s 
		INNER JOIN facility as f ON (s.hubid = f.id) 
		ORDER BY s.firstname ASC";
		$staff = \DB::select($query);*/
		//return response()->json($staff);
    }
    
	public function events(Request $request){
		//to be called in the form
		//api/events?start_date=2018-09-13&end_date=2018-09-14
		//where clause
		/*$where_clause = '';
		$start_date = $request->start_date;
	  	$end_date = $request->end_date;
		if($start_date != '' && $end_date != ''){
			$where_clause = " AND (pe.created_at BETWEEN '".$start_date."' AND '".$end_date."')";
		}
		$query = "SELECT p.case_id, of.name as 'facility_of_origin', df.name as destination, f.name AS location, pe.longitude, pe.latitude, pe.created_at as `date`, CONCAT(st.firstname, ' ', st.lastname, ' ', st.othernames) as contact_person_name, st.telephonenumber as contact_person_contact,
					IF (pe.`status` = 1, 'IDSR_ST_PICKED', 
					IF(pe.status = 2, 'IDSR_ST_DELIVERED',
					 IF(pe.status = 3 'IDSR_ST_RECEIVED',
					 IF(pe.status = 4, 'IDSR_ST_IN_TRANSIT',
					 IF(pe.status = 6, 'IDSR_ST_IN_DELIVERED', 'IDSR_ST_DELIVERED'
					))))) as `status` 
					FROM packagemovement_events pe
					INNER JOIN package p ON(pe.package_id= p.id) 
					INNER JOIN facility of ON (of.id = p.facilityid)
					 INNER JOIN facility df ON (df.id = p.final_destination) 
					INNER JOIN facility f ON (f.id = pe.location)
					INNER JOIN staff st ON(pe.created_by = st.id) 
					WHERE category_id = 11 ".$where_clause."
					ORDER BY pe.created_at"; 
					//echo $query; exit;
		$events = \DB::select($query);*/
		return response()->json('test1'=>'a','test2'=>'pk');
	}
	public function login(){
		//
	}
}