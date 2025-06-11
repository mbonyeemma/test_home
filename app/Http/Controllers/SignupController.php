<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use \App\Models\LookupType as LookupType;
use \App\Models\Staff as Staff;
use \App\Models\User as User;

class SignupController extends Controller {

    public function __construct() {
        //$this->middleware(['auth', 'clearance'])->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {
		return view('signup.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) { 
		//\Validator::make(['drivingpermit' => 'unique'], ['nationalid' => 'unique'])->passes();
		//  $this->validate($request, [
		// 	'drivingpermit' => 'nullable|required|max:8|unique:staff',
		// 	'nationalid' => 'nullable|unique:staff'
		// ]);
		
		$staff = new Staff;
		try {
			//check that the rider being added is not already added
			$staff->isactive = 1;
			$staff->firstname = $request->firstname;
			$staff->lastname = $request->lastname;
			$staff->othernames = $request->othernames;
			$staff->emailaddress = $request->emailaddress;
			$staff->telephonenumber = $request->telephonenumber;
			$staff->code = $request->code;
			$staff->designation = 5;
			$staff->type = 5;
			$staff->save();
			
			//create a user for the staff member
			$user = new User();
			if(empty($request->emailaddress)){
				$user->email = date('ymdhi').'@dev.com';
			}else{
				$user->email = $request->emailaddress;
			}
			$roles = array(13);		
			$user->name = $request->firstname.' '.$request->lastname;
			$user->setPasswordAttribute('password');
			$user->username = $request->firstname;		
			$user->save();	
			$user->roles()->attach($roles);
			
			return redirect()->route('signup.show', array('id' => $staff->id));
	
		}catch (\Exception $e) {
			
			print_r('faild to save'.$e);
			exit;
			return redirect()->url('staff/new/'.$request->type)
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
        return view ('signup.view', compact('staff'));
    }

       
}